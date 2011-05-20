package com.mymed.controller.core.services.requesthandler;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.controller.core.services.manager.pubsub.DHTManager;
import com.mymed.controller.core.services.requesthandler.exception.IOBackEndException;
import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;
import com.mymed.model.core.data.dht.factory.IDHTClient.ClientType;

/**
 * Handle all the request from the frontend
 * @author lvanni
 *
 */
public class DHTRequestHandler extends AbstractRequestHandler {
	/* --------------------------------------------------------- */
	/*                      Attributes                           */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	private DHTManager dhtManager ;

	/* --------------------------------------------------------- */
	/*                      Constructors                         */
	/* --------------------------------------------------------- */
	/**
	 * @throws ServletException 
	 * @see HttpServlet#HttpServlet()
	 */
	public DHTRequestHandler() throws ServletException {
		super();
		try {
			this.dhtManager = new DHTManager(ClientType.CASSANDRA);
		} catch (InternalBackEndException e) {
			throw new ServletException("DHTManager is not accessible because: " + e.getMessage());
		}
	}

	/* --------------------------------------------------------- */
	/*                      extends HttpServlet                  */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		try {
			/** Get the parameters */
			Map<String, String> parameters = getParameters(request);

			/** handle the request */
			RequestCode code = requestCodeMap.get(parameters.get("code"));
			if(code == null){
				throw new ServletException("unknown code: " + parameters.get("code"));
			}
			
			String key;
			switch(code){
			case READ :   // GET
				if((key = parameters.get("key")) == null){
					throw new ServletException("missing key argument!"); 
				}
				setResponseText(dhtManager.get(key));
				break;
			default : 
				throw new ServletException("DHTRequestHandler.doGet(" + code + ") not exist!");
			}
			super.doGet(request, response);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
			throw new ServletException(e.getMessage());
		} catch (IOBackEndException e) {
			e.printStackTrace();
			throw new IOException(e.getMessage());
		}
	}
	
	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	protected void doPost(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {
		try {
			/** Get the parameters */
			Map<String, String> parameters = getParameters(request);

			/** handle the request */
			RequestCode code = requestCodeMap.get(parameters.get("code"));
			if(code == null){
				throw new ServletException("unknown code: " + parameters.get("code"));
			}
			
			String key, value;
			switch(code){
			case CREATE : // PUT
				if((key = parameters.get("key")) == null){
					throw new ServletException("missing key argument!"); 
				} else if((value = parameters.get("value")) == null){
					throw new ServletException("missing value argument!"); 
				}
				System.out.println("key to publish: " + key);
				System.out.println("value to publish: " + value);
				dhtManager.put(key, value);
				setResponseText("key published");
				break;
			default : 
				throw new ServletException("DHTRequestHandler.doGet(" + code + ") not exist!");
			}
			super.doGet(request, response);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
			throw new ServletException(e.getMessage());
		} catch (IOBackEndException e) {
			e.printStackTrace();
			throw new IOException(e.getMessage());
		}
	}
}