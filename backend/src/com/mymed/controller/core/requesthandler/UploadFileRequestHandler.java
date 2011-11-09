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
		// TODO Auto-generated constructor stub
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		// TODO Auto-generated method stub
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response)
			throws ServletException, IOException {
		response.setContentType("text/html;charset=UTF-8");
		PrintWriter out = response.getWriter();
		try {
			
			// get access to file that is uploaded from client
			Part p1 = request.getPart("file");
			InputStream is = p1.getInputStream();

			// read filename which is sent as a part
			Part p2  = request.getPart("filename");
			Scanner s = new Scanner(p2.getInputStream());
			String filename = s.nextLine();    // read filename from stream
			System.out.println("filename: " + filename);

//			// get filename to use on the server
			String outputfile = this.getServletContext().getRealPath(filename);  // get path on the server
			FileOutputStream os = new FileOutputStream (outputfile);

			// write bytes taken from uploaded file to target file
			int ch = is.read();
			while (ch != -1) {
				os.write(ch);
				ch = is.read();
			}
			os.close();
			out.println("<h3>File uploaded successfully!</h3>");
		}
		catch(Exception ex) {
			ex.printStackTrace();
		}
		finally { 
			out.close();
		}
	}
}
