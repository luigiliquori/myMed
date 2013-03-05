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
 * File:   PseudoTcpRx.hpp
 * Author: user
 *
 * Created on June 24, 2011, 3:24 PM
 */

#ifndef PSEUDOTCPRX_HPP
#define	PSEUDOTCPRX_HPP

#include "MovingBuffer.hpp"
#include "PseudoTcpUtil.hpp"
#include "PseudoTcpTx.hpp"
#include "EventLogger.hpp"

#include <iostream>
#include <sstream>
#include <queue>
/**
 * @file
 * PseudoTcpRx encapsulates the receiving half of the PseudoTcp protocol
 */
#define MaxMessageLog 1000
namespace PseudoTcp {

    /// encapsulates RX part of protocol
    /**
     * The receiving end is responsable for:
     *  -# Ordering incoming messages
     *  -# Making incoming messages available to higher level
     *  -# Sending acks when needed
     */
    class PseudoTcpRx : Logger::IEventLogger {
    public:

        PseudoTcpRx(PseudoTcpTx *tx, int msgBufferSize) :
        id(tx->getId()),
        messageBufferSize(msgBufferSize),
        testMode(1),
        nextMsgIndex(0),
        tx(tx),
        availableMessages(),
        msgBuffer(messageBufferSize) {
        };

        /**
         *  Callback for incoming message, called by Connection.
         * @param msg
         *   The message itself.
         * @return 
         *   Number of bytes received.
         */
        int incomingMessage(Message *msg);

        /**
         * Send ack for Message.
         * @param msg
         *   The Message to acknowledge.
         */
        void sendAck(Message *msg); // todo: I think this can be private

        /// Returns next message in order.  If none available, returns NULL;
        Message *getNextMessage();

        Message* getMessagesReceivedLog();

        void setNextMsgIndex(int nextMsgIndex);
        int getNextMsgIndex() const;

        void setTestMode(int testMode);
        int getTestMode() const;

        void printState(std::ostream& o);

        /// IEventLogger function
        std::string logString() {
            std::stringstream ss;
            ss << *this;
            return ss.str();
        }

        /// IEventLogger function
        std::string GetLogName() {
            std::stringstream ss;
            ss << "Rx" << id;
            return ss.str();
        }

        void setId(int id);
        int getId() const;

    private:
        int id;         ///< for debugging
        int messageBufferSize;
        Message messagesReceivedLog[MaxMessageLog];
        int testMode;
        int nextMsgIndex;
        PseudoTcpTx *tx;

        // now, the data structures for handling the arriving messages

        /// availableMessages is a simple queue which holds the
        /// messages which can be consumed. They are in order and without
        /// 'holes'.
        std::queue<Message*> availableMessages;

        /// vector of length MSG_WIN for buffering messages which have
        /// been received but are not contiguous.
        //std::vector<Message*> msgBuffer;
        utils::MovingBuffer<Message> msgBuffer;

        friend std::ostream & operator<<(std::ostream& o, PseudoTcpRx const& rx) {
            o << "[[RX" << rx.id << " ";
            MsgQueStr(o, rx.availableMessages);
            return o << rx.msgBuffer << " ]]";
        }

    };
}


#endif	/* PSEUDOTCPRX_HPP */

