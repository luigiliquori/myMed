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
 * File:   IAckHandler.hpp
 * Author: user
 *
 * Created on July 3, 2011, 6:52 PM
 */

#ifndef IACKHANDLER_HPP
#define	IACKHANDLER_HPP

#include "Message.hpp"

namespace PseudoTcp
{

    /// Interface for callback from Connection with Message
    /**
     * The IMsgHandler interface serves implement a callback from the
     * lower level Connection to the higher level MessageDispatcher avoiding
     * circular dependencies.
     * @param msg
     *   The Message to handle.
     */
    class IMsgHandler {
    public:
        virtual void IncomingMessage(Message *msg) = 0;
    };
}

#endif	/* IACKHANDLER_HPP */

