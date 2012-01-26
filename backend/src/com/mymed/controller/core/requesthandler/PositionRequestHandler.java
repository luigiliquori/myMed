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
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.position.PositionManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.user.MPositionBean;

/**
 * Servlet implementation class PositionRequestHandler
 */
@WebServlet("/PositionRequestHandler")
public class PositionRequestHandler extends AbstractRequestHandler {
  private static final long serialVersionUID = 1L;

  private PositionManager positionManager;

  /**
   * @see HttpServlet#HttpServlet()
   */
  public PositionRequestHandler() throws ServletException {
    super();

    try {
      positionManager = new PositionManager();
    } catch (final InternalBackEndException e) {
      throw new ServletException("PositionManager is not accessible because: " + e.getMessage());
    }
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
      final RequestCode code = requestCodeMap.get(parameters.get("code"));
      final String userID = parameters.get("userID");

      // accessToken
      if (parameters.get("accessToken") == null) {
        throw new InternalBackEndException("accessToken argument is missing!");
      } else {
        tokenValidation(parameters.get("accessToken")); // Security Validation
      }

      if (userID == null) {
        throw new InternalBackEndException("userID argument missing!");
      }

      switch (code) {
        case READ :
          message.setMethod("READ");
          final MPositionBean position = positionManager.read(userID);
          message.addData("position", getGson().toJson(position));
          break;
        default :
          throw new InternalBackEndException("PositionRequestHandler.doGet(" + code + ") not exist!");
      }

    } catch (final AbstractMymedException e) {
      LOGGER.info("Error in doGet");
      LOGGER.debug("Error in doGet", e);
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
      final String position = parameters.get("position");

      // accessToken
      if (!parameters.containsKey("accessToken")) {
        throw new InternalBackEndException("accessToken argument is missing!");
      } else {
        tokenValidation(parameters.get("accessToken")); // Security Validation
      }

      if (position == null) {
        throw new InternalBackEndException("missing position argument!");
      }

      switch (code) {

        case UPDATE :
          message.setMethod("UPDATE");
          try {
            final MPositionBean positionBean = getGson().fromJson(position, MPositionBean.class);
            LOGGER.info("Trying to update position:\n {}", positionBean.toString());
            positionManager.update(positionBean);
            message.setDescription("Position updated!");
            LOGGER.info("Position updated!");
          } catch (final JsonSyntaxException e) {
            throw new InternalBackEndException("position jSon format is not valid");
          }
          break;
        default :
          throw new InternalBackEndException("ProfileRequestHandler.doPost(" + code + ") not exist!");
      }
    } catch (final AbstractMymedException e) {
      LOGGER.info("Error in doPost operation");
      LOGGER.debug("Error in doPost operation", e);
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    }

    printJSonResponse(message, response);
  }
}
