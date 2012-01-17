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
package com.mymed.controller.core.requesthandler.debug;

import java.io.IOException;
import java.io.PrintWriter;
import java.nio.ByteBuffer;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.cassandra.thrift.Cassandra;
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
import org.apache.thrift.transport.TTransport;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.requesthandler.AbstractRequestHandler;
import com.mymed.utils.MConverter;

/**
 * 
 * @author lvanni
 * 
 */
public class DebugConsole extends AbstractRequestHandler implements
		IRequestHandler {
	private static final long serialVersionUID = 1L;
	
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
	public DebugConsole() {
		super();
		// TODO Auto-generated constructor stub

		// Default value
		this.address = "138.96.242.2";
		this.port = 4201;
		this.keyspace = "Mymed";
		this.columnFamily = "User";
		this.keyUserID = "User_ID_1";

		this.socket = new TSocket(address, port);
		this.thriftTransport = new TFramedTransport(socket);
		this.thriftProtocol = new TBinaryProtocol(thriftTransport);
		this.cassandraClient = new Client(thriftProtocol);
		this.result = "";
	}

	protected Map<String, String> getParameters(HttpServletRequest request) {
		Map<String, String> parameters = new HashMap<String, String>();
		Enumeration<String> paramNames = request.getParameterNames();
		while (paramNames.hasMoreElements()) {
			String paramName = (String) paramNames.nextElement();
			String[] paramValues = request.getParameterValues(paramName);
			if (paramValues.length >= 1) { // all the params should be atomic
				parameters.put(paramName, paramValues[0]);
			}
			System.out.println(paramName + " : " + paramValues[0]);
		}
		return parameters;
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	protected void doGet(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {
		// clear results
		this.result = "";

		/** Get the parameters */
		Map<String, String> parameters = null;
		;
		parameters = getParameters(request);

		try {
			// Cassandra actions
			thriftTransport.open();
			cassandraClient.set_keyspace(keyspace);
			ColumnParent parent = new ColumnParent(columnFamily);
			if (parameters.containsKey("act")) {
				int chx = Integer.parseInt(parameters.get("act"));
				switch (chx) {
				case CONNECT:
					this.address = parameters.get("address");
					this.port = Integer.parseInt(parameters.get("port"));
					thriftTransport.close();
					this.socket = new TSocket(address, port);
					this.thriftTransport = new TFramedTransport(socket);
					this.thriftProtocol = new TBinaryProtocol(thriftTransport);
					this.cassandraClient = new Client(thriftProtocol);
					break;
				case SETKEYSPACE:
					this.keyspace = parameters.get("keyspace");
					cassandraClient.set_keyspace(keyspace);
					break;
				case SETCOLUMNFAMILY:
					this.columnFamily = parameters.get("columnFamily");
					break;
				case SETKEYUSERID:
					this.keyUserID = parameters.get("keyUserID");
					break;
				case INSERTKEY:
					long timestamp = System.currentTimeMillis();
					Column column = new Column(
							MConverter.stringToByteBuffer(parameters
									.get("key1")),
							MConverter.stringToByteBuffer(parameters
									.get("value1")), timestamp);
					System.out.println("\ninsert:" + "\t- keyspace = "
							+ keyspace + "\n" + "\t- key = " + keyUserID + "\n"
							+ "\t- columnFamily = " + columnFamily + "\n"
							+ "\t- name = " + parameters.get("key1") + "\n"
							+ "\t- value = " + parameters.get("value1") + "\n");
					cassandraClient.insert(MConverter.stringToByteBuffer(keyUserID),
							parent, column, ConsistencyLevel.ONE);
					this.result = "value insered!";
					break;
				case GETKEY:
					final ByteBuffer keyToBuffer = MConverter
							.stringToByteBuffer(keyUserID);
					ColumnOrSuperColumn result = null;
					final ColumnPath path = new ColumnPath(columnFamily);
					path.setColumn(parameters.get("key2").getBytes("UTF8"));
					result = cassandraClient
							.get(keyToBuffer, path, ConsistencyLevel.ONE);
					this.result = new String(result.getColumn().getValue(),
							"UTF8");
					break;
				default:
					break;
				}
			}

		} catch (InvalidRequestException e) {
			e.printStackTrace();
		} catch (UnavailableException e) {
			e.printStackTrace();
		} catch (TimedOutException e) {
			e.printStackTrace();
		} catch (TException e) {
			e.printStackTrace();
		} catch (NotFoundException e) {
			e.printStackTrace();
		} catch (InternalBackEndException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
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
	protected void doPost(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {
		processRequest(request, response);
	}

	/**
	 * 
	 * @param request
	 * @param response
	 * @throws ServletException
	 * @throws IOException
	 */
	protected void processRequest(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {
		response.setContentType("text/html;charset=UTF-8");
		PrintWriter out = response.getWriter();
		try {
			out.println("<html>");
			out.println("<head>");
			out.println("<title>Cassandra Test</title>");
			out.println("</head>");
			out.println("<body style='width:500px;'>");
			out.println("<h1>myMed: Cassandra tests</h1>" + "<hr />"
					// STATUS
					+ "<b>Status:</b><br />"
					+ "<span style='color: green;'>connected</span> on: "
					+ this.address
					+ ":"
					+ this.port
					+ "<br />"
					+ "keyspace: "
					+ this.keyspace
					+ "<br />"
					+ "columnFamily: "
					+ this.columnFamily
					+ "<br />"
					+ "keyUserID: "
					+ this.keyUserID
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
					+ "<input name='key2' type='text' value='columnName' /> )"
					+ "</form>");
			out.println("<div style='position:relative; width:500px; height:200px; border:thin black solid; overflow:auto;'>");
			out.println(result);
			out.println("</div>");
			out.println("</body>");
			out.println("</html>");
		} finally {
			out.close();
		}
	}

	@Override
	public String getServletInfo() {
		return "no description...";
	}
}