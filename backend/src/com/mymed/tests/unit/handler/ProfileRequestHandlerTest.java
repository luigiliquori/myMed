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
package com.mymed.tests.unit.handler;

import java.io.IOException;
import java.net.URI;
import java.net.URISyntaxException;

import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.junit.After;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;

import com.google.gson.JsonObject;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.session.SessionManager;
import com.mymed.controller.core.requesthandler.ProfileRequestHandler;
import com.mymed.tests.unit.handler.utils.BackendAssert;
import com.mymed.tests.unit.handler.utils.TestUtils;

/**
 * Test class for the {@link ProfileRequestHandler}.
 * 
 * @author Milo Casagrande
 * 
 */
public class ProfileRequestHandlerTest extends GeneralHandlerTest {

  private static final String HANDLER_NAME = "ProfileRequestHandler";
  private String accessToken = null;

  @BeforeClass
  public static void setUpOnce() {
    path = TestUtils.createPath(HANDLER_NAME);
  }

  @Before
  public void setupBefore() throws InternalBackEndException, IOBackEndException {
    accessToken = TestUtils.createAccessToken();

    final ProfileManager manager = new ProfileManager();
    manager.create(TestUtils.createUserBean(""));

    final SessionManager session = new SessionManager();
    session.create(TestUtils.createSessionBean(accessToken));
  }

  @After
  public void cleanAfter() throws InternalBackEndException {
    final ProfileManager manager = new ProfileManager();
    final SessionManager session = new SessionManager();

    try {
      manager.delete(TestUtils.MYMED_ID);
      session.delete(accessToken);
    } catch (final IOBackEndException ex) {
      // Do nothing, even if we have an error deleting something
    }
  }

  /**
   * Get a non existent user from the database.
   * <p>
   * Check that the response code is '404', and that the JSON format is valid
   * 
   * @throws ClientProtocolException
   * @throws IOException
   * @throws URISyntaxException
   */
  @Test
  public void readWrongUserTest() throws IOException, URISyntaxException {
    TestUtils.addParameter(params, PARAM_CODE, READ);
    TestUtils.addParameter(params, PARAM_ID, "wrong.email@example.org");
    TestUtils.addParameter(params, PARAM_ACCESS_TOKEN, accessToken);

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpGet getRequest = new HttpGet(uri);
    final HttpResponse response = client.execute(getRequest);

    BackendAssert.assertResponseCodeIs(response, 404);
    BackendAssert.assertIsValidJson(response);
  }

  /**
   * Create a new user in the database.
   * <p>
   * Check that the response code is '200', and that the JSON format is a valid
   * 'user' JSON format
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void createTest() throws URISyntaxException, ClientProtocolException, IOException {
    final JsonObject user = TestUtils.createUserJson();

    TestUtils.addParameter(params, PARAM_CODE, CREATE);
    TestUtils.addParameter(params, PARAM_USER, user.toString());
    TestUtils.addParameter(params, PARAM_ACCESS_TOKEN, accessToken);

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpPost postRequest = new HttpPost(uri);
    final HttpResponse response = client.execute(postRequest);

    BackendAssert.assertResponseCodeIs(response, 200);
    BackendAssert.assertIsValidUserJson(response);
  }

  /**
   * Update a user in the database.
   * <p>
   * Check that the response code is '200', and that the JSON format is a valid
   * 'user' JSON format
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void updateTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, PARAM_CODE, UPDATE);
    TestUtils.addParameter(params, PARAM_ACCESS_TOKEN, accessToken);

    final JsonObject user = TestUtils.createUserJson();
    user.addProperty("gender", "female");

    TestUtils.addParameter(params, PARAM_USER, user.toString());

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpPost postRequest = new HttpPost(uri);
    final HttpResponse response = client.execute(postRequest);

    BackendAssert.assertResponseCodeIs(response, 200);
    BackendAssert.assertIsValidUserJson(response);
  }

  /**
   * Delete a user in the database.
   * <p>
   * Check that the response code is '200', and that the JSON format is valid
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void deleteTest() throws URISyntaxException, IOException {
    TestUtils.addParameter(params, PARAM_CODE, DELETE);
    TestUtils.addParameter(params, PARAM_ID, TestUtils.MYMED_ID);
    TestUtils.addParameter(params, PARAM_ACCESS_TOKEN, accessToken);

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpGet getRequest = new HttpGet(uri);
    final HttpResponse response = client.execute(getRequest);

    BackendAssert.assertResponseCodeIs(response, 200);
    BackendAssert.assertIsValidJson(response);
  }
}
