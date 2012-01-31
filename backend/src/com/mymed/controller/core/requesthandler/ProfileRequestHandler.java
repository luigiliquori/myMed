/*
 * Copyright 2012 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
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

      // accessToken
      if (!parameters.containsKey("accessToken")) {
        throw new InternalBackEndException("accessToken argument is missing!");
      } else {
        tokenValidation(parameters.get("accessToken")); // Security Validation
      }

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
      LOGGER.debug("Error in doRequest operation", e);
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

      // accessToken
      if (!parameters.containsKey("accessToken")) {
        throw new InternalBackEndException("accessToken argument is missing!");
      } else {
        tokenValidation(parameters.get("accessToken")); // Security Validation
      }

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
            message.addData("profile", getGson().toJson(userBean));
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
      LOGGER.debug("Error in doRequest operation", e);
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    }

    printJSonResponse(message, response);
  }
}
