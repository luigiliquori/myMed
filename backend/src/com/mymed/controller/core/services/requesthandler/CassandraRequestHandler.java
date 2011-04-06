package com.mymed.controller.core.services.requesthandler;

import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 * Servlet implementation class CassandraRequestHandler
 */
public class CassandraRequestHandler extends AbstractRequestHandler {
	/* --------------------------------------------------------- */
	/*                      Attributes                           */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	/** Request code Map*/ 
	private Map<String, RequestCode> requestCodeMap = new HashMap<String, RequestCode>();
	
	/** Request codes*/ 
	private enum RequestCode {
		CONNECT  ("0"),
		SETKEYSPACE ("1"),
		SETCOLUMNFAMILY ("2"),
		SETKEYUSERID ("3"),
		INSERTKEY ("4"),
		GETKEY ("5");

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
    public CassandraRequestHandler() {
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
			
			switch(code){ // TODO 
			default : break;
			}
		}
		
		super.doGet(request, response);
	}
	
}
