package com.mymed.tests.unit.handler.utils;

import java.util.EnumSet;

import com.mymed.controller.core.requesthandler.message.JsonMessage;

/**
 * This is the enum class that stores all the fields of a normal JSON response.
 * <p>
 * The fields in here are the ones of a general JSON response as created by the
 * {@link JsonMessage} class.
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
 * 
 */
public enum MJson {
  STATUS("status"),
  DESCRIPTION("description"),
  HANDLER("handler"),
  METHOD("method"),
  DATA("data");

  private String element;

  private MJson(final String element) {
    this.element = element;
  }

  /**
   * Check if JSON element is valid for the JSON format
   * 
   * @param str
   *          the element to check
   * @return true if the element is valid, false otherwise
   */
  public static boolean isValidElement(final String str) {
    boolean isValid = false;

    for (final MJson element : EnumSet.allOf(MJson.class)) {
      if (element.toString().equals(str)) {
        isValid = true;
        break;
      }
    }

    return isValid;
  }

  @Override
  public String toString() {
    return element;
  }
}
