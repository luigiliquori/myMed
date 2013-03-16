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


#include "PseudoTcpUtil.hpp"

namespace PseudoTcp {

    std::ostream& MsgQueStr(std::ostream& o, std::queue<Message *> const vec) {
        o << "(";
        if (vec.size() > 0) o << vec.front()->GetNumber();
        if (vec.size() > 1) o << ".." << vec.back()->GetNumber();
        o << ")";
        return o;
    }

    std::ostream& MsgDeqStr(std::ostream& o, std::deque<Message *> const vec) {
        o << "(";
        for (unsigned int i = 0; i < vec.size(); i++) {
            o << vec.at(i)->GetNumber() << ",";
        }
        o << ")";
        return o;
    }

    std::ostream& EventDeqStr(std::ostream& o, std::deque<Logger::Event> const vec) {
        o << "(";
        for (unsigned int i = 0; i < vec.size(); i++) {
            o << vec.at(i).GetName() << ',' << vec.at(i).GetEvent() << ',' << vec.at(i).GetState() << std::endl;
        }
        o << ")";
        return o;
    }


}