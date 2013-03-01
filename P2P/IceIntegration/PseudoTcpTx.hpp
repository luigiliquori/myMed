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
 * File:   PseudoTcpTx.hpp
 * Author: user
 *
 * Created on June 29, 2011, 9:52 AM
 */

#ifndef PSEUDOTCPTX_HPP
#define	PSEUDOTCPTX_HPP

#include <queue>

// for threading
#include <boost/thread.hpp>

#include "IConnection.hpp"
#include "PseudoTcpUtil.hpp"

#include "IEventLogger.hpp"

/// The PseudoTcp namespace encapsulates the primary high-level objects involved in implementing the protocol.
namespace PseudoTcp {

    /// Encapsulates the sending half of the PseudoTcp protocol

    /**
     * The TX part is responsable for:
     *  -# Sending Messages, assigning Message number
     *  -# Keeping track of which Messages have been acked
     *  -# Keeping track of overdue acks
     *  -# Resending Messages when necessary
     */
    class PseudoTcpTx : Logger::IEventLogger {
    public:

        PseudoTcpTx(IConnection *conn, int msgBufferSize)
        : id(conn->GetId()),
        msgBufferSize(msgBufferSize),
        conn(conn),
        nextMessageNumber(0),
        acksReceived(0),
        base(0),
        stopThread(0) {
            startCheckAckThread();
        };

        ~PseudoTcpTx() {
            if (checkAckThread) {
                stopThread = true;
                checkAckThread.get()->join();
            }
        }

        /**
         * Send a message over the Connection.
         * @param msg
         *   The message to send.
         * @return
         *   1 if can be sent immediately, 2 if queued, -1 if buffer full, -2 if too big.
         */
        int send(Message *msg);

        /**
         * Update internal state based on information in ack, resending
         * Messages and ack requests when necessary.
         * @param msg
         */
        void incomingAck(Message *msg);

        /// this is just for debugging
        int getAcksReceived() const;

        /**
         *   See if all messages have been send and acknowledged.
         * @return
         *   true if they have.
         */
        bool allMessagesAcked() {
            boost::mutex::scoped_lock(threadMutex);
            return unAckedMsgs.empty();
        }

        void printState(std::ostream& o);

        /// A thread which checks every ACK_CHECKING_INTERVAL msec for overdue acks
        void startCheckAckThread();

        // IEventLogger

        std::string logString() {
            std::stringstream ss;
            ss << *this;
            return ss.str();
        }

        std::string GetLogName() {
            std::stringstream ss;
            ss << "Tx" << id;
            return ss.str();
        }

        void setId(int id);
        int getId() const;

    private:
        int id;             ///< for debugging
        int msgBufferSize;
        IConnection *conn;
        int nextMessageNumber;
        int acksReceived;
        int base;

        std::deque<Message*> unAckedMsgs;
        std::deque<Message*> msgBuff;
        std::deque<Message*> unAckedAckReqs;

        // for threads
        boost::shared_ptr<boost::thread> checkAckThread;
        volatile bool stopThread;
//        boost::mutex ackAcksMutex;
//        boost::mutex connMutex;
//        boost::mutex unackMsgsMutex;
        boost::mutex threadMutex;

        static const double NO_ACK_RESEND_TIMEOUT = 0.3;
        static const double ACK_CHECKING_INTERVAL = 1000; // in milliseconds

        void sendNow(Message *msg);
        /**
         *
         * @param msg
         *   The message to send.
         */
        void resend(Message *msg);

        void removeAckedMessages(Message* ack);

        bool acked(Message* ack, int msgNumber);

        void checkUnackedAcks();

        friend std::ostream & operator<<(std::ostream& o, PseudoTcpTx const& tx) {
            o << "[[TX" << tx.id << " nx:" << tx.nextMessageNumber << " b:" << tx.base;
            o << " unAM";
            MsgDeqStr(o, tx.unAckedMsgs);
            o << " unAAR";
            MsgDeqStr(o, tx.unAckedAckReqs);
            o << " buff";
            MsgDeqStr(o, tx.msgBuff);
            return o << "]]";
        }

    };
}


#endif	/* PSEUDOTCPTX_HPP */

