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
 * File:   IceWrapper.cpp
 * Author: user
 * 
 * Created on August 8, 2011, 10:08 PM
 */

#include <iterator>
#include <iostream>
#include <map>

#include "IceWrapper.hpp"
#include "p2pConnApi.h"
#include "rsComm.h"
#include "IceConnection.hpp"
#include "Message.hpp"

#define THIS_FILE   "IceWrapper.cpp"

bool IceWrapper::Verbose = false;
std::map<std::string, IceConnection *> IceWrapper::conns;
std::map<std::string, PseudoTcp::MessageDispatcher *> IceWrapper::mds;
int IceWrapper::nextId = 0;
bool IceWrapper::usersHaveChanged = false;
char IceWrapper::userString[4000];

IceWrapper::IceWrapper() :
cb(),
options(),
name() {
}

IceWrapper::IceWrapper(const IceWrapper& orig) {
}

IceWrapper::~IceWrapper() {
}

void IceWrapper::init(const char *userName, int service, const char *rsIp, const char *rsPort) {
    pj_log_set_level(6);
    cb.onIncomingMessage = IceWrapper::OnIncomingMessage;
    cb.onNewConnection = IceWrapper::OnNewConnection;
    cb.onNewConnectionRequest = IceWrapper::OnNewConnectionRequest;
    cb.onNewUsers = IceWrapper::OnNewUsers;
    //std::string rsIp("130.192.9.113");
    //std::string rsPort("3000");
    //std::string rsIp("127.0.0.1");
    //std::string rsPort("44456");
    char n[50];
    //char rp[40];
    //char rpt[6];
    strncpy(n, userName, 50);
    //strncpy(rp, rsIp.c_str(), 40);
    //strncpy(rpt, rsPort.c_str(), 6);
    char ip[30];
    char port[10];
    strncpy(ip, rsIp, 30);
    strncpy(port, rsPort, 10);
    initOptions();
    p2p_init(n, service, ip, port, &cb, &options);
}

void IceWrapper::initOptions() {
    options.comp_cnt = 1;
    options.max_host = -1;
    options.log_file = "";
    char nullstr[1];
    nullstr[0] = '\0';
    options.ns = pj_str(nullstr);
    std::string stunServer("mymed12.sophia.inria.fr:3478");
    char *ss = (char *) malloc(100);
    strncpy(ss, stunServer.c_str(), 100);
    options.stun_srv = pj_str(ss);
}

// todo: protect with a mutex

void IceWrapper::OnNewConnection(struct P2pConnection *conn) {
    if (IceWrapper::Verbose) std::cerr << "new connection to: " << conn->otherUser;
    IceConnection *ic = new IceConnection(conn);
    IceWrapper::conns[conn->otherUser] = ic;
    PseudoTcp::MessageDispatcher *md = new PseudoTcp::MessageDispatcher(ic, 8);
    md->SetId(nextId++);
    IceWrapper::mds[conn->otherUser] = md;
}

void IceWrapper::OnIncomingMessage(P2pConnection* conn, char* msg, unsigned size) {
    std::string m(msg, size);
    IceConnection *ic = getConnectionOfUser(conn->otherUser);
    if (ic != NULL) {
        ic->incomingRawMessage(msg, size);
    }
    if (IceWrapper::Verbose) {
        std::cerr << "Incoming msg";
        if (conn != NULL) std::cerr << " from " << conn->otherUser;
        std::cerr << ": " << m;
    }

}

int IceWrapper::OnNewConnectionRequest(P2pConnection* conn) {
    return 87;
}

void IceWrapper::OnNewUsers(char *rsUsersString) {
    strncpy(userString, rsUsersString, 4000);
    usersHaveChanged = true;
    std::cerr << "users: |" << rsUsersString << "|" << std::endl;
}

void IceWrapper::SetName(std::string name) {
    this->name = name;
}

std::string IceWrapper::GetName() const {
    return name;
}

void IceWrapper::getConn(std::string toUser, int service) {
    char user[100];
    strncpy(user, toUser.c_str(), 100);
    std::cerr << "getConn to " << toUser << " service " << service << std::endl;
    getConnection(user, service);
}

void IceWrapper::send(struct P2pConnection *conn, std::string msg) {
    if (conn == NULL) {
        std::cerr << "Trying to send over null connection" << std::endl;
    } else {
        char buf[msg.size()];
        strncpy(buf, msg.c_str(), msg.size() + 1);
        p2p_send_string(conn, buf);
    }
}

IceConnection *IceWrapper::getConnectionOfUser(std::string userName) {
    std::map<std::string, IceConnection *>::iterator iter = conns.find(userName);
    if (iter == conns.end()) return NULL;
    else return conns[userName];
}

PseudoTcp::MessageDispatcher *IceWrapper::getMdOfUser(std::string userName) {
    std::map<std::string, PseudoTcp::MessageDispatcher *>::iterator iter = mds.find(userName);
    if (iter == mds.end()) return NULL;
    else return mds[userName];
}

void IceWrapper::requestUsers() {
    rsAskUsers();
}

char *IceWrapper::getUsers() {
    usersHaveChanged = false;
    return userString;
}

// search all mds for a message.
const char *IceWrapper::getInput() {
    std::map<std::string, PseudoTcp::MessageDispatcher *>::iterator iter;
    for (iter = mds.begin(); iter != mds.end(); iter++) {
        PseudoTcp::MessageDispatcher *md = iter->second;
        PseudoTcp::Message *msg = md->getNextMessage();
        if (msg != NULL) {
            int len = msg->GetByteArray().size();
            if (len > 0) {
                //std::string acc;
                const char* s = reinterpret_cast<const char *>(&(msg->GetByteArray()[0]));
                char *input = (char *)malloc(len+1);
                strncpy(input, s, len);
                input[len] = '\0';
                std::cerr << "IceWrapper::getInput: " << input << std::endl;
                return input;
            }
        }
    }
    return NULL;
}

// search all mds for a message.
const char *IceWrapper::getInput(char *from) {
    std::map<std::string, PseudoTcp::MessageDispatcher *>::iterator iter;
    for (iter = mds.begin(); iter != mds.end(); iter++) {
        PseudoTcp::MessageDispatcher *md = iter->second;
        PseudoTcp::Message *msg = md->getNextMessage();
        if (msg != NULL) {
            int len = msg->GetByteArray().size();
            if (len > 0) {
                //std::string acc;
                const char* s = reinterpret_cast<const char *>(&(msg->GetByteArray()[0]));
                char *input = (char *)malloc(len+1);
                strncpy(input, s, len);
                input[len] = '\0';
                std::cerr << "IceWrapper::getInput: " << input << std::endl;
                strncpy(from, iter->first.c_str(), 200);
                return input;
            }
        }
    }
    return NULL;
}

//void IceWrapper::registerCallback(void (*jCallback)(char *from, char *msg)) {
//    this->javaCallback = jCallback;
//}

