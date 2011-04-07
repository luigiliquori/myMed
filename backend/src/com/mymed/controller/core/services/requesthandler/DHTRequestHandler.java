package com.mymed.controller.core.services.requesthandler;

import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.model.core.data.dht.IDHTClient.ClientType;
import com.mymed.model.core.wrapper.Wrapper;

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

	/** Request code Map*/ 
	private Map<String, RequestCode> requestCodeMap = new HashMap<String, RequestCode>();
	
	/** Request codes*/ 
	private enum RequestCode {
		PUT ("0"),
		GET ("1");
		
		public final String code;
		
		RequestCode(String code){
			this.code = code;
		}
	}
	
	/* --------------------------------------------------------- */
	/*                      Constructors                         */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#HttpServlet()
	 */
	public DHTRequestHandler() {
		super();
		// initialize the CodeMapping
		for(RequestCode r : RequestCode.values()){
			requestCodeMap.put(r.code, r);
		}
	}

	/* --------------------------------------------------------- */
	/*                      extends HttpServlet                  */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		/** Get the parameters */
		Map<String, String> parameters = getParameters(request);
		
		/** handle the request */
		if (parameters.containsKey("act")){
			RequestCode code = requestCodeMap.get(parameters.get("act"));
			
			switch(code){
			case PUT : 
				String key = parameters.get("key");
				String value = parameters.get("value");
				System.out.println("key to publish: " + key);
				System.out.println("value to publish: " + value);
				new Wrapper(ClientType.CASSANDRA).put(key, value);
				setResponse("key published");
				break;
			case GET : 
				key = parameters.get("key"); 
				System.out.println("key to search: " + key);
				setResponse(new Wrapper(ClientType.CASSANDRA).get(key));
				break;
			default : break;
			}
		} 
		
		super.doGet(request, response);
	}
}