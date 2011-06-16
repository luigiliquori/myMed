package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.dht.DHTManager;
import com.mymed.model.core.factory.IDHTWrapperFactory.WrapperType;

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
			this.dhtManager = new DHTManager(WrapperType.CASSANDRA);
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

			/** Get the method code */
			RequestCode code = requestCodeMap.get(parameters.get("code"));
			
			/** handle the request */
			String key;
			switch(code){
			case READ :   // GET
				if((key = parameters.get("key")) == null){
					handleInternalError(new InternalBackEndException("missing key argument!"), response);
					return;
				}
				try {
					setResponseText(dhtManager.get(key));
				} catch (IOBackEndException e) {
					handleNotFoundError(new IOBackEndException(key + " not found!"), response);
					return;
				}
				break;
			default : 
				handleInternalError(new InternalBackEndException("DHTRequestHandler.doGet(" + code + ") not exist!"), response);
				return;
			}
			super.doGet(request, response);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
			handleInternalError(e, response);
			return;
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

			/** Get the method code */
			RequestCode code = requestCodeMap.get(parameters.get("code"));
			
			/** handle the request */
			String key, value;
			switch(code){
			case CREATE : // PUT
				if((key = parameters.get("key")) == null){
					handleInternalError(new InternalBackEndException("missing key argument!"), response);
					return;
				} else if((value = parameters.get("value")) == null){
					handleInternalError(new InternalBackEndException("missing value argument!"), response);
					return;
				}
				System.out.println("key to publish: " + key);
				System.out.println("value to publish: " + value);
				try {
					dhtManager.put(key, value);
					setResponseText("key published");
				} catch (IOBackEndException e) {
					handleNotFoundError(new IOBackEndException(key + " not published: " + e.getMessage()), response);
					return;
				}
				break;
			default : 
				handleInternalError(new InternalBackEndException("DHTRequestHandler.doGet(" + code + ") not exist!"), response);
				return;
			}
			super.doGet(request, response);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
			handleInternalError(e, response);
			return;
		}
	}
}