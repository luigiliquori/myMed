/*
 * Copyright 2012 POLITO 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/
/* 
 * File:   p2pConnApi.h
 * Author: user
 *
 * Created on May 10, 2011, 5:54 PM
 */

#ifndef P2PCONNAPI_H
#define	P2PCONNAPI_H

#include <pjnath.h>
#include <pjlib.h>

//#include "peer_connection.h"

#ifdef	__cplusplus
extern "C" {
#endif

#define ContactInfoLen 1000     // size of buffer for contact information
#define MAX_NAME_LENGTH 60
    
    enum P2pConnectionStatus {
        P2pConnecting,
        P2pSuccess,
        P2pFailure
    };

    //typedef void P2pRxCallback(pj_ice_strans *, unsigned, void *, pj_size_t, const pj_sockaddr_t *, unsigned);

    /* Command line options are stored here */
    struct P2pConnectionOptions {
        unsigned comp_cnt;
        pj_str_t ns;
        int max_host;
        pj_bool_t regular;
        pj_str_t stun_srv;
        pj_str_t turn_srv;
        pj_bool_t turn_tcp;
        pj_str_t turn_username;
        pj_str_t turn_password;
        pj_bool_t turn_fingerprint;
        const char *log_file;
    };

    /* Variables to store parsed remote ICE info */
    struct P2pRemoteInfo {
        char ufrag[80];
        char pwd[80];
        unsigned comp_cnt;
        pj_sockaddr def_addr[PJ_ICE_MAX_COMP];
        unsigned cand_cnt;
        pj_ice_sess_cand cand[PJ_ICE_ST_MAX_CAND];
    };

    struct P2pConnection {
        enum P2pConnectionStatus status;
        int id;                         // for connecting requests with replies
        char otherUser[MAX_NAME_LENGTH];
        struct P2pConnectionOptions *opt;
        pj_ice_strans *icest;
        pj_ice_strans_cfg ice_cfg;
        struct P2pRemoteInfo remInfo;
        char contactInfo[ContactInfoLen];
        char remoteInfoString[ContactInfoLen];
};

    typedef struct p2p_callbacks {
        void (* onNewConnection)(struct P2pConnection *conn);
        int (* onNewConnectionRequest) (struct P2pConnection *conn);
        void (* onIncomingMessage)(struct P2pConnection *conn, char* msg, unsigned size);
        void (* onNewUsers)(char *rsUserString);
    } p2p_callbacks;

    void p2p_init(char* MyUserName, int service, char* rsIp, char* rsPort, struct p2p_callbacks *callbacks, struct P2pConnectionOptions *p2pOptions);

    void getConnection(char* otherUser, int service);

    void p2p_send_string(struct P2pConnection *conn, char* message);

    // binary version
    void p2p_send_bytes(struct P2pConnection *conn, char*, int length);


#ifdef	__cplusplus
}
#endif

#endif	/* P2PCONNAPI_H */

