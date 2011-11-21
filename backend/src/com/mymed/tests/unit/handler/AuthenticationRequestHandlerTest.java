package com.mymed.tests.unit.handler;

import java.io.IOException;
import java.net.URI;
import java.net.URISyntaxException;

import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.methods.HttpPost;
import org.junit.BeforeClass;
import org.junit.Test;

public class AuthenticationRequestHandlerTest extends GenralHandlerTest {

  private static final String HANDLER_NAME = "AuthenticationRequestHandler";

  @BeforeClass
  public static void setUpOnce() {
    path = TestUtils.createPath(HANDLER_NAME);
  }

  @Test
  public void createTest() throws URISyntaxException, ClientProtocolException, IOException {
    TestUtils.addParameter(params, "code", CREATE);
    TestUtils.addParameter(params, "user", TestUtils.createUser().toString());

    final String query = TestUtils.createQueryParams(params);
    final URI uri = TestUtils.createUri(path, query);

    final HttpPost postRequest = new HttpPost(uri);
    final HttpResponse response = client.execute(postRequest);
  }
}
