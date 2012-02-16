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
import java.io.PrintWriter;
import java.io.UnsupportedEncodingException;
import java.net.URLDecoder;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.Part;

import ch.qos.logback.classic.Logger;

import com.google.gson.Gson;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.session.SessionManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.properties.IProperties;
import com.mymed.properties.PropType;
import com.mymed.properties.PropertiesManager;
import com.mymed.utils.MLogger;

public abstract class AbstractRequestHandler extends HttpServlet {
  /* --------------------------------------------------------- */
  /* Attributes */
  /* --------------------------------------------------------- */
  private static final long serialVersionUID = 1L;

  // The default logger for all the RequestHandler that extends this class.
  protected static final Logger LOGGER = MLogger.getLogger();

  /**
   * The default properties manager.
   */
  private static final PropertiesManager PROPERTIES = PropertiesManager.getInstance();

  /**
   * Values for the JSON attributes.
   */
  protected static final IProperties JSON = PROPERTIES.getManager(PropType.JSON);

  /**
   * Default string encoding.
   */
  protected static final String ENCODING = PROPERTIES.getManager(PropType.GENERAL).get("general.string.encoding");

  /**
   * The default social network ID.
   */
  protected static final String SOCIAL_NET_ID = PROPERTIES.getManager(PropType.GENERAL)
      .get("general.social.network.id");

  /**
   * The default social network name.
   */
  protected static final String SOCIAL_NET_NAME = PROPERTIES.getManager(PropType.GENERAL).get(
      "general.social.network.name");

  /**
   * JSON 'code' attribute.
   */
  protected static final String JSON_CODE = JSON.get("json.code");

  /**
   * JSON 'data' attribute.
   */
  protected static final String JSON_DATA = JSON.get("json.data");

  /**
   * JSON 'user' attribute.
   */
  protected static final String JSON_USER = JSON.get("json.user");

  /**
   * JSON 'value' attribute.
   */
  protected static final String JSON_VALUE = JSON.get("json.value");

  /**
   * JSON 'accessToken' attribute.
   */
  protected static final String JSON_ACCESS_TKN = JSON.get("json.accesstoken");

  /**
   * JSON 'application' attribute.
   */
  protected static final String JSON_APPLICATION = JSON.get("json.application");

  /**
   * JSON 'create' value.
   */
  protected static final String JSON_CODE_CREATE = JSON.get("json.code.create");

  /**
   * JSON 'read' value.
   */
  protected static final String JSON_CODE_READ = JSON.get("json.code.read");

  /**
   * JSON 'update' value.
   */
  protected static final String JSON_CODE_UPDATE = JSON.get("json.code.update");

  /**
   * JSON 'delete' value.
   */
  protected static final String JSON_CODE_DELETE = JSON.get("json.code.delete");

  /** Request code Map */
  protected static final Map<String, RequestCode> REQUEST_CODE_MAP = new HashMap<String, RequestCode>(4);

  static {
    for (final RequestCode code : RequestCode.values()) {
      REQUEST_CODE_MAP.put(code.getCode(), code);
    }
  }

  /** Request codes */
  protected enum RequestCode {
    // C.R.U.D
    CREATE("0"),
    READ("1"),
    UPDATE("2"),
    DELETE("3");

    private final String code;

    RequestCode(final String code) {
      this.code = code;
    }

    /**
     * @return the code number
     */
    public String getCode() {
      return code;
    }
  }

  /** Google library to handle jSon request */
  private Gson gson;

  /** The response/feedback printed */
  private String responseText = null;

  protected AbstractRequestHandler() {
    super();

    gson = new Gson();
  }

  /**
   * @return the parameters of an HttpServletRequest
   */
  protected Map<String, String> getParameters(final HttpServletRequest request) throws AbstractMymedException {

    // see multipart/form-data Request
    if (request.getContentType() != null) {
      try {
        if (request.getContentType().matches("multipart/form-data")) {
          LOGGER.info("multipart/form-data REQUEST");
          for (final Part part : request.getParts()) {
            LOGGER.info("PART {} ", part);
          }
          throw new InternalBackEndException("multi-part is not yet implemented...");
        }
      } catch (final IOException e) {
        throw new InternalBackEndException(e);
      } catch (final ServletException e) {
        throw new InternalBackEndException(e);
      }
    }

    final Map<String, String> parameters = new HashMap<String, String>();
    final Enumeration<String> paramNames = request.getParameterNames();

    while (paramNames.hasMoreElements()) {
      final String paramName = paramNames.nextElement();
      final String[] paramValues = request.getParameterValues(paramName);

      // all the parameter should be atomic
      if (paramValues.length >= 1) {
        try {
          /*
           * Since this comes in like an HTTP request, we might have non ASCII
           * chars in the parameters, so it is better to decode them. If there
           * are only ASCII char, it is safe since ASCII < UTF-8.
           */
          final String value = URLDecoder.decode(paramValues[0], ENCODING);
          parameters.put(paramName, value);

          LOGGER.info("{}: {}", paramName, value);
        } catch (final UnsupportedEncodingException ex) {
          LOGGER.debug("Error decoding string from '{}'", ENCODING, ex);
        }
      }
    }

    if (!parameters.containsKey(JSON_CODE)) {
      throw new InternalBackEndException("code argument is missing!");
    }

    if (REQUEST_CODE_MAP.get(parameters.get(JSON_CODE)) == null) {
      throw new InternalBackEndException("code argument is not well formated");
    }

    return parameters;
  }

  /**
   * Print the server response in a jSon format
   * 
   * @param message
   * @param response
   */
  protected void printJSonResponse(final JsonMessage message, final HttpServletResponse response) {
    response.setStatus(message.getStatus());
    responseText = message.toString();
    printResponse(response);
  }

  /**
   * Check that the access token parameters has been provided, and verifies it.
   * 
   * @param parameters
   *          the parameters where to check
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  protected void checkToken(final Map<String, String> parameters) throws InternalBackEndException, IOBackEndException {
    if (!parameters.containsKey(JSON_ACCESS_TKN)) {
      throw new InternalBackEndException("access token argument is missing!");
    } else {
      validateToken(parameters.get(JSON_ACCESS_TKN));
    }
  }

  /**
   * Validate an access token
   * 
   * @param accesstoken
   *          the access token to validate
   * @throws InternalBackEndException
   * @throws IOBackEndException
   */
  private void validateToken(final String accessToken) throws InternalBackEndException, IOBackEndException {
    new SessionManager().read(accessToken);
  }

  /**
   * Print the server response
   * 
   * @param response
   * @throws IOException
   */
  private void printResponse(final HttpServletResponse response) {
    if (responseText != null) {
      response.setContentType("text/plain;charset=UTF-8");
      PrintWriter out;

      try {
        out = response.getWriter();

        LOGGER.info("Response sent:\n {}", responseText);

        out.print(responseText);
        out.close();
        responseText = null; // NOPMD to avoid code check warnings
      } catch (final IOException e) {
        LOGGER.info("IOException: {}", e.getMessage());
        LOGGER.debug("Error in printResponse()", e);
      }
    }
  }

  public Gson getGson() {
    return gson;
  }

  public void setGson(final Gson gson) {
    this.gson = gson;
  }

  public String getResponseText() {
    return responseText;
  }
}
