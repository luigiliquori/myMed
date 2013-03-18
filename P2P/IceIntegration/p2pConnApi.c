/* $Id: p2pConnApi.c 3043 2010-01-04 14:20:22Z bennylp $ */
/*
 * Copyright (C) 2011 Peter Neuss
 *
 * This module
 */
#include <stdio.h>
#include <stdlib.h>
#include <pjlib.h>
#include <pjlib-util.h>

#include "p2pConnApi.h"
#include "rsComm.h"
#include <pthread.h>

#define THIS_FILE   "p2pConnApi.c"

int Verbose = 1;

static struct {
    FILE *log_fhnd;
    pj_caching_pool cp;
    pj_pool_t *pool;
    pj_ice_strans_cfg ice_cfg;
    struct P2pConnectionOptions opt;
    pj_thread_t *thread;
    pj_bool_t thread_quit_flag;
    p2p_callbacks callbacks;
    int service;
} Globals;

/* For this demo app, configure longer STUN keep-alive time
 * so that it does't clutter the screen output.
 */
#define KA_INTERVAL 300


#define CHECK(expr)	status=expr; \
			if (status!=PJ_SUCCESS) { \
			    err_exit(#expr, status); \
			}



/* temporary hack
 * we need a way to keep outstanding connection attempts, with an id
 * to tie them to the reply.
 *
 */

#define MaxConns 20
struct P2pConnection OutstandingConns[MaxConns];
static int NextConn = 0;

static struct P2pConnection *nextConn() {
    if (NextConn >= MaxConns) {
        printf("Max Conns exceeded\n");
        exit(33);
    }
    OutstandingConns[NextConn].id = NextConn;
    return &OutstandingConns[NextConn++];
}

static struct P2pConnection *getConnFromId(int id) {
    if (id >= 0 && id < MaxConns) return &OutstandingConns[id];
    else return NULL;
}

/*
 *  These basic functions have been modified from those in
 *  the icedemo.c file of pjlib
 *
 */

/* Utility to display error messages */
static void icedemo_perror(const char *title, pj_status_t status) {
    char errmsg[PJ_ERR_MSG_SIZE];

    pj_strerror(status, errmsg, sizeof (errmsg));
    PJ_LOG(1, (THIS_FILE, "%s: %s", title, errmsg));
}

/* Utility: display error message and exit application (usually
 * because of fatal error.
 */
static void err_exit(const char *title, pj_status_t status) {
    if (status != PJ_SUCCESS) {
        icedemo_perror(title, status);
    }
    PJ_LOG(3, (THIS_FILE, "Shutting down.."));

    /* TODO: destroy ALL icest instances
    if (icedemo.icest)
        pj_ice_strans_destroy(icedemo.icest);
     */

    pj_thread_sleep(500);

    Globals.thread_quit_flag = PJ_TRUE;
    if (Globals.thread) {
        pj_thread_join(Globals.thread);
        pj_thread_destroy(Globals.thread);
    }

    if (Globals.ice_cfg.stun_cfg.ioqueue)
        pj_ioqueue_destroy(Globals.ice_cfg.stun_cfg.ioqueue);

    if (Globals.ice_cfg.stun_cfg.timer_heap)
        pj_timer_heap_destroy(Globals.ice_cfg.stun_cfg.timer_heap);

    pj_caching_pool_destroy(&Globals.cp);

    pj_shutdown();

    if (Globals.log_fhnd) {
        fclose(Globals.log_fhnd);
        Globals.log_fhnd = NULL;
    }

    exit(status != PJ_SUCCESS);
}

/* Utility to nullify parsed remote info */
static void reset_rem_info(struct P2pConnection *conn) {
    pj_bzero(&conn->remInfo, sizeof (conn->remInfo));
}

/* log callback to write to file */
static void log_func(int level, const char *data, int len) {
    pj_log_write(level, data, len);
    if (Globals.log_fhnd) {
        if (fwrite(data, len, 1, Globals.log_fhnd) != 1)
            return;
    }
}

/*
 * This function checks for events from both timer and ioqueue (for
 * network events). It is invoked by the worker thread.
 */
static pj_status_t handle_events(unsigned max_msec, unsigned *p_count) {

    enum {
        MAX_NET_EVENTS = 1
    };
    pj_time_val max_timeout = {0, 0};
    pj_time_val timeout = {0, 0};
    unsigned count = 0, net_event_count = 0;
    int c;

    max_timeout.msec = max_msec;

    /* Poll the timer to run it and also to retrieve the earliest entry. */
    timeout.sec = timeout.msec = 0;
    c = pj_timer_heap_poll(Globals.ice_cfg.stun_cfg.timer_heap, &timeout);
    if (c > 0)
        count += c;

    /* timer_heap_poll should never ever returns negative value, or otherwise
     * ioqueue_poll() will block forever!
     */
    pj_assert(timeout.sec >= 0 && timeout.msec >= 0);
    if (timeout.msec >= 1000) timeout.msec = 999;

    /* compare the value with the timeout to wait from timer, and use the
     * minimum value.
     */
    if (PJ_TIME_VAL_GT(timeout, max_timeout))
        timeout = max_timeout;

    /* Poll ioqueue.
     * Repeat polling the ioqueue while we have immediate events, because
     * timer heap may process more than one events, so if we only process
     * one network events at a time (such as when IOCP backend is used),
     * the ioqueue may have trouble keeping up with the request rate.
     *
     * For example, for each send() request, one network event will be
     *   reported by ioqueue for the send() completion. If we don't poll
     *   the ioqueue often enough, the send() completion will not be
     *   reported in timely manner.
     */
    do {
        c = pj_ioqueue_poll(Globals.ice_cfg.stun_cfg.ioqueue, &timeout);
        if (c < 0) {
            pj_status_t err = pj_get_netos_error();
            pj_thread_sleep(PJ_TIME_VAL_MSEC(timeout));
            if (p_count)
                *p_count = count;
            return err;
        } else if (c == 0) {
            break;
        } else {
            net_event_count += c;
            timeout.sec = timeout.msec = 0;
        }
    } while (c > 0 && net_event_count < MAX_NET_EVENTS);

    count += net_event_count;
    if (p_count)
        *p_count = count;

    return PJ_SUCCESS;

}

/*
 * This is the worker thread that polls event in the background.
 */
static int icedemo_worker_thread(void *unused) {
    PJ_UNUSED_ARG(unused);

    while (!Globals.thread_quit_flag) {
        handle_events(500, NULL);
    }

    return 0;
}

static pj_status_t internal_init(struct P2pConnectionOptions *options,
        pj_ice_strans_cfg *cfg) {
    pj_status_t status;

    if (options->log_file) {
        Globals.log_fhnd = fopen(options->log_file, "a");
        pj_log_set_log_func(&log_func);
    }

    /* Initialize the libraries before anything else */
    CHECK(pj_init());
    CHECK(pjlib_util_init());
    CHECK(pjnath_init());

    /* Must create pool factory, where memory allocations come from */
    pj_caching_pool_init(&Globals.cp, NULL, 0);

    /* Init our ICE settings with null values */
    pj_ice_strans_cfg_default(cfg);

    cfg->stun_cfg.pf = &Globals.cp.factory;

    /* Create application memory pool */
    Globals.pool = pj_pool_create(&Globals.cp.factory, "icedemo",
            512, 512, NULL);

    /* Create timer heap for timer stuff */
    CHECK(pj_timer_heap_create(Globals.pool, 100,
            &cfg->stun_cfg.timer_heap));

    /* and create ioqueue for network I/O stuff */
    CHECK(pj_ioqueue_create(Globals.pool, 16,
            &cfg->stun_cfg.ioqueue));

    if (Verbose) PJ_LOG(5, (THIS_FILE, "ioq is %d", cfg->stun_cfg.ioqueue));
    /* something must poll the timer heap and ioqueue,
     * unless we're on Symbian where the timer heap and ioqueue run
     * on themselves.
     */
    CHECK(pj_thread_create(Globals.pool, "icedemo", &icedemo_worker_thread,
            NULL, 0, 0, &Globals.thread));

    cfg->af = pj_AF_INET();

    /* Create DNS resolver if nameserver is set */
    if (options->ns.slen) {
        CHECK(pj_dns_resolver_create(&Globals.cp.factory,
                "resolver",
                0,
                Globals.ice_cfg.stun_cfg.timer_heap,
                Globals.ice_cfg.stun_cfg.ioqueue,
                &Globals.ice_cfg.resolver));

        CHECK(pj_dns_resolver_set_ns(Globals.ice_cfg.resolver, 1,
                &options->ns, NULL));
    }

    /* -= Start initializing ICE stream transport config =- */

    /* Maximum number of host candidates */
    if (options->max_host != -1)
        cfg->stun.max_host_cands = options->max_host;

    /* Nomination strategy */
    if (options->regular)
        cfg->opt.aggressive = PJ_FALSE;
    else
        cfg->opt.aggressive = PJ_TRUE;

    /* Configure STUN/srflx candidate resolution */
    if (options->stun_srv.slen) {
        char *pos;

        /* Command line option may contain port number */
        if ((pos = pj_strchr(&options->stun_srv, ':')) != NULL) {
            cfg->stun.server.ptr = options->stun_srv.ptr;
            cfg->stun.server.slen = (pos - options->stun_srv.ptr);

            cfg->stun.port = (pj_uint16_t) atoi(pos + 1);
        } else {
            cfg->stun.server = options->stun_srv;
            cfg->stun.port = PJ_STUN_PORT;
        }

        /* For this demo app, configure longer STUN keep-alive time
         * so that it does't clutter the screen output.
         */
        cfg->stun.cfg.ka_interval = KA_INTERVAL;
    }

    /* Configure TURN candidate */
    if (options->turn_srv.slen) {
        char *pos;

        /* Command line option may contain port number */
        if ((pos = pj_strchr(&options->turn_srv, ':')) != NULL) {
            cfg->turn.server.ptr = options->turn_srv.ptr;
            cfg->turn.server.slen = (pos - options->turn_srv.ptr);

            cfg->turn.port = (pj_uint16_t) atoi(pos + 1);
        } else {
            cfg->turn.server = options->turn_srv;
            cfg->turn.port = PJ_STUN_PORT;
        }

        /* TURN credential */
        cfg->turn.auth_cred.type = PJ_STUN_AUTH_CRED_STATIC;
        cfg->turn.auth_cred.data.static_cred.username = options->turn_username;
        cfg->turn.auth_cred.data.static_cred.data_type = PJ_STUN_PASSWD_PLAIN;
        cfg->turn.auth_cred.data.static_cred.data = options->turn_password;

        /* Connection type to TURN server */
        if (options->turn_tcp)
            cfg->turn.conn_type = PJ_TURN_TP_TCP;
        else
            cfg->turn.conn_type = PJ_TURN_TP_UDP;

        /* For this demo app, configure longer keep-alive time
         * so that it does't clutter the screen output.
         */
        cfg->turn.alloc_param.ka_interval = KA_INTERVAL;
    }

    /* -= That's it for now, initialization is complete =- */
    return PJ_SUCCESS;
}

// utility macro
#define PRINT(fmt, arg0, arg1, arg2, arg3, arg4, arg5)	    \
	printed = pj_ansi_snprintf(p, maxlen - (p-buffer),  \
				   fmt, arg0, arg1, arg2, arg3, arg4, arg5); \
	if (printed <= 0) return -PJ_ETOOSMALL; \
	p += printed

/* Utility to create a=candidate SDP attribute */
static int print_cand(char buffer[], unsigned maxlen,
        const pj_ice_sess_cand *cand) {
    char ipaddr[PJ_INET6_ADDRSTRLEN];
    char *p = buffer;
    int printed;

    PRINT("a=candidate:%.*s %u UDP %u %s %u typ ",
            (int) cand->foundation.slen,
            cand->foundation.ptr,
            (unsigned) cand->comp_id,
            cand->prio,
            pj_sockaddr_print(&cand->addr, ipaddr,
            sizeof (ipaddr), 0),
            (unsigned) pj_sockaddr_get_port(&cand->addr));

    PRINT("%s\n",
            pj_ice_get_cand_type_name(cand->type),
            0, 0, 0, 0, 0);

    if (p == buffer + maxlen)
        return -PJ_ETOOSMALL;

    *p = '\0';

    return p - buffer;
}

/*
 * Encode ICE information in SDP.
 */
static int encode_session(struct P2pConnection* conn) {
    char buffer[ContactInfoLen];
    char *p = buffer;
    int maxlen = ContactInfoLen;
    struct P2pConnectionOptions *opt = conn->opt;
    unsigned comp;
    int printed;
    pj_str_t local_ufrag, local_pwd;
    pj_status_t status;

    pj_assert(conn != NULL);
    pj_assert(conn->icest != NULL);
    /* Write "dummy" SDP v=, o=, s=, and t= lines */
    PRINT("v=0\no=- 3414953978 3414953978 IN IP4 localhost\ns=ice\nt=0 0\n",
            0, 0, 0, 0, 0, 0);

    /* Get ufrag and pwd from current session */
    pj_ice_strans_get_ufrag_pwd(conn->icest, &local_ufrag, &local_pwd,
            NULL, NULL);

    /* Write the a=ice-ufrag and a=ice-pwd attributes */
    PRINT("a=ice-ufrag:%.*s\na=ice-pwd:%.*s\n",
            (int) local_ufrag.slen,
            local_ufrag.ptr,
            (int) local_pwd.slen,
            local_pwd.ptr,
            0, 0);

    /* Write each component */
    for (comp = 0; comp < opt->comp_cnt; ++comp) {
        unsigned j, cand_cnt;
        pj_ice_sess_cand cand[PJ_ICE_ST_MAX_CAND];
        char ipaddr[PJ_INET6_ADDRSTRLEN];

        /* Get default candidate for the component */
        status = pj_ice_strans_get_def_cand(conn->icest, comp + 1, &cand[0]);
        if (status != PJ_SUCCESS)
            return -status;

        /* Write the default address */
        if (comp == 0) {
            /* For component 1, default address is in m= and c= lines */
            PRINT("m=audio %d RTP/AVP 0\n"
                    "c=IN IP4 %s\n",
                    (int) pj_sockaddr_get_port(&cand[0].addr),
                    pj_sockaddr_print(&cand[0].addr, ipaddr,
                    sizeof (ipaddr), 0),
                    0, 0, 0, 0);
        } else if (comp == 1) {
            /* For component 2, default address is in a=rtcp line */
            PRINT("a=rtcp:%d IN IP4 %s\n",
                    (int) pj_sockaddr_get_port(&cand[0].addr),
                    pj_sockaddr_print(&cand[0].addr, ipaddr,
                    sizeof (ipaddr), 0),
                    0, 0, 0, 0);
        } else {
            /* For other components, we'll just invent this.. */
            PRINT("a=Xice-defcand:%d IN IP4 %s\n",
                    (int) pj_sockaddr_get_port(&cand[0].addr),
                    pj_sockaddr_print(&cand[0].addr, ipaddr,
                    sizeof (ipaddr), 0),
                    0, 0, 0, 0);
        }
        /* Enumerate all candidates for this component */
        /* There appears to have been an error in original icedemo.c; cand_cnt wasn't initialized */
        cand_cnt = PJ_ICE_ST_MAX_CAND;
        //printf("In encode, cand_cnt is %d\n", cand_cnt);
        status = pj_ice_strans_enum_cands(conn->icest, comp + 1,
                &cand_cnt, cand);
        if (status != PJ_SUCCESS)
            return -status;

        /* And encode the candidates as SDP */
        for (j = 0; j < cand_cnt; ++j) {
            printed = print_cand(p, maxlen - (p - conn->contactInfo), &cand[j]);
            if (printed < 0)
                return -PJ_ETOOSMALL;
            p += printed;
        }
    }

    if (p == buffer + maxlen)
        return -PJ_ETOOSMALL;

    *p = '\0';
    //printf("buffer: %s\n", buffer);
    strncpy(conn->contactInfo, buffer, ContactInfoLen);
    //printf("buffer: %s\n", conn->contactInfo);
    return p - buffer;
}

/*
 * This is the callback that is registered to the ICE stream transport to
 * receive notification about ICE state progression.
 */
static void cb_on_ice_complete(pj_ice_strans *ice_st,
        pj_ice_strans_op op,
        pj_status_t status) {
    const char *opname =
            (op == PJ_ICE_STRANS_OP_INIT ? "initialization" :
            (op == PJ_ICE_STRANS_OP_NEGOTIATION ? "negotiation" : "unknown_op"));
    //printf(">>strans state: %d\n",pj_ice_strans_get_state(icedemo.icest));
    pj_assert(ice_st != NULL);
    struct P2pConnection *conn = (struct P2pConnection *) pj_ice_strans_get_user_data(ice_st);
    pj_assert(conn != NULL);
    if (status == PJ_SUCCESS) {
        PJ_LOG(3, (THIS_FILE, "ICE %s successful", opname));
        if (op == PJ_ICE_STRANS_OP_NEGOTIATION) conn->status = P2pSuccess; // if only init, still in connection process
    } else {
        char errmsg[PJ_ERR_MSG_SIZE];

        pj_strerror(status, errmsg, sizeof (errmsg));
        PJ_LOG(1, (THIS_FILE, "ICE %s failed: %s", opname, errmsg));
        pj_ice_strans_destroy(ice_st);
        conn->status = P2pFailure;
    }
}

/*
 * This is the callback that is registered to the ICE stream transport to
 * receive notification about incoming data. By "data" it means application
 * data such as RTP/RTCP, and not packets that belong to ICE signaling (such
 * as STUN connectivity checks or TURN signaling).
 */
static void cb_on_rx_data(pj_ice_strans *ice_st,
        unsigned comp_id,
        void *pkt, pj_size_t size,
        const pj_sockaddr_t *src_addr,
        unsigned src_addr_len) {
    char ipstr[PJ_INET6_ADDRSTRLEN + 10];

    PJ_UNUSED_ARG(ice_st);
    PJ_UNUSED_ARG(src_addr_len);
    PJ_UNUSED_ARG(pkt);

    // Don't do this! It will ruin the packet buffer in case TCP is used!
    //((char*)pkt)[size] = '\0';

    PJ_LOG(3, (THIS_FILE, "Component %d: received %d bytes data from %s: \"%.*s\"",
            comp_id, size,
            pj_sockaddr_print(src_addr, ipstr, sizeof (ipstr), 3),
            (unsigned) size,
            (char*) pkt));

    if (Verbose) PJ_LOG(3,(THIS_FILE,"received: %.*s\n", (unsigned) size, (char*) pkt));
    // find connection
    struct P2pConnection *conn = (struct P2pConnection *) pj_ice_strans_get_user_data(ice_st);
    if (conn != NULL) {
        PJ_LOG(3,(THIS_FILE,"received from: %s\n", conn->otherUser));
    } else {
        PJ_LOG(3,(THIS_FILE,"\nconnection not found\n"));
    }
    Globals.callbacks.onIncomingMessage(conn, pkt, size);
    // TODO: parms for this Globals.callbacks.onIncomingMessage();

    //addInputLine(pkt, size);
    fflush(stdout);
}

/*
 * Create ICE stream transport instance, invoked from the menu.
 */
static void p2p_create_instance(struct P2pConnection *conn,
        struct P2pConnectionOptions *opt) {
    pj_ice_strans_cb icecb;
    pj_status_t status;

    if (conn->icest != NULL) {
        puts("ICE instance already created, destroy it first");
        return;
    }

    conn->status = P2pConnecting;

    /* init the callback */
    pj_bzero(&icecb, sizeof (icecb));
    icecb.on_rx_data = cb_on_rx_data;
    icecb.on_ice_complete = cb_on_ice_complete;

    /* Init our ICE settings with null values */
    //pj_ice_strans_cfg_default(&conn->ice_cfg);


    /* create the instance */
    status = pj_ice_strans_create("icedemo", /* object name  */
            &conn->ice_cfg, /* settings	    */
            opt->comp_cnt, /* comp_cnt	    */
            (void *) conn, /* The strans points to our connection struct */
            &icecb, /* callback	    */
            &conn->icest) /* instance ptr */
            ;
    if (status != PJ_SUCCESS)
        icedemo_perror("error creating ice", status);
    else
        PJ_LOG(3, (THIS_FILE, "ICE instance successfully created"));
}

/*
 * Create ICE session
 */
static void p2p_init_session(struct P2pConnection *conn, unsigned rolechar) {
    pj_ice_sess_role role = (pj_tolower((pj_uint8_t) rolechar) == 'o' ?
            PJ_ICE_SESS_ROLE_CONTROLLING :
            PJ_ICE_SESS_ROLE_CONTROLLED);
    pj_status_t status;

    if (conn->icest == NULL) {
        PJ_LOG(1, (THIS_FILE, "Error: No ICE instance, create it first"));
        return;
    }

    if (pj_ice_strans_has_sess(conn->icest)) {
        PJ_LOG(1, (THIS_FILE, "Error: Session already created"));
        return;
    }

    status = pj_ice_strans_init_ice(conn->icest, role, NULL, NULL);
    if (status != PJ_SUCCESS)
        icedemo_perror("error creating session", status);
    else
        PJ_LOG(3, (THIS_FILE, "ICE session created"));

    reset_rem_info(conn);
}

/*
 * Input and parse SDP from the remote (containing remote's ICE information)
 * and save it to global variables.
 */
static void p2p_input_remote_from_str(struct P2pConnection *conn, char* remoteInfo) {
    //char linebuf[80];
    char *linebuf;
    unsigned media_cnt = 0;
    unsigned comp0_port = 0;
    char comp0_addr[80];
    pj_bool_t done = PJ_FALSE;

    //puts("Paste SDP from remote host, end with empty line");
    // ok, for now let's just brute force this
    // so, allocate a 'big-enough' array of lines, preparse into
    // lines, with strncpys all around, then proceed to their
    // line-parsing
    const int MaxLines = 50;
    const int MaxCharsPerLine = 200;
    char lines[MaxLines][MaxCharsPerLine];
    int numLines = 0;
    linebuf = strtok(remoteInfo, ";");
    while (linebuf != NULL) {
        strncpy(lines[numLines++], linebuf, MaxCharsPerLine);
        linebuf = strtok(NULL, ";");
    }

    reset_rem_info(conn);

    comp0_addr[0] = '\0';

    int currentLine = 0;
    while (!done) {
        int len;
        char *line;

        //printf(">");
        if (stdout) fflush(stdout);

        //if (fgets(linebuf, sizeof(linebuf), remoteInfo)==NULL)
        //    break;
        linebuf = lines[currentLine++];
        if (linebuf == NULL || strlen(linebuf) == 0) {
            done = PJ_TRUE;
            break;
        }
        if (Verbose) {
            PJ_LOG(5,(THIS_FILE,"****Line: |%s|\n", linebuf));
            fflush(stdout);
        }

        len = strlen(linebuf);
        while (len && (linebuf[len - 1] == '\r' || linebuf[len - 1] == ';'))
            linebuf[--len] = '\0';

        line = linebuf;
        while (len && pj_isspace(*line))
            ++line, --len;

        if (len == 0)
            break;

        /* Ignore subsequent media descriptors */
        if (media_cnt > 1)
            continue;

        switch (line[0]) {
            case 'm':
            {
                int cnt;
                char media[32], portstr[32];

                ++media_cnt;
                if (media_cnt > 1) {
                    puts("Media line ignored");
                    break;
                }

                cnt = sscanf(line + 2, "%s %s RTP/", media, portstr);
                if (cnt != 2) {
                    PJ_LOG(1, (THIS_FILE, "Error parsing media line"));
                    goto on_error;
                }

                comp0_port = atoi(portstr);

            }
                break;
            case 'c':
            {
                int cnt;
                char c[32], net[32], ip[80];

                cnt = sscanf(line + 2, "%s %s %s", c, net, ip);
                if (cnt != 3) {
                    PJ_LOG(1, (THIS_FILE, "Error parsing connection line"));
                    goto on_error;
                }

                strcpy(comp0_addr, ip);
            }
                break;
            case 'a':
            {
                char *attr = strtok(line + 2, ": \t\r\n");
                if (strcmp(attr, "ice-ufrag") == 0) {
                    strcpy(conn->remInfo.ufrag, attr + strlen(attr) + 1);
                } else if (strcmp(attr, "ice-pwd") == 0) {
                    strcpy(conn->remInfo.pwd, attr + strlen(attr) + 1);
                } else if (strcmp(attr, "rtcp") == 0) {
                    char *val = attr + strlen(attr) + 1;
                    int af, cnt;
                    int port;
                    char net[32], ip[64];
                    pj_str_t tmp_addr;
                    pj_status_t status;

                    cnt = sscanf(val, "%d IN %s %s", &port, net, ip);
                    if (cnt != 3) {
                        PJ_LOG(1, (THIS_FILE, "Error parsing rtcp attribute"));
                        goto on_error;
                    }

                    if (strchr(ip, ':'))
                        af = pj_AF_INET6();
                    else
                        af = pj_AF_INET();

                    pj_sockaddr_init(af, &conn->remInfo.def_addr[1], NULL, 0);
                    tmp_addr = pj_str(ip);
                    status = pj_sockaddr_set_str_addr(af, &conn->remInfo.def_addr[1],
                            &tmp_addr);
                    if (status != PJ_SUCCESS) {
                        PJ_LOG(1, (THIS_FILE, "Invalid IP address"));
                        goto on_error;
                    }
                    pj_sockaddr_set_port(&conn->remInfo.def_addr[1], (pj_uint16_t) port);

                } else if (strcmp(attr, "candidate") == 0) {
                    char *sdpcand = attr + strlen(attr) + 1;
                    int af, cnt;
                    char foundation[32], transport[12], ipaddr[80], type[32];
                    pj_str_t tmpaddr;
                    int comp_id, prio, port;
                    pj_ice_sess_cand *cand;
                    pj_status_t status;

                    cnt = sscanf(sdpcand, "%s %d %s %d %s %d typ %s",
                            foundation,
                            &comp_id,
                            transport,
                            &prio,
                            ipaddr,
                            &port,
                            type);
                    if (cnt != 7) {
                        PJ_LOG(1, (THIS_FILE, "error: Invalid ICE candidate line"));
                        goto on_error;
                    }

                    cand = &conn->remInfo.cand[conn->remInfo.cand_cnt];
                    pj_bzero(cand, sizeof (*cand));

                    if (strcmp(type, "host") == 0)
                        cand->type = PJ_ICE_CAND_TYPE_HOST;
                    else if (strcmp(type, "srflx") == 0)
                        cand->type = PJ_ICE_CAND_TYPE_SRFLX;
                    else if (strcmp(type, "relay") == 0)
                        cand->type = PJ_ICE_CAND_TYPE_RELAYED;
                    else {
                        PJ_LOG(1, (THIS_FILE, "Error: invalid candidate type '%s'",
                                type));
                        goto on_error;
                    }

                    cand->comp_id = (pj_uint8_t) comp_id;
                    pj_strdup2(Globals.pool, &cand->foundation, foundation);
                    cand->prio = prio;

                    if (strchr(ipaddr, ':'))
                        af = pj_AF_INET6();
                    else
                        af = pj_AF_INET();

                    tmpaddr = pj_str(ipaddr);
                    pj_sockaddr_init(af, &cand->addr, NULL, 0);
                    status = pj_sockaddr_set_str_addr(af, &cand->addr, &tmpaddr);
                    if (status != PJ_SUCCESS) {
                        PJ_LOG(1, (THIS_FILE, "Error: invalid IP address '%s'",
                                ipaddr));
                        goto on_error;
                    }

                    pj_sockaddr_set_port(&cand->addr, (pj_uint16_t) port);

                    ++conn->remInfo.cand_cnt;

                    if (cand->comp_id > conn->remInfo.comp_cnt)
                        conn->remInfo.comp_cnt = cand->comp_id;
                }
            }
                break;
        }
    }

    if (conn->remInfo.cand_cnt == 0 ||
            conn->remInfo.ufrag[0] == 0 ||
            conn->remInfo.pwd[0] == 0 ||
            conn->remInfo.comp_cnt == 0) {
        PJ_LOG(1, (THIS_FILE, "Error: not enough info"));
        goto on_error;
    }

    if (comp0_port == 0 || comp0_addr[0] == '\0') {
        PJ_LOG(1, (THIS_FILE, "Error: default address for component 0 not found"));
        goto on_error;
    } else {
        int af;
        pj_str_t tmp_addr;
        pj_status_t status;

        if (strchr(comp0_addr, ':'))
            af = pj_AF_INET6();
        else
            af = pj_AF_INET();

        pj_sockaddr_init(af, &conn->remInfo.def_addr[0], NULL, 0);
        tmp_addr = pj_str(comp0_addr);
        status = pj_sockaddr_set_str_addr(af, &conn->remInfo.def_addr[0],
                &tmp_addr);
        if (status != PJ_SUCCESS) {
            PJ_LOG(1, (THIS_FILE, "Invalid IP address in c= line"));
            goto on_error;
        }
        pj_sockaddr_set_port(&conn->remInfo.def_addr[0], (pj_uint16_t) comp0_port);
    }

    PJ_LOG(3, (THIS_FILE, "Done, %d remote candidate(s) added",
            conn->remInfo.cand_cnt));
    return;

on_error:
    reset_rem_info(conn);
}

/*
 * Start ICE negotiation! This function is invoked from the menu.
 */
static void p2p_start_nego(struct P2pConnection *conn) {
    pj_str_t rufrag, rpwd;
    pj_status_t status;

    if (Verbose) {
        PJ_LOG(3, (THIS_FILE, "p2p_start_nego.\n-- My contact info: %s\n-- RemoteInfo: %s\n", conn->contactInfo, conn->remoteInfoString));
    }
    if (conn->icest == NULL) {
        PJ_LOG(1, (THIS_FILE, "Error: No ICE instance, create it first"));
        return;
    }

    if (!pj_ice_strans_has_sess(conn->icest)) {
        PJ_LOG(1, (THIS_FILE, "Error: No ICE session, initialize first"));
        return;
    }

    if (conn->remInfo.cand_cnt == 0) {
        PJ_LOG(1, (THIS_FILE, "Error: No remote info, input remote info first"));
        return;
    }

    PJ_LOG(3, (THIS_FILE, "Starting ICE negotiation.."));

    status = pj_ice_strans_start_ice(conn->icest,
            pj_cstr(&rufrag, conn->remInfo.ufrag),
            pj_cstr(&rpwd, conn->remInfo.pwd),
            conn->remInfo.cand_cnt,
            conn->remInfo.cand);
    if (status != PJ_SUCCESS)
        icedemo_perror("Error starting ICE", status);
    else
        PJ_LOG(3, (THIS_FILE, "ICE negotiation started"));
}

/*
 * Send application data to remote agent.
 */
static void p2p_send_data(struct P2pConnection *conn, const char *data, int length) {
    unsigned comp_id = 1;   // for now, always 1, we have only 1 channel
    pj_status_t status;

    if (conn->icest == NULL) {
        PJ_LOG(1, (THIS_FILE, "Error: No ICE instance, create it first"));
        return;
    }

    if (!pj_ice_strans_has_sess(conn->icest)) {
        PJ_LOG(1, (THIS_FILE, "Error: No ICE session, initialize first"));
        return;
    }

    /*
    if (!pj_ice_strans_sess_is_complete(icedemo.icest)) {
        PJ_LOG(1,(THIS_FILE, "Error: ICE negotiation has not been started or is in progress"));
        return;
    }
     */

    if (comp_id < 1 || comp_id > pj_ice_strans_get_running_comp_cnt(conn->icest)) {
        PJ_LOG(1, (THIS_FILE, "Error: invalid component ID"));
        return;
    }

    status = pj_ice_strans_sendto(conn->icest, comp_id, data, length,
            &conn->remInfo.def_addr[comp_id - 1],
            pj_sockaddr_get_len(&conn->remInfo.def_addr[comp_id - 1]));
    if (status != PJ_SUCCESS)
        icedemo_perror("Error sending data", status);
    else
        PJ_LOG(3, (THIS_FILE, "Data sent"));
}


// some functions just for debugging
/*
 * Show information contained in the ICE stream transport. This is
 * invoked from the menu.
 */
/*
static void icedemo_show_ice(struct P2pConnection *conn) {
    static char buffer[1000];
    int len;

    if (conn->icest == NULL) {
        PJ_LOG(1, (THIS_FILE, "Error: No ICE instance, create it first"));
        return;
    }

    puts("General info");
    puts("---------------");
    printf("Component count    : %d\n", conn->opt->comp_cnt);
    printf("Status             : ");
    if (pj_ice_strans_sess_is_complete(conn->icest))
        puts("negotiation complete");
    else if (pj_ice_strans_sess_is_running(conn->icest))
        puts("negotiation is in progress");
    else if (pj_ice_strans_has_sess(conn->icest))
        puts("session ready");
    else
        puts("session not created");

    if (!pj_ice_strans_has_sess(conn->icest)) {
        puts("Create the session first to see more info");
        return;
    }

    printf("Negotiated comp_cnt: %d\n",
            pj_ice_strans_get_running_comp_cnt(conn->icest));
    printf("Role               : %s\n",
            pj_ice_strans_get_role(conn->icest) == PJ_ICE_SESS_ROLE_CONTROLLED ?
            "controlled" : "controlling");

    len = encode_session(buffer, sizeof (buffer));
    if (len < 0)
        err_exit("not enough buffer to show ICE status", -len);

    puts("");
    printf("Local SDP (paste this to remote host):\n"
            "--------------------------------------\n"
            "%s\n", buffer);


    puts("");
    puts("Remote info:\n"
            "----------------------");
    if (conn->remInfo.cand_cnt == 0) {
        puts("No remote info yet");
    } else {
        unsigned i;

        printf("Remote ufrag       : %s\n", conn->remInfo.ufrag);
        printf("Remote password    : %s\n", conn->remInfo.pwd);
        printf("Remote cand. cnt.  : %d\n", conn->remInfo.cand_cnt);

        for (i = 0; i < conn->remInfo.cand_cnt; ++i) {
            len = print_cand(buffer, sizeof (buffer), &conn->remInfo.cand[i]);
            if (len < 0)
                err_exit("not enough buffer to show ICE status", -len);

            printf("  %s", buffer);
        }
    }
}
*/

/* Now, the slightly higher-level functions which use the above functions */

/*
 *  This function does the 'first half' of the connection creation:
 *    1. creates the pj_ice_strans structure
 *    2. initializes it
 *    3. encodes the contact information into a string
 *
 *  This can take time (STUN and TURN protocols may be invoked) so it
 *  is frequently called in a thread.
 * 
 *  It is needed when initiating a connection (role = 'o') and when
 *  accepting a connection request (role = 'a') hence it is in a
 *  separate function.
 */

/*  since we are sending line-based infor to RS for now, use ; as
 *  separator for contactInfo.
 */
void replaceLfWithSemi(char* input) {
    int i;
    int len = strlen(input);
    for (i = 0; i < len; i++) {
        if (input[i] == '\n') input[i] = ';';
    }
}

void first_part_connection(struct P2pConnection* conn,
        struct P2pConnectionOptions *opt, char role) {
    memset(conn->contactInfo, 0, ContactInfoLen);

    p2p_create_instance(conn, opt);
    // wait till candidate gathering finished
    while (pj_ice_strans_get_state(conn->icest) != PJ_ICE_STRANS_STATE_READY);
    p2p_init_session(conn, role);
    // wait until session is ready
    while (pj_ice_strans_get_state(conn->icest) != PJ_ICE_STRANS_STATE_SESS_READY);
    int len = encode_session(conn);
    replaceLfWithSemi(conn->contactInfo);
    if (len < 0) {
        icedemo_perror("not enough buffer to show ICE status", -len);
        exit(23);
    }
    if (Verbose) {
        PJ_LOG(4, (THIS_FILE,"first_part_connection contact info: |%s|\n", conn->contactInfo));
        fflush(stdout);
    }
}

/*
 *  This simply serves as a function to execute in a thread.
 *  It does the first part of the connection and sends a
 *  cReq to the RS.  The remaining part will happen in the
 *  handler of the cReply.
 */
void *p2p_initiate_connection(void *ptr) {//struct P2pConnection* conn, char* otherUser,        struct P2pConnectionOptions *opt) {
    struct P2pConnection *conn = (struct P2pConnection *) ptr;
    // register thread with pj
    pj_status_t rc;
    pj_thread_desc desc;
    pj_bzero(desc, sizeof (desc));
    pj_thread_t *this_thread;
    rc = pj_thread_register("thrIn", desc, &this_thread);

    first_part_connection(conn, conn->opt, 'o');
    rsConnReq(conn->otherUser, Globals.service, conn->id, conn->contactInfo);
    PJ_LOG(2,(THIS_FILE, "Sending cReq to %s service %d id %d\n", conn->otherUser, Globals.service, conn->id));
    pthread_exit(NULL);
}

/*
 *  Connect: 
 *    Spawn a thread to begin the connection and send a cReq to the RS
 *
 */
void p2p_connect_thread(struct P2pConnection *conn) {
    pthread_t beginConnThread;
    int returnValue;
    returnValue = pthread_create(&beginConnThread, NULL, &p2p_initiate_connection, (void*) conn);
}

/*
 *  Function to execute in a thread to complete the connection by
 *  initiating the ice negotiation.
 *
 */
void *p2p_connection_reply(void *ptr) {
    struct P2pConnection *conn = (struct P2pConnection *) ptr;
    // register thread with pj
    pj_status_t rc;
    pj_thread_desc desc;
    pj_bzero(desc, sizeof (desc));
    pj_thread_t *this_thread;
    rc = pj_thread_register("thrIn", desc, &this_thread);

    if (Verbose) {
        PJ_LOG(3, (THIS_FILE, "p2p_connection_reply.\n-- My contact info: %s\n-- RemoteInfo: %s\n", conn->contactInfo, conn->remoteInfoString));
    }
    p2p_input_remote_from_str(conn, conn->remoteInfoString);
    if (Verbose) {
        PJ_LOG(3, (THIS_FILE, "p2p_connection_reply.\n-- My contact info: %s\n-- RemoteInfo: %s\n", conn->contactInfo, conn->remoteInfoString));
    }
    p2p_start_nego(conn);
    Globals.callbacks.onNewConnection(conn);
    pthread_exit(NULL);
}

/*
 *  Connect: 
 *    Spawn a thread to finish the connection process
 *
 */
void p2p_connection_reply_thread(struct P2pConnection *conn) {
    pthread_t beginConnReplyThread;
    int returnValue;
    returnValue = pthread_create(&beginConnReplyThread, NULL, &p2p_connection_reply, (void*) conn);
}

/*
 * Function to handle a cReq from the RS
 *
 */
void *p2p_connection_request(void *ptr) {
    struct P2pConnection *conn = (struct P2pConnection *) ptr;

        // register thread with pj
    pj_status_t rc;
    pj_thread_desc desc;
    pj_bzero(desc, sizeof (desc));
    pj_thread_t *this_thread;
    rc = pj_thread_register("thrIn", desc, &this_thread);

    //todo: is this necessary?  icedemo_stop_session();
    // see if user wants connection
    if (!Globals.callbacks.onNewConnectionRequest(conn)) {
        // clean up if necessary
        pthread_exit(NULL);
    }
    // Do the first step
    first_part_connection(conn, conn->opt, 'a');

    // send the reply
    rsConnReplyInt(conn->id, conn->contactInfo);

    // start the negotiation
    p2p_input_remote_from_str(conn, conn->remoteInfoString);
    p2p_start_nego(conn);
    Globals.callbacks.onNewConnection(conn);
    pthread_exit(NULL);
}

/*
 *  Connect:
 *    Spawn a thread to answer a connection request
 *
 */
void p2p_connection_request_thread(struct P2pConnection *conn) {
    pthread_t beginConnReqThread;
    int returnValue;
    returnValue = pthread_create(&beginConnReqThread, NULL, &p2p_connection_request, (void*) conn);
}

/*
 *  This function creates and initializes the connection, and sends
 *  a connectionRequest to the other user via the RS
 */
void getConnection(char* otherUser, int service) {
    if (Verbose) PJ_LOG(2, (THIS_FILE, "getConnection to %s\n", otherUser));
    char contactInfo[ContactInfoLen];
    memset(contactInfo, 0, ContactInfoLen);

    struct P2pConnection *conn = nextConn();
    strncpy(conn->otherUser, otherUser, MAX_NAME_LENGTH);
    conn->opt = &Globals.opt;
    conn->ice_cfg = Globals.ice_cfg;
    if (Verbose) PJ_LOG(3, (THIS_FILE, "stunserver %.*s\n", conn->opt->stun_srv.slen, conn->opt->stun_srv.ptr));
    if (Verbose) {
        PJ_LOG(5, (THIS_FILE, "ioq is %d %d %d %d %d\n", conn->ice_cfg.stun_cfg.ioqueue, conn->ice_cfg.stun_cfg.pf, conn->ice_cfg.stun_cfg.timer_heap, conn->ice_cfg.stun_cfg.rto_msec, conn->ice_cfg.stun_cfg.res_cache_msec));
    }
    p2p_connect_thread(conn);
}


void p2p_send_string(struct P2pConnection *conn, char* message) {
    p2p_send_data(conn, message, strlen(message));
}

void p2p_send_bytes(struct P2pConnection *conn, char* message, int length) {
    p2p_send_data(conn, message, length);
}

/* communication with RS */

#define BUFFER_SIZE 1024

void processConnRequest(char *restOfCommand) {
    // first, find conn structure
    if (Verbose) PJ_LOG(3, (THIS_FILE, "processConnRequest, roc: %s\n", restOfCommand));
    struct P2pConnection *conn = nextConn();
    (*conn).opt = &Globals.opt;
    conn->ice_cfg = Globals.ice_cfg;
    // parse rest of command
    char* endOfToken = strchr(restOfCommand, ' ');
    strncpy(conn->otherUser, restOfCommand, endOfToken - restOfCommand);
    restOfCommand = endOfToken;
    int service = atoi(restOfCommand);
    if (service != Globals.service) {
        PJ_LOG(3, (THIS_FILE, "processConnRequest refusing connection for service %d, this is service %d\n", service, Globals.service));
        return;
    }
    restOfCommand = strchr(restOfCommand+1, ' ');
    conn->id = atoi(restOfCommand);
    restOfCommand = strchr(restOfCommand+1, ' ');
    strncpy(conn->remoteInfoString, restOfCommand, ContactInfoLen);
    if (Verbose) {
        PJ_LOG(3, (THIS_FILE, "processConnRequest, id: %d otherUser: %s contactInfo: %s\n", conn->id, conn->otherUser, conn->contactInfo));
    }
    p2p_connection_request_thread(conn);
}

void processConnReply(char *restOfCommand) {
    // first, find conn structure
    if (Verbose) PJ_LOG(2, (THIS_FILE, "processConnReply, roc: %s\n", restOfCommand));
    // parse rest of command
    int connId = atoi(restOfCommand);
    struct P2pConnection *conn = getConnFromId(connId);
    if (conn == NULL) {
        PJ_LOG(1,(THIS_FILE, "***Error, connection with id %d not found", connId));
        return;
    } else {
        PJ_LOG(2, (THIS_FILE, "processConnReply: connection found id: %d otheruser: %s",conn->id, conn->otherUser));
        PJ_LOG(5, (THIS_FILE, "contact info: %s\n", conn->contactInfo));
    }
    restOfCommand = strchr(restOfCommand, ' ');
    strncpy(conn->remoteInfoString, restOfCommand, ContactInfoLen);
    p2p_connection_reply_thread(conn);
}

void processUsersList(char *restOfCommand) {
    if (Verbose) PJ_LOG(3, (THIS_FILE, "|%s|", restOfCommand));
    //parseUserList(restOfCommand);
    Globals.callbacks.onNewUsers(restOfCommand);
}

void processRsCmd(char *input) {
    char* cmd;

    // cmd name
    char* firstSpace = strchr(input, ' ');
    int index = firstSpace - input;
    cmd = malloc(index + 1);
    memset(cmd, 0, index + 1);
    strncpy(cmd, input, index);
    if (Verbose) {
        PJ_LOG(5, (THIS_FILE,"processRsCmd, input is |%s|\n", input));
        fflush(stdout);
    }
    // rest of command
    char* roc = input + index + 1;
    //if (Verbose) printf("process cmd |%s| roc |%s|\n", cmd, roc);
    if (strcmp("users", cmd) == 0) { // got users list
        processUsersList(roc);
    } else if (strcmp("cReq", cmd) == 0) { // req for comm from another user
        processConnRequest(roc);
    } else if (strcmp("cReply", cmd) == 0) { // reply to my cReq
        processConnReply(roc);
    } else {
        if (Verbose) printf("Unknown rs cmd: %s", input);
    }
}

void *rsIncomingLoop(void *ptr) {
    //char buf[BUFFER_SIZE];
    // register thread with pjlib?
    pj_status_t rc;
    pj_thread_desc desc;
    pj_bzero(desc, sizeof (desc));
    pj_thread_t *this_thread;
    rc = pj_thread_register("thrIn", desc, &this_thread);
    char * buf = malloc(BUFFER_SIZE);
    int charsRead;
    //if (Verbose) printf("Begin rs read loop\n");
    while (1) {
        charsRead = readRsCommand(buf, BUFFER_SIZE);
        if (charsRead > 0) {
            if (Verbose) {
                PJ_LOG(4,(THIS_FILE,"Incoming rs cmd: %s\n", buf));
            }
            processRsCmd(buf);
        } else {
            printf("Incoming socket broken\n");
            break;
        }
    }
    pthread_exit(NULL);
}

/*
 * Initialize module for user.  Establishes connection with RS and starts
 * thread to read and process messages from RS.
 * 
 */
void p2p_init(char* MyUserName, int service, char* rsIp, char* rsPort, struct p2p_callbacks *callbacks, struct P2pConnectionOptions *p2pOptions) {
    PJ_LOG(5, (THIS_FILE, "Entering p2p_init\n"));
    if (p2pOptions != NULL) internal_init(p2pOptions, &Globals.ice_cfg);
    // check existence of callbacks
    if (callbacks->onIncomingMessage == NULL ||
            callbacks->onNewConnection == NULL ||
            callbacks->onNewConnectionRequest == NULL) {
        PJ_LOG(1, (THIS_FILE, "p2p_init: Null callback function\n"));
        return;
    }
    Globals.callbacks = *callbacks;

    Globals.opt = *p2pOptions;
    Globals.service = service;
    /* Must create pool factory, where memory allocations come from */
    PJ_LOG(5, (THIS_FILE, "init cp\n"));
    pj_caching_pool_init(&Globals.cp, NULL, 0);
    PJ_LOG(5, (THIS_FILE, "create pool\n"));

    /* Create application memory pool */
    Globals.pool = pj_pool_create(&Globals.cp.factory, "icedemo",
            512, 512, NULL);

    PJ_LOG(5, (THIS_FILE, "About to init rs\n"));
    rsInit(rsIp, rsPort);
    rsRegisterSelf(MyUserName, service);
    PJ_LOG(5, (THIS_FILE, "...initted and registered.\n"));

    //initUiApi();

    /* Thread to read messages from RS */
    pthread_t rsReadThread;
    int rsReadThreadRet;
    rsReadThreadRet = pthread_create(&rsReadThread, NULL, rsIncomingLoop, (void*) NULL);

}