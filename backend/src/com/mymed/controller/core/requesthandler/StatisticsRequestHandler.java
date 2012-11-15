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

import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.statistics.StatisticsManager;
import com.mymed.controller.core.requesthandler.message.JsonMessageOut;

/**
 * Servlet implementation class PositionRequestHandler
 */
@WebServlet("/StatisticsRequestHandler")
public class StatisticsRequestHandler extends AbstractRequestHandler {

    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = -1545024147681413957L;

    private StatisticsManager statisticsManager;

	
    /**
     * JSON 'application' attribute.
     */
    private static final String JSON_APPLICATION = JSON.get("json.application");
    
    /**
     * JSON 'method' attribute.
     */
    private static final String JSON_METHOD = "method";
    
    /**
     * JSON 'year' attribute.
     */
    private static final String JSON_YEAR = "year";
    
    /**
     * JSON 'month' attribute.
     */
    private static final String JSON_MONTH = "month";
    
    /**
     * JSON 'day' attribute.
     */
    private static final String JSON_DAY = "day";
    
    /**
     * JSON 'statistics' attribute.
     */
    private static final String JSON_STATISTICS = "statistics";
    
    /**
     * @see HttpServlet#HttpServlet()
     */
    public StatisticsRequestHandler() throws ServletException {
        super();

        try {
        	statisticsManager = new StatisticsManager();
        } catch (final InternalBackEndException e) {
            throw new ServletException("StatisticsManager is not accessible because: " + e.getMessage());
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
            String application = parameters.get(JSON_APPLICATION);
            String method = parameters.get(JSON_METHOD);
            String year = parameters.get(JSON_YEAR);
            String month = parameters.get(JSON_MONTH);
            
            if (application == null) {
            	application = StatisticsManager.ALL_ARG;
            }
            if (method == null) {
            	method = StatisticsManager.ALL_ARG;
            }

            if (code.equals(RequestCode.READ)) {
            	message.setMethod(JSON_CODE_READ);
            	Map<String, String> stats = statisticsManager.read(application, method, year, month);
//              message.addData(JSON_STATISTICS, gson.toJson(stats));
                message.addDataObject(JSON_STATISTICS, stats);
            } else {
                throw new InternalBackEndException("StatisticsRequestHandler.doGet(" + code + ") not exist!");
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

            throw new InternalBackEndException("StatisticsRequestHandler.doPost(" + code + ") not exist!");
            
        } catch (final AbstractMymedException e) {
            LOGGER.debug("Error in doPost operation", e);
            message.setStatus(e.getStatus());
            message.setDescription(e.getMessage());
        }

        printJSonResponse(message, response);
    }
}
