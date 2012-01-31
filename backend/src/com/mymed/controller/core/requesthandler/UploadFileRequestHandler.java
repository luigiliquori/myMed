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
package com.mymed.controller.core.requesthandler;

import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.PrintWriter;
import java.util.Scanner;

import javax.servlet.ServletException;
import javax.servlet.annotation.MultipartConfig;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.Part;

/**
 * Servlet implementation class UploadFileRequestHandler
 */
@MultipartConfig
@WebServlet("/UploadFileRequestHandler")
public class UploadFileRequestHandler extends HttpServlet {
  private static final long serialVersionUID = 1L;

  /**
   * @see HttpServlet#HttpServlet()
   */
  public UploadFileRequestHandler() {
    super();
  }

  /**
   * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
   *      response)
   */
  @Override
  protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException,
      IOException {
    // Stub method
  }

  /**
   * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse
   *      response)
   */
  @Override
  protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException,
      IOException {
    response.setContentType("text/html;charset=UTF-8");
    final PrintWriter out = response.getWriter();
    try {

      // get access to file that is uploaded from client
      final Part p1 = request.getPart("file");
      final InputStream is = p1.getInputStream();

      // read filename which is sent as a part
      final Part p2 = request.getPart("filename");
      final Scanner s = new Scanner(p2.getInputStream());
      final String filename = s.nextLine();
      final String outputfile = getServletContext().getRealPath(filename);
      final FileOutputStream os = new FileOutputStream(outputfile);

      // write bytes taken from uploaded file to target file
      int ch = is.read();
      while (ch != -1) {
        os.write(ch);
        ch = is.read();
      }
      os.close();
      out.println("<h3>File uploaded successfully!</h3>");
    } catch (final Exception ex) {
      ex.printStackTrace();
    } finally {
      out.close();
    }
  }
}
