package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.lang.reflect.Type;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.pubsub.PubSubManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MLogger;

/**
 * Servlet implementation class PubSubRequestHandler
 */
public class PublishRequestHandler extends AbstractRequestHandler {
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
	public PublishRequestHandler() throws ServletException {
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

		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = requestCodeMap.get(parameters.get("code"));

			switch (code) {
			case READ:
			case DELETE:
			default:
				throw new InternalBackEndException("PublishRequestHandler(" + code + ") not exist!");
			}

		} catch (final AbstractMymedException e) {
			MLogger.getLog().info("Error in doGet operation");
			MLogger.getDebugLog().debug("Error in doGet operation", e.getCause());
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} 

		printJSonResponse(message, response);
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doPost(final HttpServletRequest request, final HttpServletResponse response)
			throws ServletException, IOException {

		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = requestCodeMap.get(parameters.get("code"));
			String application, predicate, user, data;

			switch (code) {
			case CREATE : // PUT
				message.setMethod("CREATE");
				if ((application = parameters.get("application")) == null) {
					throw new InternalBackEndException("missing application argument!");
				} else if ((predicate = parameters.get("predicate")) == null) {
					throw new InternalBackEndException("missing predicate argument!");
				} else if ((user = parameters.get("user")) == null) {
					throw new InternalBackEndException("missing user argument!");
				} else if ((data = parameters.get("data")) == null) {
					throw new InternalBackEndException("missing data argument!");
				}
				
				try {
					final MUserBean userBean = getGson().fromJson(user, MUserBean.class);
					final Type type = new TypeToken<List<MDataBean>>(){}.getType();
					final List<MDataBean> dataList = getGson().fromJson(data, type);

					MLogger.getLog().info("predicate to publish: " + predicate);
					pubsubManager.create(application, predicate, userBean, dataList);
					MLogger.getLog().info("predicate published!");
					message.setDescription("predicate published: " + predicate);
				} catch (final JsonSyntaxException e) {
					throw new InternalBackEndException("jSon format is not valid");
				}
				break;
			default :
				throw new InternalBackEndException("PublishRequestHandler(" + code + ") not exist!");
			}

		} catch (final AbstractMymedException e) {
			MLogger.getLog().info("Error in doPost operation");
			MLogger.getDebugLog().debug("Error in doPost operation", e.getCause());
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} 

		printJSonResponse(message, response);
	}
}
