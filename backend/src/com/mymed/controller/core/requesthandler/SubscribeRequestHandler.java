package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.pubsub.PubSubManager;
import com.mymed.model.data.user.MUserBean;

/**
 * Servlet implementation class PubSubRequestHandler
 */
public class SubscribeRequestHandler extends AbstractRequestHandler {
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
	public SubscribeRequestHandler() throws ServletException {
		super();
		try {
			pubsubManager = new PubSubManager();
		} catch (final InternalBackEndException e) {
			throw new ServletException(
					"PubSubManager is not accessible because: "
							+ e.getMessage());
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
	protected void doGet(final HttpServletRequest request,
			final HttpServletResponse response) throws ServletException,
			IOException {
		try {
			/** Get the parameters */
			final Map<String, String> parameters = getParameters(request);

			/** Get the method code */
			final RequestCode code = requestCodeMap.get(parameters.get("code"));

			/** handle the request */
			switch (code) {
			case READ: 
				break;
			default:
				handleError(new InternalBackEndException(
						"DHTRequestHandler.doGet(" + code + ") not exist!"),
						response);
				return;
			}
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
	protected void doPost(final HttpServletRequest request,
			final HttpServletResponse response) throws ServletException,
			IOException {
		try {
			/** Get the parameters */
			final Map<String, String> parameters = getParameters(request);

			/** Get the method code */
			final RequestCode code = requestCodeMap.get(parameters.get("code"));

			/** handle the request */
			String application, predicate, user;
			switch (code) {
			case CREATE:
				if ((application = parameters.get("application")) == null) {
					handleError(new InternalBackEndException(
							"missing application argument!"), response);
					return;
				} else if ((predicate = parameters.get("predicate")) == null) {
					handleError(new InternalBackEndException(
							"missing predicate argument!"), response);
					return;
				} else if ((user = parameters.get("user")) == null) {
					handleError(new InternalBackEndException(
							"missing user argument!"), response);
					return;
				}
				try {
					// Deserialize user
					MUserBean userBean = getGson().fromJson(user,
							MUserBean.class);

					// Create the new entry
					pubsubManager.create(application, predicate, userBean);
					setResponseText("predicate subscribed");
				} catch (JsonSyntaxException e) {
					handleError(new InternalBackEndException(
							"jSon format is not valid"), response);
				} catch (final IOBackEndException e) {
					handleError(e, response);
				}
				break;
			default:
				handleError(new InternalBackEndException(
						"PubSubRequestHandler.doGet(" + code + ") not exist!"),
						response);
				return;
			}
			super.doGet(request, response);
		} catch (final InternalBackEndException e) {
			e.printStackTrace();
			handleError(e, response);
			return;
		}
	}

}
