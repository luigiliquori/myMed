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
 * File:   IConnection.hpp
 * Author: user
 *
 * Created on August 25, 2011, 2:28 PM
 */

#ifndef ICONNECTION_HPP
#define	ICONNECTION_HPP

#include "IMsgHandler.hpp"

namespace PseudoTcp {

    class IConnection {
    public:

        /// Property MsgHandler tells who to forward incoming messages to.
        virtual void SetMsgHandler(IMsgHandler* msgHandler) = 0;

        /// Property MsgHandler tells who to forward incoming messages to.
        virtual IMsgHandler* GetMsgHandler() const = 0;


        /// Send message to other half of connection
        virtual int sendMessage(Message *msg) = 0;

        /// a message arrives from other half of connection, notify MsgHandler
        virtual int incomingMessage(Message msg) = 0;

        virtual int GetId() const = 0;

        /// Horrible hack, made necessary by exigencies of pjnat
        virtual void registerThread() = 0;

    };
}
#endif	/* ICONNECTION_HPP */

