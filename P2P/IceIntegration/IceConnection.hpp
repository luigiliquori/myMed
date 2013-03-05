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
 * File:   IceConnection.hpp
 * Author: user
 *
 * Created on August 26, 2011, 12:22 PM
 */

#ifndef ICECONNECTION_HPP
#define	ICECONNECTION_HPP

#include "IConnection.hpp"
#include "IMsgHandler.hpp"
#include "Message.hpp"
#include "p2pConnApi.h"

class IceConnection : public PseudoTcp::IConnection {
public:
    IceConnection(P2pConnection *conn);

    virtual void SetMsgHandler(PseudoTcp::IMsgHandler* msgHandler);

    virtual PseudoTcp::IMsgHandler* GetMsgHandler() const;

    virtual int sendMessage(PseudoTcp::Message *msg);

    virtual int incomingMessage(PseudoTcp::Message msg);

    virtual int GetId() const;

    virtual void registerThread();

    // not required by IConnection, but needed for conversion from low level
    // to be deserialized into message
    int incomingRawMessage(char *msg, int length);

private:
    PseudoTcp::IMsgHandler *msgHandler;
    P2pConnection *conn;

};

#endif	/* ICECONNECTION_HPP */

