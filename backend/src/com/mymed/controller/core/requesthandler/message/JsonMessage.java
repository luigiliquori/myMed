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
package com.mymed.controller.core.requesthandler.message;

import static com.mymed.utils.GsonUtils.gson;

import java.util.HashMap;
import java.util.Map;

/**
 * Represent the message/notification for the frontend
 * 
 * @author lvanni
 * 
 */
public class JsonMessage<T> {

  /* --------------------------------------------------------- */
  /* Attributes */
  /* --------------------------------------------------------- */
  private int status;
  private String handler;
  private String method;
  private String description;
  private  Map<String, String> data;
  private final Map<String, T> dataObject;

  /* --------------------------------------------------------- */
  /* Constructors */
  /* --------------------------------------------------------- */
  /**
   * Default Constructor
   * 
   * @param status
   *          server status: 200|404|500|..
   * @param handler
   *          The ClassName of the handler responsible for the message
   */
  public JsonMessage(final int status, final String handler) {
    this(status, handler, "unknown", new HashMap<String, String>(), new HashMap<String, T>());
  }

  /**
   * 
   * @param status
   *          The server status: (see http server code 200|404|500|...)
   * @param handler
   *          The ClassName of the handler responsible for the message
   * @param method
   *          The method ID: CREATE|READ|UPDATE|DELETE
   * @param data
   *          The content of the response
   */
  public JsonMessage(final int status, final String handler, final String method, final Map<String, String> data, final Map<String, T> dataObject) {
    this.status = status;
    this.handler = handler;
    this.method = method;
    this.data = data;
    this.dataObject = dataObject;
  }

  /*
   * message { "status" : 200|404|500|... , "handler" : RequestHandlerID ,
   * "method" : CREATE|READ|UPDATE|DELETE , "data" : [ "message" : "String" ,
   * "data_id_1" : "String" , "data_id_2" : "String" ] }
   */
  @Override
  public String toString() {
    return gson.toJson(this);
  }

  /* --------------------------------------------------------- */
  /* GETTER&SETTER */
  /* --------------------------------------------------------- */
  public int getStatus() {
    return status;
  }

  public void setStatus(final int status) {
    this.status = status;
  }

  public String getHandler() {
    return handler;
  }

  public void setHandler(final String handler) {
    this.handler = handler;
  }

  public String getMethod() {
    return method;
  }

  public void setMethod(final String method) {
    this.method = method;
  }

  public Map<String, String> getData() {
    return data;
  }
  
  /**
   * 
   * @param key
   * @param value
   * @deprecated Use {@link #addDataObject(String, Object)} instead
   */
  @Deprecated
  public void addData(final String key, final String value) {
    data.put(key, value);
  }

  public Map<String, T> getDataObject() {
    return dataObject;
  }
  
  public void addDataObject(final String key, final T value) {
    dataObject.put(key, value);
  }

  public String getDescription() {
    return description;
  }

  public void setDescription(final String description) {
    this.description = description;
  }
}
