package com.mymed.controller.core.services.requesthandler;

import java.util.Enumeration;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;

public abstract class AbstractRequestHandler extends HttpServlet {

	private static final long serialVersionUID = 1L;

	/* --------------------------------------------------------- */
	/*                      protected methods       	         */
	/* --------------------------------------------------------- */
	/**
	 * @return the parameters of an HttpServletRequest
	 */
	protected Map<String, String> getParameters(HttpServletRequest request){
		Map<String, String> parameters = new HashMap<String, String>();
		Enumeration<String> paramNames = request.getParameterNames();
		while (paramNames.hasMoreElements()) {
			String paramName = (String) paramNames.nextElement();
			String[] paramValues = request.getParameterValues(paramName);
			if (paramValues.length >= 1) { // all the params should be atomic
				parameters.put(paramName, paramValues[0]);
			}
		}
		return parameters;
	}
	
}
