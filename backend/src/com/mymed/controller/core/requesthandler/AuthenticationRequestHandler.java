package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.authentication.AuthenticationManager;
import com.mymed.controller.core.manager.authentication.IAuthenticationManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MLogger;

/**
 * Servlet implementation class AuthenticationRequestHandler
 */
public class AuthenticationRequestHandler extends AbstractRequestHandler {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	private IAuthenticationManager authenticationManager;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#HttpServlet()
	 */
	public AuthenticationRequestHandler() throws ServletException {
		super();

		try {
			authenticationManager = new AuthenticationManager();
		} catch (final InternalBackEndException e) {
			throw new ServletException("AuthenticationManager is not accessible because: " + e.getMessage());
		}
	}

	/* --------------------------------------------------------- */
	/* extends AbstractRequestHandler */
	/* --------------------------------------------------------- */
	@Override
	public void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException,
	IOException {

		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = requestCodeMap.get(parameters.get("code"));
			final String login = parameters.get("login");
			final String password = parameters.get("password");

			switch (code) {
			case READ :
				message.setMethod("READ");
				if (login == null || password == null) {
					throw new InternalBackEndException("missing argument!");
				} else {
					final MUserBean userBean = authenticationManager.read(login, password);
					message.addData("profile", getGson().toJson(userBean));
				}
				break;
			case DELETE :
				break;
			default :
				throw new InternalBackEndException("ProfileRequestHandler(" + code + ") not exist!");
			}
		} catch (final AbstractMymedException e) {
			MLogger.getLog().info("Error in doRequest operation");
			MLogger.getDebugLog().debug("Error in doRequest operation", e.getCause());
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} 

		printJSonResponse(message, response);
	}
	
	@Override
	public void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException,
	IOException {

		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = requestCodeMap.get(parameters.get("code"));
			final String authentication = parameters.get("authentication");

			switch (code) {
			case CREATE :
				message.setMethod("CREATE");
				final String user = parameters.get("user");
				if (user == null || authentication == null) {
					throw new InternalBackEndException("Missing user argument!");
				} else {
					try {
						MUserBean userBean = getGson().fromJson(user, MUserBean.class);
						userBean.setSocialNetworkID("MYMED");
						userBean.setSocialNetworkName("myMed");

						final MAuthenticationBean authenticationBean = getGson().fromJson(authentication,
								MAuthenticationBean.class);

						MLogger.getLog().info("Trying to create a new user:\n {}", userBean.toString());
						userBean = authenticationManager.create(userBean, authenticationBean);
						
						MLogger.getLog().info("User created");
						message.setDescription("User created");
						message.addData("profile", getGson().toJson(userBean));

						super.doPost(request, response);
					} catch (final JsonSyntaxException e) {
						throw new InternalBackEndException("User/Authentication jSon format is not valid");
					}
				}
				break;
			case UPDATE :
				final String id = parameters.get("id");
				if (id == null || authentication == null) {
					throw new InternalBackEndException("Missing id argument!");
				} else {
					try {
						final MAuthenticationBean authenticationBean = getGson().fromJson(authentication,
								MAuthenticationBean.class);

						MLogger.getLog().info("Trying to update authentication:\n {}", authenticationBean.toString());
						authenticationManager.update(id, authenticationBean);
						MLogger.getLog().info("Authentication updated!");

					} catch (final JsonSyntaxException e) {
						throw new InternalBackEndException("Authentication jSon format is not valid");
					}
				}
				break;
			default :
				throw new InternalBackEndException("ProfileRequestHandler(" + code + ") not exist!");
			}
		} catch (final AbstractMymedException e) {
			MLogger.getLog().info("Error in doRequest operation");
			MLogger.getDebugLog().debug("Error in doRequest operation", e.getCause());
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} 

		printJSonResponse(message, response);
	}
	
}
