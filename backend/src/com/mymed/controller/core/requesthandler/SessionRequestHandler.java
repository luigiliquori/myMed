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
    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = -1249666546771611959L;
    private SessionManager sessionManager;
    private ProfileManager profileManager;

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
            LOGGER.debug("Cannot access session manager", e);
            throw new ServletException("SessionManager is not accessible because: " + e.getMessage()); // NOPMD
        }
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.controller.core.requesthandler.AbstractRequestHandler#doGet
     * (javax.servlet.http.HttpServletRequest, javax.servlet.http.HttpServletResponse)
     */
    @Override
    protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessage message = new JsonMessage(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
            final String accessToken = parameters.get(JSON_ACCESS_TKN);
            final String socialNetwork = parameters.get("socialNetwork");

            if (accessToken == null) {
                throw new InternalBackEndException("accessToken argument missing!");
            }

            if (socialNetwork == null) {
                throw new InternalBackEndException("socialNetwork argument missing!");
            }

            switch (code) {
                case READ :
                    message.setMethod(JSON_CODE_READ);
                    final MSessionBean session = sessionManager.read(accessToken);
                    message.setDescription("Session avaible");
                    final MUserBean userBean = profileManager.read(session.getUser());
                    message.addData(JSON_USER, getGson().toJson(userBean));
                    break;
                case DELETE :
                    message.setMethod(JSON_CODE_DELETE);
                    sessionManager.delete(accessToken);
                    message.setDescription("Session deleted -> LOGOUT");
                    LOGGER.info("Session {} deleted -> LOGOUT", accessToken);
                    break;
                default :
                    throw new InternalBackEndException("SessionRequestHandler.doGet(" + code + ") not exist!");
            }

        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doGet", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.controller.core.requesthandler.AbstractRequestHandler#doPost
     * (javax.servlet.http.HttpServletRequest, javax.servlet.http.HttpServletResponse)
     */
    @Override
    protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException {
        final JsonMessage message = new JsonMessage(200, this.getClass().getName());

        try {
            final Map<String, String> parameters = getParameters(request);
            final RequestCode code = REQUEST_CODE_MAP.get(parameters.get(JSON_CODE));
            final String accessToken = parameters.get(JSON_ACCESS_TKN);
            final String userID = parameters.get("userID");

            if (accessToken == null) {
                throw new InternalBackEndException("accessToken argument missing!");
            }

            switch (code) {
                case CREATE : // FOR FACEBOOK - TODO Check Security!
                    message.setMethod(JSON_CODE_CREATE);
                    if (userID == null) {
                        throw new InternalBackEndException("userID argument missing!");
                    }

                    final MUserBean userBean = profileManager.read(userID);

                    // Create a new session
                    final MSessionBean sessionBean = new MSessionBean();
                    sessionBean.setIp(request.getRemoteAddr());
                    sessionBean.setUser(userBean.getId());
                    sessionBean.setCurrentApplications("");
                    sessionBean.setP2P(false);
                    sessionBean.setTimeout(System.currentTimeMillis());
                    sessionBean.setAccessToken(accessToken);
                    sessionBean.setId(accessToken);
                    sessionManager.create(sessionBean);

                    // Update the profile with the new session
                    userBean.setSession(accessToken);
                    profileManager.update(userBean);

                    message.setDescription("session created");
                    LOGGER.info("Session {} created -> LOGIN", accessToken);

                    final StringBuffer urlBuffer = new StringBuffer(250);
                    urlBuffer.append("http://");
                    urlBuffer.append(SERVER_URI);
                    urlBuffer.append("?socialNetwork=");
                    urlBuffer.append(userBean.getSocialNetworkName());
                    urlBuffer.trimToSize();

                    message.addData("url", urlBuffer.toString());
                    message.addData(JSON_ACCESS_TKN, accessToken);
                    break;
                case UPDATE :
                    // message.setMethod("UPDATE");
                    // try {
                    // if (session == null) {
                    // throw new
                    // InternalBackEndException("session argument missing!");
                    //
                    // }
                    // sessionBean = getGson().fromJson(session,
                    // MSessionBean.class);
                    // sessionManager.update(sessionBean);
                    // } catch (final JsonSyntaxException e) {
                    // throw new InternalBackEndException(
                    // "user jSon format is not valid");
                    // }
                    break;
                default :
                    throw new InternalBackEndException("ProfileRequestHandler.doPost(" + code + ") not exist!");
            }

        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doPost", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }
}
