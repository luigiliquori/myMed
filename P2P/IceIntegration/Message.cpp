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

#include <iosfwd>
#include <sstream>

#include "Message.hpp"

namespace PseudoTcp {

  
    Message::Message(int n) : byteArray(), number(-1), reqAck(0), ack(0), ackNumber(0), ackBase(0) {
        ack = 0;
        //byteArray = new unsigned char[n+1];
        for (int i=0; i<n; i++)
            byteArray.insert(byteArray.end(),'a');
        byteArray.insert(byteArray.end(), '\0');
        }
    
    void Message::SetAck(bool ack) {
        this->ack = ack;
    }
    bool Message::IsAck() const {
        return ack;
    }
    void Message::SetNumber(int number) {
        this->number = number;
    }
    int Message::GetNumber() const {
        return number;
    }
    void Message::SetReqAck(bool reqAck) {
        this->reqAck = reqAck;
    }
    bool Message::IsReqAck() const {
        return reqAck;
    }
    std::bitset<MSG_WIN> Message::GetMsgsReceived() {
        return msgsReceived;
    }
    void Message::SetAckNumber(int ackNumber) {
        this->ackNumber = ackNumber;
    }
    int Message::GetAckNumber() const {
        return ackNumber;
    }
    void Message::SetAckBase(int ackBase) {
        this->ackBase = ackBase;
    }
    int Message::GetAckBase() const {
        return ackBase;
    }

    std::vector<unsigned char> &Message::GetByteArray()  {
        return byteArray;
    }

    std::string Message::toString() const {
        std::ostringstream output;
        output << "[Msg#" << this->number <<   (this->reqAck ? "(rqAk)" : "") << "|" << byteArray.size() << "| ";
        output <<  ackToString() + "]";
        return output.str();
    }
    void Message::SetMsgsReceived(std::bitset<MSG_WIN> msgsReceived) {
        this->msgsReceived = msgsReceived;
    }
    void Message::SetLastSent(double lastSent) {
        this->lastSent = lastSent;
    }
    double Message::GetLastSent() const {
        return lastSent;
    }

    std::string Message::ackToString() const {
        if (!ack) return "";
        std::ostringstream output;
        output << "<" << ackNumber << " " << ackBase << ":" << msgsReceivedToString() << ">";
        return output.str();
    }

    std::string Message::msgsReceivedToString() const {
        std::ostringstream output;
        for (int i=0; i < MSG_WIN; i++)
            output << (msgsReceived[i] ? "1" : "0");
        return output.str();
    }

}
