package com.mymed.controller.core.services.requesthandler;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;

/**
 * Servlet implementation class CassandraRequestHandler
 */
public class CassandraRequestHandler extends AbstractRequestHandler {
	/* --------------------------------------------------------- */
	/*                      Attributes                           */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	/* --------------------------------------------------------- */
	/*                      Constructors                         */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#HttpServlet()
	 */
	public CassandraRequestHandler() {
		super();
	}

	/* --------------------------------------------------------- */
	/*                      extends HttpServlet                  */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		/** Get the parameters */
		Map<String, String> parameters;
		try {
			parameters = getParameters(request);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
			throw new ServletException(e.getMessage());
		}

		/** handle the request */
		RequestCode code = requestCodeMap.get(parameters.get("code"));

		switch(code){ // TODO 
		default : break;
		}

		super.doGet(request, response);
	}

}
