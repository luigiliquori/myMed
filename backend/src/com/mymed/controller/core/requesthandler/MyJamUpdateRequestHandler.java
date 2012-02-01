package com.mymed.controller.core.requesthandler;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.nio.BufferOverflowException;
import java.nio.ByteBuffer;
import java.nio.charset.Charset;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.myjam.MyJamManager;
import com.mymed.controller.core.manager.storage.MyJamStorageManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.id.MyMedId;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.model.data.myjam.MyJamTypeValidator;

/**
 * Manages the requests related to updates.
 * 
 * @author iacopo
 * 
 */
@WebServlet("/MyJamUpdateRequestHandler")
public class MyJamUpdateRequestHandler extends AbstractRequestHandler implements IMyJamCallAttributes {
  /* --------------------------------------------------------- */
  /* Attributes */
  /* --------------------------------------------------------- */
  private static final long serialVersionUID = 1L;

  private MyJamManager myJamManager;

  /* --------------------------------------------------------- */
  /* Constructors */
  /* --------------------------------------------------------- */
  /**
   * @throws ServletException
   * @see HttpServlet#HttpServlet()
   */
  public MyJamUpdateRequestHandler() throws ServletException {
    super();
    try {
      myJamManager = new MyJamManager(new MyJamStorageManager());
    } catch (final InternalBackEndException e) {
      throw new ServletException("MyJamStorageManager is not accessible because: " + e.getMessage());
    }
  }

  /* --------------------------------------------------------- */
  /* extends HttpServlet */
  /* --------------------------------------------------------- */
  @Override
  protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException,
      IOException {

    final JsonMessage message = new JsonMessage(200, this.getClass().getName());

    try {
      final Map<String, String> parameters = getParameters(request);
      final RequestCode code = requestCodeMap.get(parameters.get("code"));
      String id, last_reception;

      switch (code) {
        case READ : // GET
          message.setMethod("READ");
          if ((id = parameters.get(ID)) != null && (last_reception = parameters.get(START_TIME)) != null) {
            final long startTime = Long.parseLong(last_reception);
            final MyMedId reportId = MyMedId.parseString(id);
            final List<MReportBean> updates = myJamManager.getUpdates(reportId.toString(), startTime);
            message.addData("updates", getGson().toJson(updates));
          } else {
            throw new InternalBackEndException("missing parameter, bad request!");
          }
          break;
        default :
          throw new InternalBackEndException(this.getClass().getName() + "(" + code + ") not exist!");
      }
    } catch (final AbstractMymedException e) {
      LOGGER.info("Error in doGet operation");
      LOGGER.debug("Error in doGet operation", e);
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    }

    printJSonResponse(message, response);
  }

  /**
   * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
   *      response)
   */
  @Override
  protected void doPost(final HttpServletRequest request, final HttpServletResponse response) throws ServletException,
      IOException {

    final JsonMessage message = new JsonMessage(200, this.getClass().getName());

    try {
      final Map<String, String> parameters = getParameters(request);
      final RequestCode code = requestCodeMap.get(parameters.get("code"));
      String content, id;

      switch (code) {
        case CREATE :
          message.setMethod("CREATE");
          if ((id = parameters.get(ID)) != null) {
            final MyMedId updateId = MyMedId.parseString(id);
            content = convertStreamToString(request.getInputStream(), request.getContentLength());
            final MReportBean update = getGson().fromJson(content, MReportBean.class);
            MyJamTypeValidator.validate(update);
            final MReportBean res = myJamManager.insertUpdate(updateId.toString(), update);
            message.addData("update", getGson().toJson(res));
          } else {
            throw new InternalBackEndException("missing parameter, bad request!");
          }
          break;
        default :
          throw new InternalBackEndException(this.getClass().getName() + "(" + code + ") not exist!");
      }

    } catch (final AbstractMymedException e) {
      LOGGER.info("Error in doPost operation");
      LOGGER.debug("Error in doPost operation", e);
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    }

    printJSonResponse(message, response);
  }

  /**
   * @see HttpServlet#doDelete(HttpServletRequest request, HttpServletResponse
   *      response)
   */
  @Override
  protected void doDelete(final HttpServletRequest request, final HttpServletResponse response)
      throws ServletException, IOException {

    final JsonMessage message = new JsonMessage(200, this.getClass().getName());

    try {
      final Map<String, String> parameters = getParameters(request);
      final RequestCode code = requestCodeMap.get(parameters.get("code"));
      switch (code) {
        case DELETE :
          message.setMethod("DELETE");
      }
      super.doDelete(request, response);
    } catch (final AbstractMymedException e) {
      LOGGER.info("Error in doRequest operation");
      LOGGER.debug("Error in doRequest operation", e);
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    }
  }

  /**
   * Given an InputStream reads the bytes as UTF8 chars and return a String.
   * 
   * @param is
   *          Input stream.
   * @param length
   *          Length of the stream in bytes.
   * @return The string
   * @throws InternalBackEndException
   *           Format is not correct or the length less then the real wrong.
   */
  private static String convertStreamToString(final InputStream is, final int length) throws InternalBackEndException {
    try {
      if (length > 0) {
        final ByteBuffer byteBuff = ByteBuffer.allocate(length);
        int currByte;
        while ((currByte = is.read()) != -1) {
          byteBuff.put((byte) currByte);
        }
        byteBuff.compact();
        return com.mymed.utils.MConverter.byteBufferToString(byteBuff);
      } else {
        final BufferedReader buffRead = new BufferedReader(new InputStreamReader(is, Charset.forName(ENCODING)));
        final StringBuffer sb = new StringBuffer(150);
        String line;
        while ((line = buffRead.readLine()) != null) {
          sb.append(line);
          sb.append('\n');
        }

        sb.trimToSize();
        return sb.toString();
      }
    } catch (final IOException e) {
      throw new InternalBackEndException("Wrong content");
    } catch (final BufferOverflowException e) {
      throw new InternalBackEndException("Wrong length");
    } finally {
      try {
        is.close();
      } catch (final IOException e) {
        e.printStackTrace();
      }
    }
  }
}