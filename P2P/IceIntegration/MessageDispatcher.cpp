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

#include "MessageDispatcher.hpp"

namespace PseudoTcp {

    static bool Verbose = 0;

    MessageDispatcher::MessageDispatcher(IConnection *conn, int windowSize) :
    tx(conn, windowSize),
    rx(&tx, windowSize),
    id(conn->GetId()) {
        conn->SetMsgHandler(this);
    }

    void MessageDispatcher::SetId(int id) {
        this->id = id;
    }

    int MessageDispatcher::GetId() const {
        return id;
    }

    int MessageDispatcher::send(Message *msg) {
        int returnVal = tx.send(msg);
        if (Verbose) std::cerr << "<<<<<" << *this << " after forwarding send to tx " << tx << " " << rx << std::endl;
        return returnVal;
    }


    void MessageDispatcher::IncomingMessage(Message *msg) {
        if (Verbose) {
            std::cerr << *this << " >>>>>> incoming message md " << this << std::endl;
            std::cerr << " msg: " << msg->toString()  << std::endl;
            std::cerr << " rx " << this->rx << std::endl;
        }
        if (msg->IsAck()) {
            if (Verbose) std::cerr << " it's an ack rx: " << rx << std::endl;
            tx.incomingAck(msg);
            if (Verbose) std::cerr << " after ack rx: " << rx << std::endl;
        }
        if (msg->hasPayload()) {
            if (Verbose) std::cerr << " it has payload rx: " << rx << std::endl;
            rx.incomingMessage(msg);
            if (Verbose) std::cerr << " after payload rx: " << rx << std::endl;
        }
        if (Verbose) std::cerr << "MD <<<<< message processed" << std::endl;
    }

}
