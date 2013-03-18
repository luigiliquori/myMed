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
 * File:   main.cpp
 * Author: user
 *
 * Created on August 8, 2011, 10:03 PM
 */

#include <cstdlib>
#include <iostream>
#include <sstream>
#include <vector>
#include <iosfwd>
#include "p2pConnApi.h"
#include "IceWrapper.hpp"

#include "MessageDispatcher.hpp"
#include "Message.hpp"

using namespace std;

/*
 * 
 */

void processCommand(std::map<std::string, PseudoTcp::MessageDispatcher *> conns, IceWrapper iw, string cmdLine) {
    //std::cout << "processing command " << cmdLine << std::endl;
    // tokenize
    std::stringstream ss(cmdLine);
    std::vector<std::string> tokens(10);
    int i = 0;
    while (i < 10 && ss >> tokens[i++]);
    //    for (int j = 0; j < i; j++) {
    //        std::cerr << "token " << j << " is " << tokens[j] << std::endl;
    //    }
    std::string cmd = tokens[0];
    if (cmd == "c" || cmd == "connect") { // get connection to user
        iw.getConn(tokens[1], 7);
    } else if (cmd == "s" || cmd == "send") {
        // get connection
        // first look in conns
        PseudoTcp::MessageDispatcher *md = iw.getMdOfUser(tokens[1]);
        if (md == NULL) {
            std::cerr << "conn for user " << tokens[1] << " not found" << std::endl;
            return;
        }
        PseudoTcp::Message *m = new PseudoTcp::Message();
        std::vector<unsigned char> &ba = m->GetByteArray();
        for (unsigned int i = 0; i < tokens[2].size(); i++) {
            ba.insert(ba.end(), tokens[2][i]);
        }
        m->SetReqAck(true);
        //cerr << "created message " << m->toString() << endl;
        md->send(m);  // todo: does this have to be created on heap?
        //cerr << "message sent" << endl;
    } else if (cmd == "u" || cmd == "users") {
        cerr << "requesting users" << endl;
        iw.requestUsers();
    } else {
        std::cout << "Commands are:" << std::endl << "c (connect) username" << std::endl
                << "s (sed) username msg" << std::endl
                << "u (users)" << std::endl;
    }

}

int main(int argc, char** argv) {

    if (argc != 2) {
        std::cerr << "Usage: iceInt userName" << std::endl;
        return -1;
    }
    std::string userName = argv[1];
    std::cout << "Hello " << userName << std::endl;

    IceWrapper iw;
    std::map<std::string, PseudoTcp::MessageDispatcher *> conns;
    iw.SetName(userName);
    iw.init(userName.c_str() , 7, "127.0.0.1", "44456");

    bool done = false;
    string cmd;
    while (!done) {
        getline(std::cin, cmd);
        std::cout << "Command is " << cmd << std::endl;
        if (cmd == "q" || cmd == "quit") {
            done = true;
        } else {
            processCommand(conns, iw, cmd);
        }
    }


    return 0;
}

