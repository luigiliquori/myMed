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
import com.mymed.controller.core.manager.pubsub.PubSubManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.user.MUserBean;

/**
 * Servlet implementation class PubSubRequestHandler
 */
public class SubscribeRequestHandler extends AbstractRequestHandler {
  private static final long serialVersionUID = 1L;

  private final PubSubManager pubsubManager;

  public SubscribeRequestHandler() throws ServletException {
    super();
    pubsubManager = new PubSubManager();
  }

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
      final RequestCode code = REQUEST_CODE_MAP.get(parameters.get("code"));

      // accessToken
      if (!parameters.containsKey("accessToken")) {
        throw new InternalBackEndException("accessToken argument is missing!");
      } else {
        tokenValidation(parameters.get("accessToken")); // Security Validation
      }

      switch (code) {
        case READ :
        case DELETE :
        default :
          throw new InternalBackEndException("SubscribeRequestHandler.doGet(" + code + ") not exist!");
      }

    } catch (final AbstractMymedException e) {
      LOGGER.info("Error in doGet operation");
      LOGGER.debug("Error in doGet operation", e.getCause());
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
      final RequestCode code = REQUEST_CODE_MAP.get(parameters.get("code"));
      String application, predicate, user;

      // accessToken
      if (!parameters.containsKey("accessToken")) {
        throw new InternalBackEndException("accessToken argument is missing!");
      } else {
        tokenValidation(parameters.get("accessToken")); // Security Validation
      }

      switch (code) {
        case CREATE :
          if ((application = parameters.get("application")) == null) {
            throw new InternalBackEndException("missing application argument!");
          } else if ((predicate = parameters.get("predicate")) == null) {
            throw new InternalBackEndException("missing predicate argument!");
          } else if ((user = parameters.get("user")) == null) {
            throw new InternalBackEndException("missing user argument!");
          }
          try {
            final MUserBean userBean = getGson().fromJson(user, MUserBean.class);

            pubsubManager.create(application, predicate, userBean);
            LOGGER.info("predicate subscribed: " + predicate);
            message.setDescription("predicate subscribed: " + predicate);

          } catch (final JsonSyntaxException e) {
            throw new InternalBackEndException("jSon format is not valid");
          }
          break;
        default :
          throw new InternalBackEndException("SubscribeRequestHandler.doGet(" + code + ") not exist!");
      }

    } catch (final AbstractMymedException e) {
      LOGGER.info("Error in doGet operation");
      LOGGER.debug("Error in doGet operation", e.getCause());
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    }

    printJSonResponse(message, response);
  }
}
