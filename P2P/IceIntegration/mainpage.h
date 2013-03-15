/*
 * Copyright 2012 POLITO 
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
/** @file mainpage.h

  @brief Main page of Mymed peer-to-peer library documentation

  @mainpage Mymed peer-to-peer library
  
  @author Peter Neuss
  
  @section intro Introduction
	myMed project is born from a double constatation: on one side the huge potential of the economic development of the trans-Alp region (Alcotra), and from the other side the poor precence of advanced ICT tools that supports the economic growth.
myMed was proposed thanks to the collabaration between the french INRIA (Institut National de Recherche en Informatique et en Automatique) of Sophia Antipolis and the italian POLITO (Polytechnic of Turin), plus two other institutions, namely the University of Turin (UNITO) and the Oriental University of Piedmont (UNIPO).
The project objectives are the design, the sperimentation and the realization of a prototype of a geosensible social network aimed at improving the information exchange and at creating ad-hoc services for the Alcotra region.
	

  The primary purpose of this library is to provide peer-to-peer connections for MyMed applications.
  
  There are two main software packages, and two levels of wrapper.
  
  The low-level software packages is written in C, and consists of two files :
  - p2pConnApi.c - this uses the open-source pjnath library to create UDP-style peer-to-peer connections
  - rsComm.c - this communicates with a MyMed Rendezvous Server (RS) to bootstrap the peer-to-peer connections.
  
  The PseudoTcp software package is written in C++.  It assumes a UDP-style connection adhering to the IConnection interface. Using this it implements a reliable message-based connection.  In particular, it provides :
  -# Retransmission of lost messages.
  -# Elimination of duplicates.
  -# Ordering of messages.
  
  The first wrapper integrates the PseudoTcp package with the low-level package.  It consists of four files :
  - IceConnection.cpp/.hpp - This is an object which implements IConnection using the low-level p2p connection 
  - IceWrapper.cpp/hpp - This providec a C++ facade for the low-level functionality and the PseudoTcp.  A MyMed application written in C++ would use this as an API for the library.
  
  The second wrapper provides a Java interface for the functionality and is implemented using JNI.  It consists of the following files :
  - JavaIceWrapper.java - This loads the dynamic library and declares the API
  - mymed_JavaIceWrapper.h - This is created automatically and declares the native function headers
  - JavaIceWrapper.cpp - This file contains the definitions of the native functions.
  
  The directory "IceIntegration" contains all the files for creating the library.
  The directory "Chat" contains a sample Java chat application which uses the library.
  
  @section javaint Java Interface
  
  @subsection create How to create the dynamic library
    The shell file 'makeDynLib.sh' will create the library file.  It consists of five steps:
    -# Compile the JavaIceWrapper.java file creating a JavaIceWrapper.class file
    -# Create the mymed_JavaIceWrapper.h file automatically from the class file
    -# Compile the low-level .c files into object files appropriate for a dynamic library
    -# Compile the .cpp files into object files appropriate for a dynamic library
    -# Combine the object files into a dynamic library named libmymed.so.1.0.1
  
  @subsection connect How to connect to the library from Java
  -# Include the JavaIceWrapper.java file in your Java application (it is in the mymed package)
  -# Create a symbolic link to the libmymed.so.1.0.1 library called libmymed.so
  -# make sure the Java compiler can find the libmed.so file (either by placing it in a standard location or through the command-line parameter -Djava.library.path)
  
  @subsection api The Java API
  
    The JavaIceWrapper class reference details the API.  See also the Chat application written in Java/Swing for an example of how to use the library.
    
  @section Documentation
  
  This documentation has been created by doxygen.  To generate it, in the IceIntegration directory execute the command:
  doxygen DoxyFile
  
  
  
  
  <hr>
  */
