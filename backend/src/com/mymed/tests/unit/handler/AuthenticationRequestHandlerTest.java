package com.mymed.tests.unit.handler;

import java.io.IOException;
import java.net.URI;
import java.net.URISyntaxException;

import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.junit.BeforeClass;
import org.junit.Test;

import com.mymed.tests.unit.handler.utils.BackendAssert;
import com.mymed.tests.unit.handler.utils.TestUtils;

public class AuthenticationRequestHandlerTest extends GeneralHandlerTest {

  private static final String HANDLER_NAME = "AuthenticationRequestHandler";

  @BeforeClass
  public static void setUpOnce() {
    path = TestUtils.createPath(HANDLER_NAME);
  }

  /**
   * Call the DELETE method that is not implemented.
   * <p>
   * Check that the response is '500'.
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void deleteTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, PARAM_CODE, DELETE);

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpGet getRequest = new HttpGet(uri);
    final HttpResponse response = client.execute(getRequest);

    BackendAssert.assertIsValidJson(response);
    BackendAssert.assertResponseCodeIs(response, 500);
  }

  /**
   * Create an authentication and a user
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void createTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, PARAM_CODE, CREATE);
    TestUtils.addParameter(params, PARAM_AUTH, TestUtils.createAuthenticationJson().toString());
    TestUtils.addParameter(params, PARAM_USER, TestUtils.createUserJson().toString());

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpPost postRequest = new HttpPost(uri);
    final HttpResponse response = client.execute(postRequest);

    BackendAssert.assertIsValidJson(response);
    BackendAssert.assertResponseCodeIs(response, 200);
  }

  /**
   * Perform a read with the GET method
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void readTest1() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, PARAM_CODE, READ);
    TestUtils.addParameter(params, PARAM_LOGIN, TestUtils.MYMED_EMAIL);
    TestUtils.addParameter(params, PARAM_PWD, TestUtils.getFakePassword());

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpGet getRequest = new HttpGet(uri);
    final HttpResponse response = client.execute(getRequest);

    BackendAssert.assertIsValidJson(response);
    BackendAssert.assertResponseCodeIs(response, 200);
    BackendAssert.assertIsValidUserJson(response);
  }

  /**
   * Perform a read with the POST method
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void readTest2() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, PARAM_CODE, READ);
    TestUtils.addParameter(params, PARAM_LOGIN, TestUtils.MYMED_EMAIL);
    TestUtils.addParameter(params, PARAM_PWD, TestUtils.getFakePassword());

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpPost postRequest = new HttpPost(uri);
    final HttpResponse response = client.execute(postRequest);

    BackendAssert.assertIsValidJson(response);
    BackendAssert.assertResponseCodeIs(response, 200);
    BackendAssert.assertIsValidUserJson(response);
  }

  /**
   * Perform an update of the 'authentication', and check that the response code
   * is '200'
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void updateTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, PARAM_CODE, UPDATE);
    TestUtils.addParameter(params, PARAM_AUTH, TestUtils.createAuthenticationJson().toString());
    TestUtils.addParameter(params, PARAM_ID, TestUtils.MYMED_ID);

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpPost postRequest = new HttpPost(uri);
    final HttpResponse response = client.execute(postRequest);

    BackendAssert.assertIsValidJson(response);
    BackendAssert.assertResponseCodeIs(response, 200);
  }
}
