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
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.Test;

public class GeneralHandlerTest {
  // The query codes
  protected static final String CREATE = "0";
  protected static final String READ = "1";
  protected static final String UPDATE = "2";
  protected static final String DELETE = "3";
  protected static final String WRONG = "8";

  protected HttpClient client;
  // Lists where we store the parameters to construct the queries
  protected List<NameValuePair> params;
  protected List<NameValuePair> user;

  // String necessary to create the URL to the backend
  protected static String path;

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

  @AfterClass
  public static void closeOnce() {
    path = "";
  }

  /**
   * Send a wrong code in the query with a 'GET' request.
   * <p>
   * Check that the response code is '500', and that the JSON format is valid
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void sendWrongCodeGetTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, "code", WRONG);

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpGet getRequest = new HttpGet(uri);
    final HttpResponse response = client.execute(getRequest);

    BackendAssert.assertResponseCodeIs(response, 500);
    BackendAssert.assertIsValidJson(response);
  }

  /**
   * Send a wrong code in the query with a 'POST' request.
   * <p>
   * Check that the response code is '500', and that the JSON format is valid
   * 
   * @throws URISyntaxException
   * @throws ClientProtocolException
   * @throws IOException
   */
  @Test
  public void sendWrongCodePostTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, "code", WRONG);

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpPost postRequest = new HttpPost(uri);
    final HttpResponse response = client.execute(postRequest);

    BackendAssert.assertResponseCodeIs(response, 500);
    BackendAssert.assertIsValidJson(response);
  }
}
