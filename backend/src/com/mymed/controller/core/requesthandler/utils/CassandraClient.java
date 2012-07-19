/*
 * Copyright 2012 INRIA
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
package com.mymed.controller.core.requesthandler.utils;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.cassandra.thrift.Cassandra.Client;
import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TSocket;

import ch.qos.logback.classic.Logger;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.requesthandler.AbstractRequestHandler;
import com.mymed.utils.MConverter;
import com.mymed.utils.MLogger;

/**
 * @author lvanni
 */
@WebServlet("/CassandraClient")
public class CassandraClient extends AbstractRequestHandler {
    private static final long serialVersionUID = 1L;

    /** Cassandra debug operations */
    public static final int CONNECT = 0;
    public static final int SETKEYSPACE = 1;
    public static final int SETCOLUMNFAMILY = 2;
    public static final int SETKEYUSERID = 3;
    public static final int INSERTKEY = 4;
    public static final int GETKEY = 5;

    /** set user profile */
    public static final int SETPROFILE = 10;
    /** get user profile */
    public static final int GETPROFILE = 11;

    // SDK APIs Tests
    public static final int REGISTER = 20;
    public static final int GETAPPLIST = 21;
    public static final int GETAPPLIACTION = 22;
    public static final int PUBLISH = 23;

    private static final Logger LOGGER = MLogger.getLogger();

    private transient TFramedTransport thriftTransport;
    private transient TSocket socket;
    private transient TProtocol thriftProtocol;
    private transient Client cassandraClient;

    private String keyspace;
    private String columnFamily;
    private String keyUserID;
    private String result;

    private String address;
    private int port;

    /**
     * @see HttpServlet#HttpServlet()
     */
    public CassandraClient() {
        super();
        // Default value
        address = "138.96.242.2";
        port = 4201;
        keyspace = "Mymed";
        columnFamily = "User";
        keyUserID = "User_ID_1";

        socket = new TSocket(address, port);
        thriftTransport = new TFramedTransport(socket);
        thriftProtocol = new TBinaryProtocol(thriftTransport);
        cassandraClient = new Client(thriftProtocol);
        result = "";
    }

    @Override
    protected Map<String, String> getParameters(final HttpServletRequest request) {
        final Map<String, String> parameters = new HashMap<String, String>();
        final Enumeration<String> paramNames = request.getParameterNames();
        while (paramNames.hasMoreElements()) {
            final String paramName = paramNames.nextElement();
            final String[] paramValues = request.getParameterValues(paramName);

            if (paramValues.length >= 1) { // all the params should be atomic
                parameters.put(paramName, paramValues[0]);
            }

            LOGGER.info(paramName + " : " + paramValues[0]);
        }

        return parameters;
    }

    /**
     * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
     *      response)
     */
    @Override
    protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        // clear results
        result = "";

        /** Get the parameters */
        Map<String, String> parameters = null;;
        parameters = getParameters(request);

        try {
            // Cassandra actions
            thriftTransport.open();
            cassandraClient.set_keyspace(keyspace);
            final ColumnParent parent = new ColumnParent(columnFamily);
            if (parameters.containsKey("act")) {
                final int chx = Integer.parseInt(parameters.get("act"));
                switch (chx) {
                    case CONNECT :
                        address = parameters.get("address");
                        port = Integer.parseInt(parameters.get("port"));
                        thriftTransport.close();
                        socket = new TSocket(address, port);
                        thriftTransport = new TFramedTransport(socket);
                        thriftProtocol = new TBinaryProtocol(thriftTransport);
                        cassandraClient = new Client(thriftProtocol);
                        break;
                    case SETKEYSPACE :
                        keyspace = parameters.get("keyspace");
                        cassandraClient.set_keyspace(keyspace);
                        break;
                    case SETCOLUMNFAMILY :
                        columnFamily = parameters.get("columnFamily");
                        break;
                    case SETKEYUSERID :
                        keyUserID = parameters.get("keyUserID");
                        break;
                    case INSERTKEY :
                        final long timestamp = System.currentTimeMillis();
                        final Column column = new Column(MConverter.stringToByteBuffer(parameters.get("key1")),
                                        MConverter.stringToByteBuffer(parameters.get("value1")), timestamp);

                        LOGGER.info("\ninsert:" + "\t- keyspace = " + keyspace + "\n" + "\t- key = " + keyUserID + "\n"
                                        + "\t- columnFamily = " + columnFamily + "\n" + "\t- name = "
                                        + parameters.get("key1") + "\n" + "\t- value = " + parameters.get("value1")
                                        + "\n");

                        cassandraClient.insert(MConverter.stringToByteBuffer(keyUserID), parent, column,
                                        ConsistencyLevel.ONE);
                        result = "value insered!";
                        break;
                    case GETKEY :
                        final ByteBuffer keyToBuffer = MConverter.stringToByteBuffer(keyUserID);
                        ColumnOrSuperColumn result = null;
                        final ColumnPath path = new ColumnPath(columnFamily);
                        try {
                            path.setColumn(parameters.get("key2").getBytes("UTF8"));
                            result = cassandraClient.get(keyToBuffer, path, ConsistencyLevel.ONE);
                            this.result = new String(result.getColumn().getValue(), "UTF8");
                        } catch (final UnsupportedEncodingException ex) {
                            throw new InternalBackEndException(ex);
                        }
                        break;
                    default :
                        break;
                }
            }
        } catch (final InvalidRequestException e) {
            LOGGER.debug("Error in doGet", e);
        } catch (final UnavailableException e) {
            LOGGER.debug("Error in doGet", e);
        } catch (final TimedOutException e) {
            LOGGER.debug("Error in doGet", e);
        } catch (final TException e) {
            LOGGER.debug("Error in doGet", e);
        } catch (final NotFoundException e) {
            LOGGER.debug("Error in doGet", e);
        } catch (final InternalBackEndException e) {
            LOGGER.debug("Error in doGet", e);
        } finally {
            // finish the transaction
            parameters.clear();
            thriftTransport.close();
            processRequest(request, response);
        }
    }

    /**
     * @see HttpServlet
     */
    @Override
    protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        processRequest(request, response);
    }

    /**
     * @param request
     * @param response
     * @throws ServletException
     * @throws IOException
     */
    protected void processRequest(final HttpServletRequest request, final HttpServletResponse response)
                    throws ServletException {
        response.setContentType("text/html;charset=UTF-8");
        PrintWriter out = null;
        try {
            out = response.getWriter();
            out.println("<html>");
            out.println("<head>");
            out.println("<title>Cassandra Test</title>");
            out.println("</head>");
            out.println("<body style='width:500px;'>");
            // STATUS
            out.println("<h1>myMed: Cassandra tests</h1>" + "<hr />" + "<b>Status:</b><br />"
                            + "<span style='color: green;'>connected</span> on: "
                            + address
                            + ":"
                            + port
                            + "<br />"
                            + "keyspace: "
                            + keyspace
                            + "<br />"
                            + "columnFamily: "
                            + columnFamily
                            + "<br />"
                            + "keyUserID: "
                            + keyUserID
                            + "<br />"
                            + "<hr />"
                            // CONNECT
                            + "<b>Console:</b><br />"
                            + "<form>"
                            + "<input type='hidden' name='act' value='"
                            + CONNECT
                            + "' />"
                            + "<input type='submit' value='Connect' /> ("
                            + "<input name='address' type='text' value='138.96.242.2' />, "
                            + "<input name='port' type='text' value='4201'/> )"
                            + "</form>"
                            // SETKEYSPACE
                            + "<form>"
                            + "<input type='hidden' name='act' value='"
                            + SETKEYSPACE
                            + "' />"
                            + "<input type='submit' value='setKeyspace'/> ("
                            + "<input name='keyspace' type='text' value='Mymed' />)"
                            + "</form>"
                            // SETCOLUMNFAMILY
                            + "<form>"
                            + "<input type='hidden' name='act' value='"
                            + SETCOLUMNFAMILY
                            + "' />"
                            + "<input type='submit' value='setColumnFamily' /> ("
                            + "<input name='columnFamily' type='text' value='User' />)"
                            + "</form>"
                            // SETKEYUSERID
                            + "<form>"
                            + "<input type='hidden' name='act' value='"
                            + SETKEYUSERID
                            + "' />"
                            + "<input type='submit' value='setKey' /> ("
                            + "<input name='keyUserID' type='text' value='User_ID_1' />)"
                            + "</form>"
                            // INSERTKEY
                            + "<form>"
                            + "<input type='hidden' name='act' value='"
                            + INSERTKEY
                            + "' />"
                            + "<input type='submit' value='Insert' /> ("
                            + "<input name='key1' type='text' value='columnName' />, "
                            + "<input name='value1' type='text' value='value'/> )"
                            + "</form>"
                            // GETKEY
                            + "<form>"
                            + "<input type='hidden' name='act' value='"
                            + GETKEY
                            + "' />"
                            + "<input type='submit' value='Get' /> ("
                            + "<input name='key2' type='text' value='columnName' /> )" + "</form>");
            out.println("<div style='position:relative; width:500px; height:200px; border:thin black solid; overflow:auto;'>");
            out.println(result);
            out.println("</div>");
            out.println("</body>");
            out.println("</html>");
        } catch (final IOException ex) {
            throw new ServletException(ex);
        } finally {
            if (out != null) {
                out.close();
            }
        }
    }

    @Override
    public String getServletInfo() {
        return "no description...";
    }
}
