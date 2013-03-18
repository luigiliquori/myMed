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
package com.mymed.tests.unit.handler.utils;

import java.util.EnumSet;

import com.mymed.controller.core.requesthandler.message.JsonMessageOut;

/**
 * This is the enum class that stores all the fields of a normal JSON response.
 * <p>
 * The fields in here are the ones of a general JSON response as created by the {@link JsonMessageOut} class.
 * <p>
 * The format of the JSON message should be:<br>
 * <code>
  message {
    "status": 200|404|500|...,
    "handler": RequestHandlerID,
    "method": CREATE|READ|UPDATE|DELETE,
    "data": {
      "message": "String",
      "data_id_1": "String",
      "data_id_2": "String"
    }
  }
 * </code>
 * 
 * @author Milo Casagrande
 */
public enum MJson implements IJson {
    STATUS("status"),
    DESCRIPTION("description"),
    HANDLER("handler"),
    METHOD("method"),
    DATA("data"),
    DATAOBJECT("dataObject"),
    WARNING("warning");

    private String element;

    private MJson(final String element) {
        this.element = element;
    }

    /**
     * Check if JSON element is valid for the JSON format
     * 
     * @param str
     *            the element to check
     * @return true if the element is valid, false otherwise
     */
    public static boolean isValidElement(final String str) {
        boolean isValid = false;

        for (final MJson element : EnumSet.allOf(MJson.class)) {
            if (element.get().equals(str)) {
                isValid = true;
                break;
            }
        }

        return isValid;
    }

    /*
     * (non-Javadoc)
     * @see java.lang.Enum#toString()
     */
    @Override
    public String toString() {
        return element;
    }

    /*
     * (non-Javadoc)
     * @see com.mymed.tests.unit.handler.utils.IJson#get()
     */
    @Override
    public String get() {
        return element;
    }
}
