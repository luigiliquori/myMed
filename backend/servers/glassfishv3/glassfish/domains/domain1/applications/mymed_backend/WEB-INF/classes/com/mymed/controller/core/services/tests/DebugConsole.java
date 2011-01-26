package com.mymed.controller.core.services.tests;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.cassandra.thrift.Cassandra;
import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.cassandra.thrift.Cassandra.Client;
import org.apache.thrift.TException;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;

import com.mymed.controller.core.services.requesthandler.IRequestHandler;

/**
 * 
 * @author lvanni
 *
 */
public class DebugConsole extends HttpServlet implements IRequestHandler{
	private static final long serialVersionUID = 1L;
    
	private TTransport tr;
	private TProtocol proto;
	private Client client;
	private String keyspace = "Keyspace1";
	private String columnFamily = "Standard1";
	private String keyUserID = "1";
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
		this.keyspace = "Keyspace1";
		this.columnFamily = "Standard1";
		this.keyUserID = "1";

		this.tr = new TSocket(address, port);
		this.proto = new TBinaryProtocol(tr);
		this.client = new Cassandra.Client(proto);
		this.result = "";
    }

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		// clear results
		this.result = "";

		// Get the parameters
		Map<String, String> parameters = new HashMap<String, String>();
		Enumeration<String> paramNames = request.getParameterNames();
		try {
			while (paramNames.hasMoreElements()) {
				String paramName = (String) paramNames.nextElement();
				String[] paramValues = request.getParameterValues(paramName);
				String paramValue = "";
				if (paramValues.length == 1) {
					paramValue = paramValues[0];
					if (paramValue.length() == 0) {
						parameters.put(paramName, "no value");
					} else {
						parameters.put(paramName, paramValue);
					}
				} else {
					for (int i = 0; i < paramValues.length; i++) {
						paramValue += paramValues[i] + ",";
					}
					parameters.put(paramName, paramValue);
				}
			}

			// Cassandra actions
			tr.open();
			ColumnPath colPathName = new ColumnPath(columnFamily);
			if (parameters.containsKey("act")){
				int chx = Integer.parseInt(parameters.get("act"));
				switch(chx){
				case CONNECT :
					this.address = parameters.get("address");
					this.port = Integer.parseInt(parameters.get("port"));
					this.tr = new TSocket(address, port);
					break;
				case SETKEYSPACE :
					this.keyspace = parameters.get("keyspace");
					break;
				case SETCOLUMNFAMILY :
					this.columnFamily = parameters.get("columnFamily");
					break;
				case SETKEYUSERID :
					this.keyUserID = parameters.get("keyUserID");
					break;
				case INSERTKEY :
					long timestamp = System.currentTimeMillis();
					colPathName.setColumn(parameters.get("key1").getBytes("UTF8"));
					client.insert(keyspace, keyUserID, colPathName, parameters.get(
					"value1").getBytes("UTF8"), timestamp,
					ConsistencyLevel.ONE);
					this.result = "value insered!";
					break;
				case GETKEY :
					colPathName.setColumn(parameters.get("key2").getBytes("UTF8"));
					Column col = client.get(keyspace, keyUserID, colPathName,
							ConsistencyLevel.ONE).getColumn();
					this.result = new String(col.value, "UTF8");
					break;
				default : break;
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
		} finally {
			// finish the transaction
			parameters.clear();
			tr.close();
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
			out
			.println("<h1>myMed: Cassandra tests</h1>"
					+ "<hr />"
					// STATUS
					+ "<b>Status:</b><br />"
					+ "<span style='color: green;'>connected</span> on: " + this.address + ":" + this.port + "<br />"
					+ "keyspace: " + this.keyspace + "<br />"
					+ "columnFamily: " + this.columnFamily + "<br />"
					+ "keyUserID: " + this.keyUserID + "<br />"
					+ "<hr />"
					// CONNECT
					+ "<b>Console:</b><br />"
					+ "<form>"
					+ "<input type='hidden' name='act' value='" + CONNECT + "' />"
					+	   "<input type='submit' value='Connect' /> ("
					+ "<input name='address' type='text' value='138.96.242.2' />, "
					+ "<input name='port' type='text' value='4201'/> )"
					+ "</form>"
					// SETKEYSPACE
					+ "<form>"
					+ "<input type='hidden' name='act' value='" + SETKEYSPACE + "' />"
					+	   "<input type='submit' value='setKeyspace'/> ("
					+ "<input name='keyspace' type='text' value='Keyspace1' />)"
					+ "</form>"
					// SETCOLUMNFAMILY
					+ "<form>"
					+ "<input type='hidden' name='act' value='" + SETCOLUMNFAMILY + "' />"
					+	   "<input type='submit' value='setColumnFamily' /> ("
					+ "<input name='columnFamily' type='text' value='columnFamily' />)"
					+ "</form>"
					// SETKEYUSERID
					+ "<form>"
					+ "<input type='hidden' name='act' value='" + SETKEYUSERID + "' />"
					+	   "<input type='submit' value='setKeyUserID' /> ("
					+ "<input name='keyUserID' type='text' value='1' />)"
					+ "</form>"
					// INSERTKEY
					+ "<form>"
					+ "<input type='hidden' name='act' value='" + INSERTKEY + "' />"
					+	   "<input type='submit' value='Insert' /> ("
					+ "<input name='key1' type='text' value='columnName' />, "
					+ "<input name='value1' type='text' value='value'/> )"
					+ "</form>"
					// GETKEY
					+ "<form>"
					+ "<input type='hidden' name='act' value='" + GETKEY + "' />"
					+	   "<input type='submit' value='Get' /> ("
					+ "<input name='key2' type='text' value='columnName' /> )"
					+ "</form>");
			out
			.println("<div style='position:relative; width:500px; height:200px; border:thin black solid; overflow:auto;'>");
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