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
#include <stdlib.h>
#include <iostream>
#include <iterator>
#include "PseudoTcpRx.hpp"
#include "EventLogger.hpp"

namespace PseudoTcp {

    static int Verbose = 0;

    int PseudoTcpRx::incomingMessage(Message *msg) {
        Logger::EventLogger::addEventG(Logger::Important, this, "Received msg " + msg->toString());
        if (Verbose) std::cerr << std::endl << *this << ">>>>>>>>>>>>>>>>>>>> RX " << this << " incomingMessage" << msg->toString() <<  std::endl;
        //if (Verbose) std::cerr << "Rx " << this << " incoming msg " << msg->toString() << std::endl;
        if (Verbose) std::cerr << "   buffer is " << this->msgBuffer << std::endl;
        //std::cerr << " let's try the payload size: " << msg->GetByteArray().size();
        //if (Verbose) printf("Message is %s\n", msg->GetByteArray());
        //if (Verbose) printf("testmode is %d, next is %d\n", testMode, nextMsgIndex);
        //Logger::EventLogger::addEventG((Logger::IEventLogger*)this, "Message Arrives");
        if (testMode) {
            messagesReceivedLog[nextMsgIndex++] = *msg;
        }
        // add msg to msgBuffer
        if (Verbose) std::cerr << "buf before " << msgBuffer << std::endl;
        try {
            //std::cerr << "rx incMsg: " << msg->toString() << std::endl;
            msgBuffer.put(msg, msg->GetNumber());
        } catch (int error) { // underflow should be ignored; could just be duplicate packet
            if (Verbose) std::cerr << " error during put" << std::endl;
            if (error == msgBuffer.MovingBuffer_IndexOverflow)
                if (Verbose) std::cerr << "Overflow error during msgBuffer.put() of msgnumber " << msg->GetNumber() << std::endl;
            if (Verbose && error == msgBuffer.MovingBuffer_IndexUnderflow)
                std::cerr << "Underflow error during msgBuffer.put() of msgnumber " << msg->GetNumber() << std::endl;
            if (Verbose) std::cerr << "buf after error " << msgBuffer << std::endl;
        }
        //  if now have more contiguous messages, send them to availableMessages
        if (Verbose) {
            std::cerr << "buf after put " << msgBuffer << std::endl;
            MsgQueStr(std::cerr, availableMessages); std::cerr << std::endl;
        }
        Message *available;
        while ((available = msgBuffer.popIfNotEmpty()) != NULL) {
            if (Verbose) {
                std::cerr << " push message " << available << " is " << available->toString() << std::endl;
                MsgQueStr(std::cerr, availableMessages);  std::cerr << std::endl;
            }
            //std::cerr << " making avail, let's try the payload size: " << available->GetByteArray().size() << std::endl;
            availableMessages.push(available);
            //std::cerr << " message " << available << " is " << available->toString() << " ";
            //std::cerr << " from vector, let's try the payload size: " << availableMessages.back()->GetByteArray().size() << std::endl;
        }
        if (Verbose) std::cerr << " No more pops";
        if (Verbose) std::cerr << "buf after pops " << msgBuffer << std::endl;
        if (msg->IsReqAck()) {
            if (Verbose) std::cerr << "it's an AckReq" << std::endl;
            sendAck(msg);
        }
        //Logger::EventLogger::addEventG((Logger::IEventLogger*)this, "Message Arrived");
        if (Verbose) std::cerr << "buf after ack sent " << msgBuffer << std::endl;
        if (Verbose) std::cerr << *this << "<<<<<<<<<<<<<<<<<<<< RX " << this << " end incomingMessage" << msg->toString() <<  std::endl;
        Logger::EventLogger::addEventG(Logger::Important, this, "msg processed");
        //if (Verbose) std::cerr << std::endl << "<<<<<<<<<<<<<<<<<<<<" << std::endl;
        //Logger::EventLogger::glob()->addAfter(this);
        return 1;  //todo: put something meaningful here, or fn should return void
    }

    void PseudoTcpRx::setId(int id) {
        this->id = id;
    }
    int PseudoTcpRx::getId() const {
        return id;
    }

    void PseudoTcpRx::sendAck(Message *msg) {
        if (Verbose) std::cerr << "sending Ack, buff " << msgBuffer << std::endl;
        Message *ack = new Message();
        ack->SetAck(1);
        ack->SetAckNumber(msg->GetNumber());
        ack->SetAckBase(msgBuffer.getFirstIndex());
        std::bitset<MSG_WIN> ackBits;
        for (int i = ack->GetAckBase(); i < ack->GetAckBase() + messageBufferSize; i++) {
            Message *message;
            try {
                message = msgBuffer.get(i);
            } catch (int error) {
                printf("Error in PseudoTcpRx::sendAck %d\n", error);
                exit(33);
            }
            ackBits[i - ack->GetAckBase()] = (message != NULL);
        }
        ack->SetMsgsReceived(ackBits);
        if (Verbose) std::cout << ack->toString() << std::endl;
        tx->send(ack);
        if (Verbose) std::cerr << "done sending Ack, buff " << msgBuffer << std::endl;
        //conn->sendMessage(ack);
        //delete ack;  // todo: where do we delete this?
    }

    Message *PseudoTcpRx::getNextMessage() {
        if (Verbose) std::cerr << " getting next msg, buf " << msgBuffer << std::endl;
        if (availableMessages.empty()) {
            //std::cerr << " there isn't one" << std::endl;
            if (Verbose) std::cerr << " done getting next msg, buf " << msgBuffer << std::endl;
            return NULL;
        } else {
            Message *msg = availableMessages.front();
            //std::cerr << " message " << msg << " is " << msg->toString() << " ";
            //std::cerr << " again, let's try the payload size: " << msg->GetByteArray().size();
            availableMessages.pop();
            //std::cerr << " after pop, let's try the payload size: " << msg->GetByteArray().size();
            if (Verbose) std::cerr << " done getting next msg, buf " << msgBuffer << std::endl;
            return msg;
        }
    }

    void PseudoTcpRx::printState(std::ostream& o) {
    }


    // accessors

    Message* PseudoTcpRx::getMessagesReceivedLog() {
        return messagesReceivedLog;
    }

    void PseudoTcpRx::setNextMsgIndex(int nextMsgIndex) {
        this->nextMsgIndex = nextMsgIndex;
    }

    int PseudoTcpRx::getNextMsgIndex() const {
        return nextMsgIndex;
    }

    void PseudoTcpRx::setTestMode(int testMode) {
        this->testMode = testMode;
    }

    int PseudoTcpRx::getTestMode() const {
        return testMode;
    }



}

