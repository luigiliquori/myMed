<%@ page language="java" contentType="text/plain; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page isErrorPage="true" import="java.io.PrintWriter" %>

<%
	out.println("<h3>Unhandled Exception!</h4>");
	out.println(exception.getMessage());
%>
