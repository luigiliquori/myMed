package com.mymed.tests.unit.handler;

import java.io.IOException;
import java.net.URI;
import java.net.URISyntaxException;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.junit.After;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;

import com.google.gson.JsonObject;
import com.mymed.controller.core.requesthandler.ProfileRequestHandler;

/**
 * Test class for the {@link ProfileRequestHandler}.
 * 
 * @author Milo Casagrande
 * 
 */
public class ProfileRequestHandlerTest {

  private static final String REQUEST_HANDLER_NAME = "ProfileRequestHandler";
  // The query codes
  private static final String CREATE = "0";
  private static final String READ = "1";
  private static final String UPDATE = "2";
  private static final String DELETE = "3";
  private static final String WRONG = "8";

  private static String path;

  private HttpClient client;
  // Lists where we store the parameters to construct the queries
  private List<NameValuePair> params;
  private List<NameValuePair> user;

  @BeforeClass
  public static void setUpOnce() {
    path = TestUtils.createPath(REQUEST_HANDLER_NAME);
  }

  @Before
  public void setUp() {
    client = new DefaultHttpClient();
    params = new ArrayList<NameValuePair>();
    user = new ArrayList<NameValuePair>();
  }

  @After
  public void close() {
    params.clear();
    params = null;

    user.clear();
    user = null;
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
    TestUtils.addParameter(params, "code", READ);
    TestUtils.addParameter(params, "id", "wrong.email@example.org");

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
    final JsonObject user = createUser();

    TestUtils.addParameter(params, "code", CREATE);
    TestUtils.addParameter(params, "user", user.toString());

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
    TestUtils.addParameter(params, "code", UPDATE);

    final JsonObject user = createUser();
    user.addProperty("gender", "male");

    TestUtils.addParameter(params, "user", user.toString());

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
    TestUtils.addParameter(params, "code", DELETE);
    TestUtils.addParameter(params, "id", "MYMED_prova@example.org");

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpGet getRequest = new HttpGet(uri);
    final HttpResponse response = client.execute(getRequest);

    BackendAssert.assertResponseCodeIs(response, 200);
    BackendAssert.assertIsValidJson(response);
  }

  /**
   * Read the database for the deleted user.
   * <p>
   * Check that the response code is '404', and that the JSON format is valid
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void getDeletedUserTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, "code", READ);
    TestUtils.addParameter(params, "id", "MYMED_prova@example.org");

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpGet getRequest = new HttpGet(uri);
    final HttpResponse response = client.execute(getRequest);

    BackendAssert.assertResponseCodeIs(response, 404);
    BackendAssert.assertIsValidJson(response);
  }

  /**
   * Send a wrong code in the query.
   * <p>
   * Check that the response code is '500', and that the JSON format is valid
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void sendWrongCodeTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, "code", WRONG);

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpGet getRequest = new HttpGet(uri);
    final HttpResponse response = client.execute(getRequest);

    BackendAssert.assertResponseCodeIs(response, 500);
    BackendAssert.assertIsValidJson(response);
  }

  /**
   * Create a default user in JSON format
   */
  private JsonObject createUser() {
    final JsonObject user = new JsonObject();
    user.addProperty("id", "MYMED_prova@example.org");
    user.addProperty("login", "prova@example.org");
    user.addProperty("email", "prova@example.org");
    user.addProperty("name", "Mario Rossi");
    user.addProperty("firstName", "Mario");
    user.addProperty("lastName", "Rossi");
    user.addProperty("birthday", "");
    user.addProperty("lastConnection", "0");
    user.addProperty("profilePicture", "");
    user.addProperty("socialNetworkID", "MYMED");
    user.addProperty("socialNetworkName", "myMed");

    return user;
  }
}
