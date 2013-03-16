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
 * File:   MessageDispatcher.hpp
 * Author: user
 *
 * Created on July 3, 2011, 11:46 PM
 */

#ifndef MESSAGEDISPATCHER_HPP
#define	MESSAGEDISPATCHER_HPP

#include "PseudoTcpTx.hpp"
#include "PseudoTcpRx.hpp"
#include "IMsgHandler.hpp"

namespace PseudoTcp {

    /// Top level PseudoTcp object, which contains TX and TX parts
    /**
     * Has two primary purposes:
     *  -# Act as encapsulator and facade for PseudoTcpTx and PseudoTcpRx
     *  -# Handle incoming messages, sending them to RX (if payload)
     * or TX (if ack info) as needed.
     */
    class MessageDispatcher : public IMsgHandler {

    public:
        MessageDispatcher(IConnection *conn, int windowSize);

        /// IMsgHandler interface3
        void IncomingMessage(Message *msg);

        /// facade for Tx
        int send(Message *msg);

        /// facade for Tx
        bool allMessagesAcked() {
            return tx.allMessagesAcked();
        }

        // facade for Rx
        Message *getNextMessage() {
            return rx.getNextMessage();
        }
        void SetId(int id);
        int GetId() const;

        friend std::ostream & operator<<(std::ostream& o, MessageDispatcher const& md) {
            return o << "[[MD" << md.id << "]]";
        }


    private:
        PseudoTcpTx tx;
        PseudoTcpRx rx;
        int id;     ///< for debugging
        static const bool Verbose = 0;

    };

}

#endif	/* MESSAGEDISPATCHER_HPP */

