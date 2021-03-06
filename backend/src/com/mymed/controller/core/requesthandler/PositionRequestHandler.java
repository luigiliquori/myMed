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

import static com.mymed.utils.GsonUtils.gson;

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
import com.mymed.controller.core.requesthandler.message.JsonMessageOut;
import com.mymed.model.data.user.MPositionBean;

/**
 * Servlet implementation class PositionRequestHandler
 */
@WebServlet("/PositionRequestHandler")
public class PositionRequestHandler extends AbstractRequestHandler {

    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = -1545024147681413957L;

    private PositionManager positionManager;

    /**
     * JSON 'position' attribute.
     */
    private static final String JSON_POSITION = JSON.get("json.position");

    /**
     * JSON 'userID' attribute.
     */
    private static final String JSON_USER_ID = JSON.get("json.user.id");

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
    protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessageOut<Object> message = new JsonMessageOut<Object>(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            // Check the access token
            checkToken(parameters);

            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
            final String userID = parameters.get(JSON_USER_ID);

            if (userID == null) {
                throw new InternalBackEndException("userID argument missing!");
            }

            if (code.equals(RequestCode.READ)) {
                message.setMethod(JSON_CODE_READ);
                final MPositionBean position = positionManager.read(userID);
                message.addData(JSON_POSITION, gson.toJson(position));
                message.addDataObject(JSON_POSITION, position);
            } else {
                throw new InternalBackEndException("PositionRequestHandler.doGet(" + code + ") not exist!");
            }
        } catch (final AbstractMymedException e) {
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
    protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessageOut<Object> message = new JsonMessageOut<Object>(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            // Check the access token
            checkToken(parameters);

            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
            final String position = parameters.get(JSON_POSITION);

            if (position == null) {
                throw new InternalBackEndException("missing position argument!");
            }

            if (code.equals(RequestCode.UPDATE)) {
                message.setMethod(JSON_CODE_UPDATE);
                try {
                    final MPositionBean positionBean = gson.fromJson(position, MPositionBean.class);
                    LOGGER.info("Trying to update position:\n {}", positionBean.toString());
                    positionManager.update(positionBean);
                    message.setDescription("Position updated!");
                    LOGGER.info("Position updated!");
                } catch (final JsonSyntaxException e) {
                    throw new InternalBackEndException("position jSon format is not valid");
                }
            } else {
                throw new InternalBackEndException("PositionRequestHandler.doPost(" + code + ") not exist!");
            }
        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doPost operation", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }
}
