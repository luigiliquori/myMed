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
 * File:   IceConnection.hpp
 * Author: user
 *
 * Created on August 8, 2011, 10:08 PM
 */

#ifndef ICEWRAPPER_HPP
#define	ICEWRAPPER_HPP

#include "p2pConnApi.h"
#include <boost/bind.hpp>
#include <pjlib-util.h>
#include <string>
#include <map>
#include "IceConnection.hpp"
#include "MessageDispatcher.hpp"

/// The IceWrapper class furnishes a C++ interface to the low-level C functionality
/// provided by the p2pConnApi.c and rsComm.c files.

/**
 * IceWrapper provides a facade to C++ for invoking the low-level ice functions.
 * It uses static functions for the callbacks.
 *
 * For each low-level connection (P2pConnection) created, IceWrapper creates
 * an IceConnection and a MessageDispatcher.  It keeps two maps which relate the
 * userName to the relative IceConnection and MessageDispatcher.
 *
 * IceWrapper also supplies a function which scans all existing MessageDispatchers
 * to see if any have input waiting.
 */
class IceWrapper {
public:
    IceWrapper();
    IceWrapper(const IceWrapper& orig);
    virtual ~IceWrapper();

    /// callback handler
    static void OnNewConnection(struct P2pConnection *conn);

    /// callback handler
    static void OnIncomingMessage(struct P2pConnection *conn, char* msg, unsigned size);

    /// callback handler
    static int OnNewConnectionRequest(struct P2pConnection *conn);

    /// callback handler
    static void OnNewUsers(char *rsUsersString);

    /// accessor for Name property, used for debugging
    void SetName(std::string name);

    /// accessor for Name property, used for debugging
    std::string GetName() const;

    static bool Verbose;

    /**
     * Initialization parameters for low level
     * @param userName
     *   Name of user who is registering
     * @param service
     *   MyMed Application number
     * @param rsIp
     *   IP address of Rendezvous server
     * @param rsPort
     *   Port of Rendezvous server
     */
    void init(const char *userName, int service, const char *rsIp, const char *rsPort);

    /**
     * Request a connection to a user for an application
     * @param toUser
     *   user to connect to
     * @param service
     *   MyMed application number
     */
    void getConn(std::string toUser, int service);

    /**
     * Send a string using a connection
     * @param conn
     *   Lower level connection to use
     * @param msg
     *   Message to send
     */
    void send(struct P2pConnection *conn, std::string msg);

    /**
     * Return IceConnection corresponding to user
     * @param userName
     *   name of user to get connection of
     * @return
     */
    static IceConnection *getConnectionOfUser(std::string userName);

    /**
     * Return MessageDispatcher corresponding to user
     * @param userName
     *   name of user to get MessageDispatcher of
     * @return
     */
    static PseudoTcp::MessageDispatcher *getMdOfUser(std::string userName);

    /**
     * Obsolete, remove
     * @return
     */
    static const char *getInput();

    /**
     * Scan all MessageDispatchers, if any have input return the input.
     * @param from
     *   'out' parameter will be set to userName of user who sent the input.
     * @return
     */
    static const char *getInput(char *from);

    /**
     * Return the userstring received from the RS
     * @return
     */
    char *getUsers();

    /**
     * Return true if userstring has been updated
     * @return
     */
    bool usersChanged();

    /**
     * Send a request to the RS for currently registered users.
     */
    void requestUsers();
    //void registerCallback(void (*javaCallback)(char *from, char *msg));

private:
    struct p2p_callbacks cb;
    struct P2pConnectionOptions options;
    std::string name;
    static char userString[];
    static bool usersHaveChanged;

    void initOptions();
    static std::map<std::string, IceConnection *> conns;
    static std::map<std::string, PseudoTcp::MessageDispatcher *> mds;
    static int nextId;

    //void (*javaCallback)(char *from, char *msg);



    //cb.onNewConnection = cb_NewConnection;
    //cb.onIncomingMessage = cb_IncomingMessage;
    //cb.onNewConnectionRequest = cb_NewConnectionRequest;
    //cb.onNewUsers = cb_foo;

};

#endif	/* ICEWRAPPER_HPP */

