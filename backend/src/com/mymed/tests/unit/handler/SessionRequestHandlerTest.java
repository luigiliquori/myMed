package com.mymed.tests.unit.handler;

import java.io.IOException;
import java.net.URI;
import java.net.URISyntaxException;

import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;

import com.google.gson.JsonObject;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.session.SessionManager;
import com.mymed.tests.unit.handler.utils.BackendAssert;
import com.mymed.tests.unit.handler.utils.TestUtils;

public class SessionRequestHandlerTest extends GeneralHandlerTest {

  private static final String HANDLER_NAME = "SessionRequestHandler";
  private static String accessToken;

  @BeforeClass
  public static void setUpOnce() {
    accessToken = TestUtils.createAccessToken();
    path = TestUtils.createPath(HANDLER_NAME);
  }

  @Before
  public void setupBefore() throws InternalBackEndException, IOBackEndException {
    // Set up what we need in the server
    final ProfileManager profileManager = new ProfileManager();
    profileManager.create(TestUtils.createUserBean(accessToken));

    final SessionManager manager = new SessionManager();
    manager.create(TestUtils.createSessionBean(accessToken));
  }

  /**
   * Perform a read with wrong data, and check that the response code is '500'
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void wrongReadTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, PARAM_CODE, READ);
    TestUtils.addParameter(params, PARAM_ACCESS_TOKEN, TestUtils.createAccessToken());
    TestUtils.addParameter(params, PARAM_SOCIAL_NETWORK, "myMed");

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpGet getRequest = new HttpGet(uri);
    final HttpResponse response = client.execute(getRequest);

    BackendAssert.assertIsValidJson(response);
    BackendAssert.assertResponseCodeIs(response, 500);
  }

  /**
   * Perform a correct read, and check that the response code is '200'
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void readUserTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, PARAM_CODE, READ);
    TestUtils.addParameter(params, PARAM_ACCESS_TOKEN, accessToken);
    TestUtils.addParameter(params, PARAM_SOCIAL_NETWORK, "myMed");

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpGet getRequest = new HttpGet(uri);
    final HttpResponse response = client.execute(getRequest);

    BackendAssert.assertIsValidJson(response);
    BackendAssert.assertResponseCodeIs(response, 200);
    BackendAssert.assertIsValidUserJson(response);
  }

  /**
   * Update a session object, and check that the response code is '200'
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void updateTest() throws URISyntaxException, ClientProtocolException, IOException {
    final JsonObject session = TestUtils.createSessionJson();
    session.addProperty("isExpired", "true");

    TestUtils.addParameter(params, PARAM_CODE, UPDATE);
    TestUtils.addParameter(params, PARAM_ACCESS_TOKEN, accessToken);
    TestUtils.addParameter(params, PARAM_SESS_STRING, session.toString());

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpPost postRequest = new HttpPost(uri);
    final HttpResponse response = client.execute(postRequest);

    BackendAssert.assertIsValidJson(response);
    BackendAssert.assertResponseCodeIs(response, 200);
  }

  /**
   * Delete a session object, and check that the response code is '200'
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void deleteTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, PARAM_CODE, DELETE);
    TestUtils.addParameter(params, PARAM_ACCESS_TOKEN, accessToken);
    TestUtils.addParameter(params, PARAM_SOCIAL_NETWORK, "myMed");

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpGet getRequest = new HttpGet(uri);
    final HttpResponse response = client.execute(getRequest);

    BackendAssert.assertIsValidJson(response);
    BackendAssert.assertResponseCodeIs(response, 200);
  }
}
