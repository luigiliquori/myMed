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

#include <iosfwd>
#include <sstream>

#include "IceConnection.hpp"
#include "Message.hpp"

IceConnection::IceConnection(P2pConnection *conn) :
conn(conn) {

}

int IceConnection::GetId() const {
    return 1;
}

PseudoTcp::IMsgHandler *IceConnection::GetMsgHandler() const {
    return msgHandler;
}

void IceConnection::SetMsgHandler(PseudoTcp::IMsgHandler* msgHandler) {
    this->msgHandler = msgHandler;
}

int IceConnection::incomingMessage(PseudoTcp::Message msg) {
    //std::cerr << "incoming msg " << std::endl;
    msgHandler->IncomingMessage(&msg);
    return msg.GetByteArray().size();
}

int IceConnection::incomingRawMessage(char *bytes, int length) {
    if (0) {
        std::cerr << "incoming raw message ";
        std::string payload(bytes, length);
        std::cerr << "  " << payload << std::endl;
    }
    std::string inStr(bytes, length);
    std::stringstream ss;
    ss << inStr;
    //std::cerr << "ss is " << ss << std::endl;
    PseudoTcp::Message *msg = new PseudoTcp::Message();
    boost::archive::text_iarchive ia(ss);
    ia >> msg;
    //std::cerr << "msg is " << msg->toString() << std::endl;
    if (msgHandler == NULL) std::cerr << "mhnull" << std::endl;
    if (msg->GetByteArray().size() > 0) {
        std::cerr << "payload is ";
        for (unsigned int i = 0; i < msg->GetByteArray().size(); i++) {
            std::cerr << msg->GetByteArray()[i];
        }
        std::cerr << std::endl;
    }
    msgHandler->IncomingMessage(msg);
    return length;
}

int IceConnection::sendMessage(PseudoTcp::Message* msg) {
    //todo: have to allocate string here; when and where to delete it?
    // are we sure we have to alloc it?
    //std::cerr << "sending msg " << *msg << std::endl;
    std::stringstream ss;
    boost::archive::text_oarchive oa(ss);
    oa << msg;
    int length = ss.str().length();
    char *foo = (char *) malloc(length + 1);
    strncpy(foo, ss.str().c_str(), length);
    foo[length] = '\0';
    p2p_send_bytes(conn, foo, length + 1);
    //std::cerr << "sent" << std::endl;
    return length;

    //    int len = msg->GetByteArray().size();
    //    char *foo = malloc(len + 1);
    //    strncpy(foo, msg->GetByteArray().data(), len);
    //    foo[len] = '\0';
    //    p2p_send_string(conn, msg); // todo: msg to string
}

void IceConnection::registerThread() {
    pj_status_t rc;
    pj_thread_desc desc;
    pj_bzero(desc, sizeof (desc));
    pj_thread_t *this_thread;
    rc = pj_thread_register("thrIn", desc, &this_thread);
}

