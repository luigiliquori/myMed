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

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

import org.apache.http.HttpResponse;

/**
 * Assert utilities for the RequestHandler classes
 * 
 * @author Milo Casagrande
 * 
 */
public class BackendAssert {

  /**
   * Assert that the response code from the backend is what it is supposed to be
   * 
   * @param response
   *          the backend response
   * @param expectedCode
   *          the expected code
   */
  public static void assertResponseCodeIs(final HttpResponse response, final int expectedCode) {
    final int statusCode = response.getStatusLine().getStatusCode();
    assertEquals("The status code is not as expected.", expectedCode, statusCode);
  }

  /**
   * Assert that the JSON format we get back from the response is valid
   * 
   * @param response
   *          the HTTP response
   * @throws IllegalStateException
   * @throws IOException
   */
  public static void assertIsValidJson(final HttpResponse response) throws IllegalStateException, IOException {
    final InputStreamReader in = new InputStreamReader(response.getEntity().getContent());
    final BufferedReader br = new BufferedReader(in);

    String line = "";

    try {
      while ((line = br.readLine()) != null) {
        assertTrue("The JSON format is not valid.", TestUtils.isValidJson(line));
      }
    } finally {
      in.close();
      br.close();
    }
  }

  /**
   * Assert that the User JSON format we get back from the response is valid
   * 
   * @param response
   *          the HTTP response
   * @throws IllegalStateException
   * @throws IOException
   */
  public static void assertIsValidUserJson(final HttpResponse response) throws IllegalStateException, IOException {
    final InputStreamReader in = new InputStreamReader(response.getEntity().getContent());
    final BufferedReader br = new BufferedReader(in);

    String line = "";

    try {
      if (br.ready()) {
        while ((line = br.readLine()) != null) {
          assertTrue("The JSON format is not valid.", TestUtils.isValidUserJson(line));
        }
      }
    } finally {
      in.close();
      br.close();
    }
  }
}
