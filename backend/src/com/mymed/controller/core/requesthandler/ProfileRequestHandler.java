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

    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = -7350632718579822436L;

    /**
     * JSON 'id' attribute.
     */
    private static final String JSON_ID = JSON.get("json.id");

    /**
     * JSON 'profile' attribute.
     */
    private static final String JSON_PROFILE = JSON.get("json.profile");

    private ProfileManager profileManager;

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

    /*
     * (non-Javadoc)
     * @see
     * com.mymed.controller.core.requesthandler.AbstractRequestHandler#doGet
     * (javax.servlet.http.HttpServletRequest,
     * javax.servlet.http.HttpServletResponse)
     */
    @Override
    protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessage message = new JsonMessage(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            // Check the access token
            checkToken(parameters);

            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
            final String id = parameters.get(JSON_ID);

            if (id == null) {
                throw new InternalBackEndException("missing id argument!");
            }

            switch (code) {
                case READ :
                    message.setMethod(JSON_CODE_READ);
                    final MUserBean userBean = profileManager.read(id);
                    message.addData(JSON_USER, getGson().toJson(userBean));
                    break;
                case DELETE :
                    message.setMethod(JSON_CODE_DELETE);
                    profileManager.delete(id);
                    message.setDescription("User " + id + " deleted");
                    LOGGER.info("User '{}' deleted", id);
                    break;
                default :
                    throw new InternalBackEndException("ProfileRequestHandler.doGet(" + code + ") not exist!");
            }

        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doGet operation", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }

    /*
     * (non-Javadoc)
     * @see
     * com.mymed.controller.core.requesthandler.AbstractRequestHandler#doPost
     * (javax.servlet.http.HttpServletRequest,
     * javax.servlet.http.HttpServletResponse)
     */
    @Override
    protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessage message = new JsonMessage(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            // Check the access token
            checkToken(parameters);

            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
            final String user = parameters.get(JSON_USER);

            if (user == null) {
                throw new InternalBackEndException("missing user argument!");
            }

            switch (code) {
                case CREATE :
                    message.setMethod(JSON_CODE_CREATE);
                    try {
                        LOGGER.info("User:\n", user);
                        MUserBean userBean = getGson().fromJson(user, MUserBean.class);
                        LOGGER.info("Trying to create a new user:\n {}", userBean.toString());
                        userBean = profileManager.create(userBean);
                        LOGGER.info("User created!");
                        message.setDescription("User created!");
                        message.addData(JSON_PROFILE, getGson().toJson(userBean));
                    } catch (final JsonSyntaxException e) {
                        throw new InternalBackEndException("user jSon format is not valid");
                    }
                    break;
                case UPDATE :
                    message.setMethod(JSON_CODE_UPDATE);
                    try {
                        MUserBean userBean = getGson().fromJson(user, MUserBean.class);
                        LOGGER.info("Trying to update user:\n {}", userBean.toString());
                        userBean = profileManager.update(userBean);
                        message.addData(JSON_PROFILE, getGson().toJson(userBean));
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
            LOGGER.debug("Error in doPost operation", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }
}
