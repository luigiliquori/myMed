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
#include <deque>

#include "EventLogger.hpp"
#include "Event.hpp"
#include "IEventLogger.hpp"
#include "PseudoTcpUtil.hpp"

namespace Logger {

    void EventLogger::addEvent(IEventLogger *object, std::string event) {
        //        std::cerr << " adding event " << event << ';';
        //        std::cerr << " object is " << object << std::endl;
        //        std::cerr << " object name is " << object->GetLogName() << std::endl;
        //        std::cerr << " object str is " << object->logString() << std::endl;
        Event e(object->GetLogName(), event, object->logString());
        eventMap[object->GetLogName()].push_back(e);
        allEvents.push_back(e);
    }

    void EventLogger::clearEvents() {
        eventMap.clear();
    }

    void EventLogger::outputEvents(std::ostream& stream) {
        for (unsigned int i = 0; i < allEvents.size(); i++) {
            outputEvent(stream, allEvents[i], getIndentForName(allEvents[i].GetName()));
            //            std::string obName = allEvents[i].GetName();
            //            std::string spaces(getIndentForName(obName), ' ');
            //            stream << spaces << allEvents[i] << std::endl;
        }
    }

    void EventLogger::outputEvent(std::ostream& stream, Event &ev, int indent) {
        std::string spaces(indent, ' ');
        stream << spaces << '{' << ev.GetName() << ' ' << ev.GetEvent() << std::endl << spaces << ' ' << ev.GetState() << '}' << std::endl;
    }

    void EventLogger::outputEvents(std::ostream& stream, std::string obName) {
        int indent = getIndentForName(obName);
        for (unsigned int i = 0; i < allEvents.size(); i++) {
            if (obName == allEvents[i].GetName()) {
                outputEvent(stream, allEvents[i], indent);
                //                stream << allEvents[i] << std::endl;
            }
        }
    }

    void EventLogger::setEventLevel(int eventLevel) {
        this->eventLevel = eventLevel;
    }

    int EventLogger::getEventLevel() const {
        return eventLevel;
    }

    EventLogger *EventLogger::globalInstance = NULL;
}
