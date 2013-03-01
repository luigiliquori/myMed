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


#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <ctype.h>

#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>

#include <netdb.h>

// forward defs
//static void usParseUsers(char *input, struct PeerConnectionList* pcl);
//static int inputOk(char* input);

#define BUFFER_SIZE 4096
#define CMD_SIZE 2000

static int Verbose = 1;
static int RendesvousServerSocket = -1;

// open socket for UserServer comm

int rsInit(char* ip, char* port) {
    //struct sockaddr_in serv;
    int sock;

    if ((sock = socket(AF_INET, SOCK_STREAM, 0)) == -1) {
        perror("socket");
        exit(-1);
    }

    /* get address for UserServer */
    struct addrinfo hints;
    struct sockaddr_storage userServer_addr;
    struct addrinfo* res = NULL;
    int addr_len;

    memset(&hints, 0, sizeof (struct addrinfo));
    hints.ai_family = AF_UNSPEC;
    hints.ai_socktype = SOCK_STREAM;
    hints.ai_protocol = IPPROTO_TCP;
    hints.ai_flags = 0;

    if (getaddrinfo(ip, port, &hints, &res) != 0) {
        perror("getaddrinfo");
        exit(EXIT_FAILURE);
    }

    memcpy(&userServer_addr, res->ai_addr, res->ai_addrlen);
    addr_len = res->ai_addrlen;
    freeaddrinfo(res);

    if ((connect(sock, (struct sockaddr *) &userServer_addr, addr_len)) == -1) {
        perror("connect");
        exit(-1);
    }

    RendesvousServerSocket = sock;
    if (Verbose) printf("UserServerSocket: %d\n", RendesvousServerSocket);
    return 0;
}


void sendCommand(char* cmd) {
    if (RendesvousServerSocket == -1) {
        perror("UserServer not initialized");
        return;
    }
    int userServerSock = RendesvousServerSocket;
    if (Verbose) printf("sending to rs: |%s|\n",cmd);
    if (send(userServerSock, cmd, strlen(cmd), 0) == -1) {
        fprintf(stderr, "Failed to send data to server.\n");
    }
}


// Open connection to UserServer and register self

int rsRegisterSelf(char* userName, int service) {

    char cmd[CMD_SIZE];
    //ssize_t nb = -1;

    if (RendesvousServerSocket == -1) {
        perror("UserServer not initialized");
        return 0;
    }
    memset(cmd, 0, sizeof (cmd));
    sprintf(cmd, "reg %s %d\n", userName, service);
    if (Verbose) printf("cmd: |%s|\n", cmd);
    sendCommand(cmd);
    return 0;
}


// request users from UserServer

/*  Commands to Server */
// request users from UserServer

void rsAskUsers() {
    sendCommand("getUsers\n");
}

void rsConnReq(char* user, int service, int connectionId, char* myContactInfo) {
    char cmd[CMD_SIZE];
    memset(cmd, 0, sizeof (cmd));
    sprintf(cmd, "cReq %s %d %d %s\n", user, service, connectionId, myContactInfo);
    //printf("\n^^^^^^^contact info: %s", myContactInfo);
    //printf("\n^^^^^^^cmd: %s", cmd);

    sendCommand(cmd);
}

void rsConnReply(char* connId, char* myContactInfo) {
    char cmd[CMD_SIZE];
    memset(cmd, 0, sizeof (cmd));
    sprintf(cmd, "cReply %s %s\n", connId, myContactInfo);
    sendCommand(cmd);
}

// a version which uses and int as the connection id
void rsConnReplyInt(int connId, char* myContactInfo) {
    char cmd[CMD_SIZE];
    memset(cmd, 0, sizeof (cmd));
    sprintf(cmd, "cReply %d %s\n", connId, myContactInfo);
    sendCommand(cmd);
}

/* receive commands from server */

int readRsCommand(char* buf, int bufSize) {
    ssize_t nb = -1;

    if ((nb = recv(RendesvousServerSocket, buf, bufSize, 0)) > 0) {
        buf[nb] = '\0';
        if (Verbose && nb>0) fprintf(stdout, "Received %d bytes. buf: |%s|\n", (int) nb, buf);
    }
    return nb;
}
// for now, just check that all chars are printable.  Assume
// ends with '\0'

/*
static int inputOk(char* input) {
    if (input == NULL) return 0;
    int i;
    for (i = 0; input[i] != '\0'; i++) {
        if (!isprint(input[i])) {
            if (Verbose) {
                printf("input not OK\n");
                fflush(stdout);
            }
            return 0;
        }
    }
    return 1;
}
*/

/*
static void usParseUsers(char *input, struct PeerConnectionList* pcl) {
    int verbose = Verbose;
    if (verbose) {
        printf("in usParseUsers...|%s|\n\n", input);
        fflush(stdout);
    }
    char *token;
    char *delimiters = ";";
    char *savePtr;
    token = strtok_r(input, delimiters, &savePtr);
    if (verbose) {
        printf("clear list...");
        fflush(stdout);
    }
    clearPeerConnectionList(pcl);
    if (verbose) {
        printf("after clear...");
        fflush(stdout);
    }
    while (token != NULL) {
        if (verbose) {
            printf("\n token: %s", token);
            fflush(stdout);
        }
        // so, a recursive call won't work?
        char* name;
        char* ip;
        char* port;
        char *innerDelim = " ";
        char *innerSavePtr;
        name = strtok_r(token, innerDelim, &innerSavePtr);
        ip = strtok_r(NULL, innerDelim, &innerSavePtr);
        port = strtok_r(NULL, innerDelim, &innerSavePtr);
        if (name == NULL || ip == NULL || port == NULL) continue;
        if (verbose) {
            printf("before update...");
            if (name == NULL) printf(".null name");
            else printf(".name %s.", name);
            if (ip == NULL) printf(".null ip");
            else printf(".name %s.", name);
            if (port == NULL) printf(".null port");
            else printf(".name %s.", name);
            fflush(stdout);
        }
        updatePeerConnection(pcl, name, ip, port, -1, -1);

        token = strtok_r(NULL, delimiters, &savePtr);
    }

    // print connections
    if (verbose) {
        fprintPeerConnections(stdout,pcl);
        fflush(stdout);
    }
}
 */

