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
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.session.SessionManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.session.MSessionBean;
import com.mymed.model.data.user.MUserBean;

/**
 * Servlet implementation class SessionRequestHandler
 */
public class SessionRequestHandler extends AbstractRequestHandler {
  /* --------------------------------------------------------- */
  /* Attributes */
  /* --------------------------------------------------------- */
  private static final long serialVersionUID = 1L;

  private static final String SOCIAL_NETWORK_NAME = "myMed";

  private SessionManager sessionManager;
  private ProfileManager profileManager;

  /* --------------------------------------------------------- */
  /* Constructors */
  /* --------------------------------------------------------- */
  /**
   * @throws ServletException
   * @see HttpServlet#HttpServlet()
   */
  public SessionRequestHandler() throws ServletException {
    super();

    try {
      sessionManager = new SessionManager();
      profileManager = new ProfileManager();
    } catch (final InternalBackEndException e) {
      throw new ServletException("SessionManager is not accessible because: " + e.getMessage());
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

    final JsonMessage message = new JsonMessage(200, this.getClass().getName());

    try {
      final Map<String, String> parameters = getParameters(request);
      final RequestCode code = requestCodeMap.get(parameters.get("code"));
      final String accessToken = parameters.get("accessToken");
      final String socialNetwork = parameters.get("socialNetwork");

      if (accessToken == null) {
        throw new InternalBackEndException("accessToken argument missing!");
      }

      if (socialNetwork == null) {
        throw new InternalBackEndException("socialNetwork argument missing!");
      }

      switch (code) {
        case READ :
          message.setMethod("READ");
          if (socialNetwork.equals(SOCIAL_NETWORK_NAME)) {
            final MSessionBean session = sessionManager.read(accessToken);
            message.setDescription("Session avaible");
            final MUserBean userBean = profileManager.read(session.getUser());
            message.addData("user", getGson().toJson(userBean));
          } else if (socialNetwork.equals("facebook")) {
            throw new InternalBackEndException("not implemented yet...");
          } else {
            throw new InternalBackEndException("socialNetwork not recognized!");
          }
          break;
        case DELETE :
          message.setMethod("DELETE");
          if (socialNetwork.equals(SOCIAL_NETWORK_NAME)) {
            sessionManager.delete(accessToken);
            message.setDescription("Session deleted -> LOGOUT");
            LOGGER.info("Session {} deleted -> LOGOUT", accessToken);
          } else if (socialNetwork.equals("facebook")) {
            throw new InternalBackEndException("facebook logout not implemented yet...");
          } else {
            throw new InternalBackEndException("socialNetwork not recognized!");
          }
          break;
        default :
          throw new InternalBackEndException("SessionRequestHandler.doGet(" + code + ") not exist!");
      }
    } catch (final AbstractMymedException e) {
      LOGGER.info("Error in doGet");
      LOGGER.debug("Error in doGet", e.getCause());
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    }

    printJSonResponse(message, response);
  }
  /**
   * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse
   *      response)
   */
  @Override
  protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException,
      IOException {

    final JsonMessage message = new JsonMessage(200, this.getClass().getName());

    try {
      final Map<String, String> parameters = getParameters(request);
      final RequestCode code = requestCodeMap.get(parameters.get("code"));
      final String accessToken = parameters.get("accessToken");
      final String session = parameters.get("session");

      if (accessToken == null) {
        throw new InternalBackEndException("accessToken argument missing!");
      }

      switch (code) {
        case UPDATE :
          message.setMethod("UPDATE");
          try {
            if (session == null) {
              throw new InternalBackEndException("session argument missing!");
            }

            final MSessionBean sessionBean = getGson().fromJson(session, MSessionBean.class);
            sessionManager.update(sessionBean);
          } catch (final JsonSyntaxException e) {
            throw new InternalBackEndException("session jSon format is not valid");
          }
          break;
        default :
          throw new InternalBackEndException("ProfileRequestHandler.doPost(" + code + ") does not exist!");
      }
    } catch (final AbstractMymedException e) {
      LOGGER.info("Error in doPost");
      LOGGER.debug("Error in doPost", e.getCause());
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    }

    printJSonResponse(message, response);
  }
}
