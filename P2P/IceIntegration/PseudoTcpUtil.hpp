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
 * File:   PseudoTcpUtil.hpp
 * Author: user
 *
 * Created on July 6, 2011, 12:50 PM
 */

#ifndef PSEUDOTCPUTIL_HPP
#define	PSEUDOTCPUTIL_HPP

#include "Message.hpp"
#include "Event.hpp"
#include <queue>

namespace PseudoTcp {

    std::ostream& MsgQueStr(std::ostream& o, std::queue<Message *> const vec);

    std::ostream& MsgDeqStr(std::ostream& o, std::deque<Message *> const vec);

    std::ostream& EventDeqStr(std::ostream& o, std::deque<Logger::Event> const vec);
}

#endif	/* PSEUDOTCPUTIL_HPP */

