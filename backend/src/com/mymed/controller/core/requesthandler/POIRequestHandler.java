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
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.Gson;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.geolocation.GeoLocationManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.geolocation.MSearchBean;

/**
 * Servlet implementation class POIRequestHandler
 */
@WebServlet("/POIRequestHandler")
public class POIRequestHandler extends AbstractRequestHandler {

  /**
   * Generated serial version.
   */
  private static final long serialVersionUID = 8872837258822221066L;

  /**
   * JSON 'type' attribute.
   */
  private static final String JSON_TYPE = JSON.get("json.type");

  /**
   * JSON 'latitude' attribute.
   */
  private static final String JSON_LAT = JSON.get("json.latitude");

  /**
   * JSON 'longitude' attribute.
   */
  private static final String JSON_LON = JSON.get("json.longitude");

  /**
   * JSON 'pois' attribute.
   */
  private static final String JSON_POI = JSON.get("json.poi");

  /**
   * JSON 'radius' attribute.
   */
  private static final String JSON_RADIUS = JSON.get("json.radius");

  private GeoLocationManager geoLocationManager;

  /**
   * @see HttpServlet#HttpServlet()
   */
  public POIRequestHandler() throws ServletException {
    super();
    try {
      geoLocationManager = new GeoLocationManager();
    } catch (final InternalBackEndException e) {
      throw new ServletException("GeoLocationManager is not accessible because: " + e.getMessage());
    }
  }

  /**
   * Convert into micro-degree.
   * 
   * @param coord
   *          the coordinate string as 1.2.3
   * @return the int representing the coordinate
   */
  private int convertDegreeToMicroDegree(final String coord) {
    final String[] digits = coord.split("\\.");
    String result = digits[0];
    int i = 0;
    while (i < digits[1].length() && i < 6) {
      result += digits[1].charAt(i);
      i++;
    }
    for (int j = i; j < 6; j++) {
      result += "0";
    }
    return Integer.parseInt(result);
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
      // GET THE PARAMETERS
      final Map<String, String> parameters = getParameters(request);
      final RequestCode code = requestCodeMap.get(parameters.get(JSON_CODE));
      String application, type, latitude, longitude, radius;

      // CHECK THE ACCESS TOKEN
      if (!parameters.containsKey(JSON_ACCESS_TKN)) {
        throw new InternalBackEndException("accessToken argument is missing!");
      } else {
        tokenValidation(parameters.get(JSON_ACCESS_TKN));
      }

      if (code == RequestCode.READ) {
        message.setMethod(JSON_CODE_READ);

        // CHECK THE PARAMETERS
        if ((application = parameters.get(JSON_APPLICATION)) == null) {
          throw new InternalBackEndException("missing application argument!");
        } else if ((type = parameters.get(JSON_TYPE)) == null) {
          throw new InternalBackEndException("missing type argument!");
        } else if ((longitude = parameters.get(JSON_LON)) == null) {
          throw new InternalBackEndException("missing longitude argument!");
        } else if ((latitude = parameters.get(JSON_LAT)) == null) {
          throw new InternalBackEndException("missing latitude argument!");
        } else if ((radius = parameters.get(JSON_RADIUS)) == null) {
          throw new InternalBackEndException("missing radius argument!");
        }

        // GET THE POIs
        final List<MSearchBean> pois = geoLocationManager.read(application, type, convertDegreeToMicroDegree(latitude),
            convertDegreeToMicroDegree(longitude), Integer.parseInt(radius), true);
        message.setDescription("POIs successfully read!");
        final Gson gson = new Gson();
        message.addData(JSON_POI, gson.toJson(pois));
      } else {
        throw new InternalBackEndException("POIRequestHandler(" + code + ") not exist!");
      }
    } catch (final AbstractMymedException e) {
      LOGGER.debug("Error in doGet operation", e);
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
      // GET THE PARAMETERS
      final Map<String, String> parameters = getParameters(request);
      final RequestCode code = requestCodeMap.get(parameters.get(JSON_CODE));
      String application, type, user, longitude, latitude, value;

      // CHECK THE ACCESS TOKEN
      if (!parameters.containsKey(JSON_ACCESS_TKN)) {
        throw new InternalBackEndException("accessToken argument is missing!");
      } else {
        tokenValidation(parameters.get(JSON_ACCESS_TKN));
      }

      if (code == RequestCode.CREATE) {
        message.setMethod(JSON_CODE_CREATE);

        // CHECK THE PARAMETERS
        if ((application = parameters.get(JSON_APPLICATION)) == null) {
          throw new InternalBackEndException("missing application argument!");
        } else if ((type = parameters.get(JSON_TYPE)) == null) {
          throw new InternalBackEndException("missing type argument!");
        } else if ((user = parameters.get(JSON_USER)) == null) {
          throw new InternalBackEndException("missing user argument!");
        } else if ((longitude = parameters.get(JSON_LON)) == null) {
          throw new InternalBackEndException("missing longitude argument!");
        } else if ((latitude = parameters.get(JSON_LAT)) == null) {
          throw new InternalBackEndException("missing latitude argument!");
        } else if ((value = parameters.get(JSON_VALUE)) == null) {
          throw new InternalBackEndException("missing value argument!");
        }

        // CREATE THE NEW POI
        geoLocationManager.create(application, type, user, convertDegreeToMicroDegree(latitude),
            convertDegreeToMicroDegree(longitude), value, 0);

        message.setDescription("POIs successfully created!");
      } else {
        throw new InternalBackEndException("POIRequestHandler(" + code + ") not exist!");
      }
    } catch (final AbstractMymedException e) {
      LOGGER.debug("Error in doPost operation", e);
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    }

    printJSonResponse(message, response);
  }
}