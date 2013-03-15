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
 * File:   EventLogger.hpp
 * Author: user
 *
 * Created on July 7, 2011, 11:04 PM
 */

#ifndef EVENTLOGGER_HPP
#define	EVENTLOGGER_HPP

#include "IEventLogger.hpp"
#include "Event.hpp"
#include <deque>
#include <map>
#include <iostream>

/// The Logger namespace encapsulates some functions useful for debugging the PseudoTcp functionality.
namespace Logger {

    enum {
        None,
        Important,
        Medium,
        Detail
    };

    /**
     * EventLogger is for debugging.
     * Keeps track of events logged by IEventLogger objects.
     */
    class EventLogger {
    public:

        EventLogger() :
        eventLevel(None) {
        };

        void addEvent(IEventLogger *object, std::string event);

        void clearEvents();

        void outputEvents(std::ostream &stream);

        void outputEvents(std::ostream& stream, std::string obName);

        void persistEvents(std::string path);

        // global instance

        static EventLogger *glob() {
            return globalInstance;
        }

        static void setGlob(EventLogger *logger) {
            globalInstance = logger;
        }

        static void addEventG(int level, IEventLogger *object, std::string event) {
            if (globalInstance != NULL && level <= globalInstance->eventLevel) {
                //std::cerr << "adding event" << std::endl;
                globalInstance->addEvent(object, event);
            }
        }

        void setIndentLevel(std::string obName, int indent) {
            indentMap[obName] = indent;
        }

        void setEventLevel(int eventLevel);
        int getEventLevel() const;

    private:

        int getIndentForName(std::string obName) {
            if (indentMap.find(obName) == indentMap.end()) {
                return 0;
            } else {
                return indentMap[obName];
            }
        }

        void outputEvent(std::ostream& stream, Event &ev, int indent);

        std::map<std::string, std::deque<Event> > eventMap;

        std::map<std::string, int> indentMap;

        std::deque<Event> allEvents;

        int eventLevel;

        static EventLogger *globalInstance;
    };
}

#endif	/* EVENTLOGGER_HPP */

