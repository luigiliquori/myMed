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
 * File:   Message.hpp
 * Author: user
 *
 * Created on June 28, 2011, 10:12 PM
 */


#ifndef MESSAGE_HPP
#define	MESSAGE_HPP

#include <bitset>
#include <string>
#include <iostream>
#include <vector>

#include <boost/archive/text_iarchive.hpp>
#include <boost/archive/text_oarchive.hpp>
#include <boost/serialization/vector.hpp>
#include <boost/serialization/bitset.hpp>


#define MSG_WIN 16

namespace PseudoTcp {

    /// Encapsulates the protocol format.

    /**
     * GetByteArray returns the payload, the rest is the header.
     */
    class Message {
    public:

        Message() : byteArray(), number(UNNUMBERED), lastSent(NEVER_SENT), reqAck(0), ack(0), ackNumber(0), ackBase(0) {
        };

        Message(std::vector<unsigned char> payload) : number(UNNUMBERED), lastSent(NEVER_SENT), reqAck(0), ack(0), ackNumber(0), ackBase(0)
        {
            byteArray = payload;
        }

        /**
         *   For testing purposes, create a message of length n whose elements are 'a'
         * @param n
         */
        Message(int n);

        /// Returns the payload
        std::vector<unsigned char> &GetByteArray();

        /** @defgroup group1 Accessors
         * @{
         */

        /// Set whether this Message contains an Ack
        void SetAck(bool ack);
        /// true if the Message is an Ack
        bool IsAck() const;
        /// Number property is sequential numbering of Messages sent by a PseudoTcpTx
        void SetNumber(int number);
        /// Number property is sequential numbering of Messages sent by a PseudoTcpTx
        int GetNumber() const;
        /// ReqAck property determines whether this Message requests an ack.
        void SetReqAck(bool reqAck);
        /// ReqAck property determines whether this Message requests an ack.
        bool IsReqAck() const;
        /// AckNumber property is the Message number of the message we are acking
        void SetAckNumber(int ackNumber);
        /// AckNumber property is the Message number of the message we are acking
        int GetAckNumber() const;
        /// AckBase property represents the lowest missing Message number
        void SetAckBase(int ackBase);
        /// AckBase property represents the lowest missing Message number
        int GetAckBase() const;
        /// LastSent property is the time of the most recent attempted send of the Message.
        /** Initialized to special value NEVER_SENT @see NEVER_SENT */
        void SetLastSent(double lastSent);
        /// LastSent property is the time of the most recent attempted send of the Message.
        double GetLastSent() const;
        /// for debugging only.  @todo: remove
        std::bitset<MSG_WIN> GetMsgsReceived();
        /// each bit represents a Message in the window, 1 if received, 0 if not
        void SetMsgsReceived(std::bitset<MSG_WIN> msgsReceived);
        /** @}*/

        /// true if message has payload (is not just an ack)

        bool hasPayload() {
            return !byteArray.empty();
        }

        /// A more detailed output then <<
        std::string toString() const;

        friend std::ostream & operator<<(std::ostream& o, Message const& msg) {
            return o << "[msg# " << msg.number << "|" << &msg << "|" << ']';
        }

        /// Special value of property Number to indicate unitialized
        static const int UNNUMBERED = -1;
        /// Special value of property LastSent to indicate Message has never been sent
        static const double NEVER_SENT = -1.0;

    private:
        /// Boost serialization
        friend class boost::serialization::access;

        template<class Archive>
        void serialize(Archive & ar, const unsigned int version) {
            ar & byteArray;
            ar & number;
            // lastSent is not needed, but for now:
            ar & lastSent;
            ar & reqAck;
            ar & ack;
            ar & ackNumber;
            ar & ackBase;
            ar & msgsReceived;
        }
        /// The payload of the Message.
        std::vector<unsigned char> byteArray;
        // header
        int number; /**< var for Number property */
        double lastSent; /**< var for LastSent property */
        // ack
        bool reqAck; /**< var for ReqAck property */
        bool ack; /**< var for Ack property */
        int ackNumber; /**< var for AckNumber property */
        int ackBase; /**< var for AckBase property */
        std::bitset<MSG_WIN> msgsReceived;

        /// for debugging only
        std::string ackToString() const;
        /// for debugging only
        std::string msgsReceivedToString() const;

    };
}

#endif	/* MESSAGE_HPP */

