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
			tr.open();
			ColumnParent parent = new ColumnParent(columnFamily);
			if (parameters.containsKey("act")) {
				int chx = Integer.parseInt(parameters.get("act"));
				switch (chx) {
				case CONNECT:
					this.address = parameters.get("address");
					this.port = Integer.parseInt(parameters.get("port"));
					tr.close();
					this.tr = new TSocket(address, port);
					this.proto = new TBinaryProtocol(tr);
					this.client = new Cassandra.Client(proto);
					break;
				case SETKEYSPACE:
					this.keyspace = parameters.get("keyspace");
					break;
				case SETCOLUMNFAMILY:
					this.columnFamily = parameters.get("columnFamily");
					break;
				case SETKEYUSERID:
					this.keyUserID = parameters.get("keyUserID");
					break;
				case INSERTKEY:
					long timestamp = System.currentTimeMillis();
					final Column column = new Column(
							MConverter.stringToByteBuffer(parameters
									.get("key1")),
							MConverter.stringToByteBuffer(parameters
									.get("value1")), timestamp);
					System.out.println("\ninsert:" + "\t- keyspace = "
							+ keyspace + "\n" + "\t- key = " + keyUserID + "\n"
							+ "\t- columnFamily = " + columnFamily + "\n"
							+ "\t- name = " + parameters.get("key1") + "\n"
							+ "\t- value = " + parameters.get("value1") + "\n");
					client.insert(MConverter.stringToByteBuffer(keyUserID),
							parent, column, ConsistencyLevel.ONE);
					this.result = "value insered!";
					break;
				case GETKEY:
					final ByteBuffer keyToBuffer = MConverter
							.stringToByteBuffer(keyUserID);
					ColumnOrSuperColumn result = null;
					final ColumnPath path = new ColumnPath(columnFamily);
					path.setColumn(parameters.get("key2").getBytes("UTF8"));
					result = client
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
					+ "<input name='keyspace' type='text' value='Keyspace1' />)"
					+ "</form>"
					// SETCOLUMNFAMILY
					+ "<form>"
					+ "<input type='hidden' name='act' value='"
					+ SETCOLUMNFAMILY
					+ "' />"
					+ "<input type='submit' value='setColumnFamily' /> ("
					+ "<input name='columnFamily' type='text' value='columnFamily' />)"
					+ "</form>"
					// SETKEYUSERID
					+ "<form>"
					+ "<input type='hidden' name='act' value='"
					+ SETKEYUSERID
					+ "' />"
					+ "<input type='submit' value='setKeyUserID' /> ("
					+ "<input name='keyUserID' type='text' value='1' />)"
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