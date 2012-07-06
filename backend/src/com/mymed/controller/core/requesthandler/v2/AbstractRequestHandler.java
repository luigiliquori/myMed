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
package com.mymed.controller.core.requesthandler.v2;

import java.io.IOException;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.Part;

import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;


public abstract class AbstractRequestHandler extends com.mymed.controller.core.requesthandler.AbstractRequestHandler {
    /**
	 * 
	 */
	private static final long serialVersionUID = 5219022603572550553L;

	/**
     * Serial version ID.
     */
	
	@Override
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
				parameters.put(paramName, paramValues[0]);
				LOGGER.info("{}: {}", paramName, paramValues[0]);
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

}
