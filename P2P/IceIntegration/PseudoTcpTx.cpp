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
#include <stack>

#include "PseudoTcpTx.hpp"
#include "SimpleTimer.hpp"
#include "EventLogger.hpp"

namespace PseudoTcp {

    static int Verbose = 0;

    int PseudoTcpTx::send(Message *msg) {
        boost::mutex::scoped_lock(threadMutex);
        if (Verbose) std::cerr << std::endl << ">>>>>>>>>>>>>>>>>>>> TX " << *this << " send " << msg->toString() << std::endl;
        Logger::EventLogger::addEventG(Logger::Important, this, "Send message " + msg->toString());
        // If it's only an ack, just send it
        if (!msg->hasPayload()) {
            sendNow(msg);
        } else {
            if (nextMessageNumber - base <= msgBufferSize) {
                msg->SetNumber(nextMessageNumber++);
                if (Verbose) std::cerr << *this << " queueing msg " << msg->toString() << std::endl;
                if (msg->hasPayload()) {
                    unAckedMsgs.push_back(msg);
                }
                if (msg->IsReqAck()) {
                    unAckedAckReqs.push_back(msg);
                }
                sendNow(msg);
            } else {
                msgBuff.push_back(msg);
            }
            if (Verbose) std::cerr << " <<<<<<<<<<<<<<<<<<<< TX " << *this << " queued " << msg->toString() << std::endl;
        }
        Logger::EventLogger::addEventG(Logger::Important, this, "Sent message " + msg->toString());
        return 1;  //todo: put something meaningful here, or fn should return void
    }

    void PseudoTcpTx::setId(int id) {
        this->id = id;
    }

    int PseudoTcpTx::getId() const {
        return id;
    }

    void PseudoTcpTx::resend(Message *msg) {
        if (Verbose) std::cerr << *this << " Resending msg " << msg->toString() << std::endl;
        sendNow(msg);
        if (Verbose) std::cerr << *this << " msg resent" << std::endl;
        //unAckedMsgs.push_front(msg);
    }

    void PseudoTcpTx::sendNow(Message* msg) {
        if (conn != NULL) {
            msg->SetLastSent(SimpleTimer::now());
            conn->sendMessage(msg);
        }
    }

    void PseudoTcpTx::incomingAck(Message* ack) {
        boost::mutex::scoped_lock(threadMutex);
        if (Verbose) std::cerr << *this << " >>>>>>>>>>>>>>>>>>>>" << this << " incoming ack " << std::endl;
        Logger::EventLogger::addEventG(Logger::Important, this, "Got ack " + ack->toString());
        acksReceived++;
        while (!unAckedAckReqs.empty() && unAckedAckReqs.front()->GetNumber() <= ack->GetAckNumber())
            unAckedAckReqs.pop_front();
        if (Verbose) std::cerr << "  after popping ackAcks " << *this << std::endl;
        removeAckedMessages(ack);
        if (Verbose) std::cerr << "  after removeAckedMessages " << *this << std::endl;
        base = ack->GetAckBase();
        // todo: see if there is now room to send more messages
        while (!msgBuff.empty() && nextMessageNumber - base <= msgBufferSize) {
            Message *m = msgBuff.front();
            msgBuff.pop_front();
            send(m);
        }
        Logger::EventLogger::addEventG(Logger::Important, this, "Ack processed");
        if (Verbose) std::cerr << this << " <<<<<<<<<<<<<<<<<<<<" << this << " end incoming ack " << std::endl;
    }

    // using information in the ack, go thru the unacked messages
    // if acked, remove; if unacked resend, and push back on unacked queue,
    // preserving order

    void PseudoTcpTx::removeAckedMessages(Message* ack) {
        std::stack<Message *> resendQ;
        int highestMessageNumber = ack->GetAckBase() + msgBufferSize - 1; // todo: here, we shouldn't go over ackNumber I think...
        if (highestMessageNumber > ack->GetAckNumber()) highestMessageNumber = ack->GetAckNumber();
        if (Verbose) std::cerr << "  removing acked msgs, up to " << highestMessageNumber << std::endl;
        while (!unAckedMsgs.empty() && unAckedMsgs.front()->GetNumber() <= highestMessageNumber) {
            Message *msg = unAckedMsgs.front();
            unAckedMsgs.pop_front();
            if (Verbose) std::cerr << "  popped " << msg->toString() << std::endl;
            if (!acked(ack, msg->GetNumber())) {
                if (Verbose) std::cerr << "  queue for resend" << std::endl;
                resendQ.push(msg);
            } else {
                if (Verbose) std::cerr << "  acked" << std::endl;
            }
        }
        //std::cerr << " tx " << *this << std::endl;
        while (!resendQ.empty()) {
            Message *msg = resendQ.top();
            resendQ.pop();
            // if it's the last one, make it reqAck
            if (resendQ.empty()) {
                msg->SetReqAck(true);
                unAckedAckReqs.push_back(msg);
            } else { // otherwise it's not an ack req
                msg->SetReqAck(false);
            }
            resend(msg);
            unAckedMsgs.push_front(msg);
        }
        if (Verbose) std::cerr << "  " << *this << " after resending unacked messages" << std::endl;
    }

    /**
     *   This function interprets the information in an
     * ack message, to see if the particular message number
     * is acked.
     *
     * @param ack
     *   The ack message
     * @param msgNumber
     *   The message number we are interested in
     * @return
     *   true if the message acks the msgNumber
     */
    bool PseudoTcpTx::acked(Message* ack, int msgNumber) {
        if (msgNumber < ack->GetAckBase()) return true;
        int bitIndex = msgNumber - ack->GetAckBase();
        if (bitIndex > msgBufferSize) throw "PseudoTcpTx::acked error, msgNumber too high";
        return ack->GetMsgsReceived()[bitIndex];
    }

    void PseudoTcpTx::startCheckAckThread() {
        assert(!checkAckThread);
        checkAckThread = boost::shared_ptr<boost::thread > (new boost::thread(boost::bind(&PseudoTcpTx::checkUnackedAcks, this)));
        if (Verbose) std::cerr << " Starting checkAckThread: " << checkAckThread.get()->get_id() << std::endl;
    }

    void PseudoTcpTx::checkUnackedAcks() {
        //std::cerr << "...ack thread running...";
        conn->registerThread();
        while (!stopThread) {
            //std::cerr << "<<<<<<<<<<<<<<<<<<<< TX " << this << ": " << *this << "...sleep ack thread checking..." << std::endl;
            boost::this_thread::sleep(boost::posix_time::milliseconds(ACK_CHECKING_INTERVAL));
            //std::cerr << ">>>>>>>>>>>>>>>>>>>> TX " << this << ": " << *this << "...go ack thread checking..." << std::endl;
            {
                boost::mutex::scoped_lock(threadMutex);
                if (Verbose) std::cerr << *this << "  >>>>>>>>>>>>>>>>>>>>  " << "...go ack thread checking..." << std::endl;
                double now = SimpleTimer::now();
                Message *mostRecentUnackedAck = NULL;
                while (!unAckedAckReqs.empty() && now - (mostRecentUnackedAck = unAckedAckReqs.front()) ->GetLastSent() > NO_ACK_RESEND_TIMEOUT) {
                    unAckedAckReqs.pop_front();
                }
                if (mostRecentUnackedAck != NULL) {
                    //std::cout << "..found one: " << *mostRecentUnackedAck << std::endl;
                    Logger::EventLogger::addEventG(Logger::Important, this, "Unacked acks exist...");
                    resend(mostRecentUnackedAck);
                    unAckedAckReqs.push_front(mostRecentUnackedAck);
                    Logger::EventLogger::addEventG(Logger::Important, this, "Unacked acks processed");
                } else { // It can happen that there are no UnackedAckReqs, but there are unacked messages.  In this case, send first message.
                    // todo: perhaps we should send them all?
                    // todo: is this still true?  Can this still happen?  I think it shouldn't...
                    if (!unAckedMsgs.empty()) {
                        Logger::EventLogger::addEventG(Logger::Important, this, "Unacked Msgs exist...");
                        Message *m = unAckedMsgs.front();
                        // it should ask for an ack
                        m->SetReqAck(true);
                        unAckedAckReqs.push_back(m);
                        //unAckedMsgs.pop_front();   // Don't pop it you idiot, it's still unAcked!!
                        resend(m);
                        Logger::EventLogger::addEventG(Logger::Important, this, "Unacked Msg resent...");
                    }
                }
                if (Verbose) std::cerr << *this << "  <<<<<<<<<<<<<<<<<<<<  " << "...sleep ack thread checking..." << std::endl;
            }
        }
    }

    int PseudoTcpTx::getAcksReceived() const {
        return acksReceived;
    }

}

