package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.pubsub.PubSubManager;

/**
 * Servlet implementation class PubSubRequestHandler
 */
public class FindRequestHandler extends AbstractRequestHandler {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	private PubSubManager pubsubManager;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * @throws ServletException
	 * @see HttpServlet#HttpServlet()
	 */
	public FindRequestHandler() throws ServletException {
		super();
		try {
			pubsubManager = new PubSubManager();
		} catch (final InternalBackEndException e) {
			throw new ServletException("PubSubManager is not accessible because: " + e.getMessage());
		}
	}

	/* --------------------------------------------------------- */
	/* extends HttpServlet */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException,
	        IOException {
		try {
			/** Get the parameters */
			final Map<String, String> parameters = getParameters(request);

			/** Get the method code */
			final RequestCode code = requestCodeMap.get(parameters.get("code"));

			/** handle the request */
			String application, predicate, user;

			if (code == RequestCode.READ) {
				if ((application = parameters.get("application")) == null) {
					handleError(new InternalBackEndException("missing application argument!"), response);
					return;
				} else if ((predicate = parameters.get("predicate")) == null) {
					handleError(new InternalBackEndException("missing predicate argument!"), response);
					return;
				}

				try {
					// GET DETAILS
					if ((user = parameters.get("user")) != null) {
						final List<Map<String, String>> details = pubsubManager.read(application, predicate, user);
						if (details.isEmpty()) {
							throw new IOBackEndException("no reslult found!");
						}

						setResponseText(getGson().toJson(details));
					} else { // GET RESULTS
						final List<Map<String, String>> resList = pubsubManager.read(application, predicate);
						if (resList.isEmpty()) {
							throw new IOBackEndException("no reslult found!");
						}

						setResponseText(getGson().toJson(resList));
					}
				} catch (final IOBackEndException e) {
					handleError(e, response);
				}
			} else {
				handleError(new InternalBackEndException("DHTRequestHandler.doGet(" + code + ") not exist!"), response);
				return;
			}

			// TODO test if it works
			// switch (code) {
			// case READ : // GET
			// if ((application = parameters.get("application")) == null) {
			// handleError(new
			// InternalBackEndException("missing application argument!"),
			// response);
			// return;
			// } else if ((predicate = parameters.get("predicate")) == null) {
			// handleError(new
			// InternalBackEndException("missing predicate argument!"),
			// response);
			// return;
			// }
			// try {
			// // GET DETAILS
			// if ((user = parameters.get("user")) != null) {
			// final List<Map<String, String>> details =
			// pubsubManager.read(application, predicate, user);
			// if (details.isEmpty()) {
			// throw new IOBackEndException("no reslult found!");
			// }
			// setResponseText(getGson().toJson(details));
			// } else { // GET RESULTS
			// final List<Map<String, String>> resList =
			// pubsubManager.read(application, predicate);
			// if (resList.isEmpty()) {
			// throw new IOBackEndException("no reslult found!");
			// }
			// setResponseText(getGson().toJson(resList));
			// }
			// } catch (final IOBackEndException e) {
			// handleError(e, response);
			// }
			// break;
			// default :
			// handleError(new
			// InternalBackEndException("DHTRequestHandler.doGet(" + code +
			// ") not exist!"),
			// response);
			// return;
			// }

			super.doGet(request, response);
		} catch (final InternalBackEndException e) {
			e.printStackTrace();
			handleError(e, response);
			return;
		}
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doPost(final HttpServletRequest request, final HttpServletResponse response)
	        throws ServletException, IOException {
		try {
			/** Get the parameters */
			final Map<String, String> parameters = getParameters(request);

			/** Get the method code */
			final RequestCode code = requestCodeMap.get(parameters.get("code"));

			/** handle the request */
			if (code != RequestCode.CREATE) {
				handleError(new InternalBackEndException("PubSubRequestHandler.doGet(" + code + ") not exist!"),
				        response);
				return;
			}

			// TODO test if it works
			// switch (code) {
			// case CREATE :
			// break;
			// default :
			// handleError(new
			// InternalBackEndException("PubSubRequestHandler.doGet(" + code +
			// ") not exist!"),
			// response);
			// return;
			// }

			super.doGet(request, response);
		} catch (final InternalBackEndException e) {
			e.printStackTrace();
			handleError(e, response);
			return;
		}
	}

}
