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
 * File:   Event.hpp
 * Author: user
 *
 * Created on July 7, 2011, 11:35 PM
 */

#ifndef EVENT_HPP
#define	EVENT_HPP

namespace Logger {

    class Event {
    public:

        Event(std::string name, std::string event, std::string objectState)
        : name(name),
        event(event),
        state(objectState) {
        }

        void SetState(std::string state) {
            this->state = state;
        }

        std::string GetState() const {
            return state;
        }

        void SetEvent(std::string event) {
            this->event = event;
        }

        std::string GetEvent() const {
            return event;
        }

        void SetName(std::string name) {
            this->name = name;
        }

        std::string GetName() const {
            return name;
        }

        friend std::ostream & operator<<(std::ostream& o, Event const& ev) {
            return o << '{' << ev.GetName() << ';' << ev.GetEvent() << ';' << ev.GetState() << '}';
        }


    private:
        std::string name;
        std::string event;
        std::string state;
    };
}

#endif	/* EVENT_HPP */

