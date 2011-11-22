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
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.user.MUserBean;

/**
 * Servlet implementation class UsersRequestHandler
 */
public class ProfileRequestHandler extends AbstractRequestHandler {
  /* --------------------------------------------------------- */
  /* Attributes */
  /* --------------------------------------------------------- */
  private static final long serialVersionUID = 1L;

  private ProfileManager profileManager;

  /* --------------------------------------------------------- */
  /* Constructors */
  /* --------------------------------------------------------- */
  /**
   * @throws ServletException
   * @see HttpServlet#HttpServlet()
   */
  public ProfileRequestHandler() throws ServletException {
    super();

    try {
      profileManager = new ProfileManager();
    } catch (final InternalBackEndException e) {
      throw new ServletException("ProfileManager is not accessible because: " + e.getMessage());
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
      final String id = parameters.get("id");
      if (id == null) {
        throw new InternalBackEndException("missing id argument!");
      }

      switch (code) {
        case READ :
          message.setMethod("READ");
          final MUserBean userBean = profileManager.read(id);
          message.addData("user", getGson().toJson(userBean));
          break;
        case DELETE :
          message.setMethod("DELETE");
          profileManager.delete(id);
          message.setDescription("User " + id + " deleted");
          LOGGER.info("User '{}' deleted", id);
          break;
        default :
          throw new InternalBackEndException("ProfileRequestHandler.doGet(" + code + ") not exist!");
      }

    } catch (final AbstractMymedException e) {
      LOGGER.info("Error in doRequest operation");
      LOGGER.debug("Error in doRequest operation", e.getCause());
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
  protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException,
      IOException {

    final JsonMessage message = new JsonMessage(200, this.getClass().getName());

    try {
      final Map<String, String> parameters = getParameters(request);
      final RequestCode code = requestCodeMap.get(parameters.get("code"));
      final String user = parameters.get("user");
      if (user == null) {
        throw new InternalBackEndException("missing user argument!");
      }

      switch (code) {
        case CREATE :
          message.setMethod("CREATE");
          try {
            LOGGER.info("User:\n", user);
            MUserBean userBean = getGson().fromJson(user, MUserBean.class);
            LOGGER.info("Trying to create a new user:\n {}", userBean.toString());
            userBean = profileManager.create(userBean);
            LOGGER.info("User created!");
            message.setDescription("User created!");
            message.addData("user", getGson().toJson(userBean));
          } catch (final JsonSyntaxException e) {
            throw new InternalBackEndException("user jSon format is not valid");
          }
          break;
        case UPDATE :
          message.setMethod("UPDATE");
          try {
            MUserBean userBean = getGson().fromJson(user, MUserBean.class);
            LOGGER.info("Trying to update user:\n {}", userBean.toString());
            userBean = profileManager.update(userBean);
            message.addData("profile", getGson().toJson(userBean));
            message.setDescription("User updated!");
            LOGGER.info("User updated!");
          } catch (final JsonSyntaxException e) {
            throw new InternalBackEndException("user jSon format is not valid");
          }
          break;
        default :
          throw new InternalBackEndException("ProfileRequestHandler.doPost(" + code + ") not exist!");
      }

    } catch (final AbstractMymedException e) {
      LOGGER.info("Error in doRequest operation");
      LOGGER.debug("Error in doRequest operation", e.getCause());
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    }

    printJSonResponse(message, response);
  }
}
