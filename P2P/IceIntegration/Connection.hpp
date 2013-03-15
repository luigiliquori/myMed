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
 * File:   Connection.hpp
 * Author: user
 *
 * Created on June 28, 2011, 10:11 PM
 */

#ifndef CONNECTION_HPP
#define	CONNECTION_HPP

#include <set>
#include <deque>
#include <boost/thread.hpp>
#include "IMsgHandler.hpp"
#include "EventLogger.hpp"
#include "PseudoTcpUtil.hpp"
#include "IConnection.hpp"



namespace PseudoTcp {

    /// A Connection is what actually sends (or simulates sending of) a Message.

    /**
     *  A Connection is really a half-connection; it has a pointer to it's other half
     *
     *  When 'send' is called, it forwards the message to other half.
     *  When receives incomingMessage from other half, forwards to Message handler.
     *
     *  Generally a PseudoTcpTx object will call 'send', while a
     *  PseudoTcpRx object will handle incoming messages.
     *
     *  The 'send' part can simulate network problems by losing, delaying, or
     *  duplicating messages.
     *
     * @param id
     *   An id for the connection, can be useful in debugging.
     */
    class Connection : Logger::IEventLogger, public IConnection {
    public:

        Connection(int id) :
        id(id),
        otherHalf(NULL),
        msgHandler(NULL),
        packetNumber(0),
        messagesToSend(),
        messagesMutex(),
        stopThread(0) {
            startSendingThread();
        }

        Connection(int id, IMsgHandler *msgHandler) :
        id(id),
        otherHalf(NULL),
        msgHandler(msgHandler),
        packetNumber(0),
        messagesToSend(),
        messagesMutex(),
        stopThread(0) {
            startSendingThread();
        }

        ~Connection() {
            if (sendingThread) {
                stopThread = true;
                sendingThread.get()->join();
            }
        }



        // accessors

        /// Property Id is just for debugging purposes

        void SetId(int id) {
            this->id = id;
        }

        /// Property Id is just for debugging purposes

        virtual int GetId() const {
            return id;
        }

        /// Property OtherHalf points to the other Connection object

        void SetOtherHalf(Connection* otherHalf) {
            this->otherHalf = otherHalf;
        }

        /// Property OtherHalf points to the other Connection object

        Connection* GetOtherHalf() const {
            return otherHalf;
        };

        /// Property MsgHandler tells who to forward incoming messages to.
        void SetMsgHandler(IMsgHandler* msgHandler);

        /// Property MsgHandler tells who to forward incoming messages to.
        IMsgHandler* GetMsgHandler() const;


        /// Send message to other half of connection
        int sendMessage(Message *msg);

        /// a message arrives from other half of connection, notify MsgHandler
        int incomingMessage(Message msg);

        /// IEventLogger function

        std::string logString() {
            std::stringstream ss;
            ss << "(pn: " << packetNumber << ")(";
            for (unsigned int i = 0; i < messagesToSend.size(); i++) {
                ss << messagesToSend.at(i).GetNumber() << ",";
            }
            ss << ")";
            return ss.str();
        }

        /// IEventLogger function

        std::string GetLogName() {
            std::stringstream ss;
            ss << "Conn" << id;
            return ss.str();
            //            return "Conn" + id;
        }



        /// For simulating lost packets.

        /**
         * If returns true, the message is not sent.  Base class default always
         * return false, i.e. no simulated losses.
         * @param msg
         *   The Message which would be sent, for the possibility that the loss
         * could depend on the Message number or content.
         * @return 
         */
        virtual bool losePacket(Message *msg) {
            return false;
        }

        /// A static function for connection two Connection objects to each other

        static void connectConnectionPair(Connection* c1, Connection* c2) {
            c1->otherHalf = c2;
            c2->otherHalf = c1;
        }

    protected:

        int id; ///< Property Id
        Connection *otherHalf; ///< Property OtherHalf
        IMsgHandler *msgHandler; ///< Property MsgHandler
        int packetNumber; ///< Which packet number is about to be sent.

        std::deque<Message> messagesToSend; ///< Shared buffer for sending thread

        // for threads
        boost::shared_ptr<boost::thread> sendingThread;
        boost::mutex messagesMutex;
        volatile bool stopThread;
        void startSendingThread();
        void sendMessages();

    };
}



#endif	/* CONNECTION_HPP */

