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
#include <stdio.h>
#include <deque>
#include <boost/random.hpp>
#include "Connection.hpp"


namespace PseudoTcp {

    static int Verbose = 0;

    int Connection::incomingMessage(Message msg) {
        Message *newMsg = new Message(msg);
        if (Verbose) std::cerr << "Connection " << id << " incoming message " << msg.toString() << std::endl;
        msgHandler->IncomingMessage(newMsg);
        return 1;
    }

    int Connection::sendMessage(Message *msg) {
        if (Verbose) std::cerr << "Connection " << this->id << " queueing message for Connection " << otherHalf->GetId() << std::endl;
        boost::mutex::scoped_lock lock(messagesMutex);
        messagesToSend.push_back(*msg);
        if (Verbose) std::cerr << "Connection " << this->id << " queued message for Connection " << otherHalf->GetId() << std::endl;
        return 1;
    }

    void Connection::SetMsgHandler(IMsgHandler* msgHandler) {
        this->msgHandler = msgHandler;
    }

    IMsgHandler* Connection::GetMsgHandler() const {
        return msgHandler;
    };

    void Connection::startSendingThread() {
        assert(!sendingThread);
        sendingThread = boost::shared_ptr<boost::thread > (new boost::thread(boost::bind(&Connection::sendMessages, this)));
        if (Verbose) std::cerr << " Starting sendingThread: " << sendingThread.get()->get_id() << std::endl;
    }

    void Connection::sendMessages() {
        boost::mt19937 rng;
        boost::uniform_int<> range(0, 100);
        boost::variate_generator<boost::mt19937&, boost::uniform_int<> > gen(rng, range);
        while (!stopThread) {
            //if (Verbose) std::cerr << "Send Msg"
            boost::this_thread::sleep(boost::posix_time::milliseconds(gen()));
            Message m;
            bool messageToSend = false;
            {
                boost::mutex::scoped_lock lock(messagesMutex);
                if (!messagesToSend.empty()) {
                    m = messagesToSend.front();
                    messagesToSend.pop_front();
                    messageToSend = true;
                }
            }
            if (messageToSend && otherHalf != NULL) {
                if (Verbose) std::cerr << std::endl << "***Connection " << this->id << " sending message " << m << " to Connection " << otherHalf->GetId() << std::endl;
                if (losePacket(&m)) {
                    Logger::EventLogger::addEventG(Logger::Important, this, " Message Lost: " + m.toString());
                } else {
                    Logger::EventLogger::addEventG(Logger::Important, this, " Message sent: " + m.toString());
                    otherHalf->incomingMessage(m);
                    if (Verbose) std::cerr << "Connection " << this->id << " sent message to Connection " << otherHalf->GetId() << std::endl;
                }
                packetNumber++;
            }
        }
    }
}
