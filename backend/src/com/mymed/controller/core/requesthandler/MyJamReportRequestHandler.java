package com.mymed.controller.core.requesthandler;

import java.io.IOException;
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
import com.mymed.model.data.geolocation.MSearchBean;
import com.mymed.model.data.id.MyMedId;
import com.mymed.model.data.myjam.MReportBean;
import com.mymed.model.data.myjam.MyJamTypeValidator;
import com.mymed.utils.MConverter;

/**
 * Manages the requests related to reports.
 * 
 * @author iacopo
 * 
 */
@WebServlet("/MyJamReportRequestHandler")
public class MyJamReportRequestHandler extends AbstractRequestHandler implements IMyJamCallAttributes {
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
  public MyJamReportRequestHandler() throws ServletException {
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
      MyMedId id;
      String reportId, userId, latitude, longitude, radius;

      // accessToken
      if (parameters.get("accessToken") == null) {
        throw new InternalBackEndException("accessToken argument is missing!");
      } else {
        tokenValidation(parameters.get("accessToken")); // Security Validation
      }

      switch (code) {
        case READ : // GET
          message.setMethod("READ");
          if ((latitude = parameters.get(LATITUDE)) != null && (longitude = parameters.get(LONGITUDE)) != null
              && (radius = parameters.get(RADIUS)) != null) {
            // Latitude and longitude in micro-degrees
            final int lat = Integer.parseInt(latitude);
            final int lon = Integer.parseInt(longitude);
            // Diameter in m.
            final int rad = Integer.parseInt(radius);
            final List<MSearchBean> resultList = myJamManager.searchReports(lat, lon, rad);
            message.addData("search_reports", getGson().toJson(resultList));
          } else if ((reportId = parameters.get(ID)) != null) {
            id = MyMedId.parseString(reportId); // This conversion is done only
                                                // to check the syntax of the
                                                // id.
            final MReportBean reportInfo = myJamManager.getReport(id.toString());
            message.addData("report", getGson().toJson(reportInfo));
          } else if ((userId = parameters.get(USER_ID)) != null) {
            final List<String> activeRepId = myJamManager.getActiveReport(userId);
            message.addData("active_reports", getGson().toJson(activeRepId));
          } else {
            throw new InternalBackEndException("missing parameter, bad request!");
          }
          break;
        default :
          throw new InternalBackEndException(this.getClass().getName() + "(" + code + ") not exist!");
      }
    } catch (final AbstractMymedException e) {
      e.printStackTrace();
      LOGGER.info("Error in doGet");
      LOGGER.debug("Error in doGet", e);
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    } catch (final NumberFormatException e) {
      e.printStackTrace();
      LOGGER.info("Error in doGet");
      LOGGER.debug("Error in doGet", e);
      message.setStatus(500);
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
      String latitude, longitude, content;

      // accessToken
      if (parameters.get("accessToken") == null) {
        throw new InternalBackEndException("accessToken argument is missing!");
      } else {
        tokenValidation(parameters.get("accessToken")); // Security Validation
      }

      switch (code) {
        case CREATE :
          message.setMethod("CREATE");
          if ((latitude = parameters.get(LATITUDE)) != null && (longitude = parameters.get(LONGITUDE)) != null) {
            final int lat = Integer.parseInt(latitude);
            final int lon = Integer.parseInt(longitude);
            content = MConverter.convertStreamToString(request.getInputStream(), request.getContentLength());
            final MReportBean report = getGson().fromJson(content, MReportBean.class);
            MyJamTypeValidator.validate(report);
            final MReportBean res = myJamManager.insertReport(report, lat, lon);
            message.addData("report", getGson().toJson(res));
          } else {
            throw new InternalBackEndException("missing parameter, bad request!");
          }
          break;
        default :
          throw new InternalBackEndException(this.getClass().getName() + "(" + code + ") not exist!");
      }

    } catch (final AbstractMymedException e) {
      e.printStackTrace();
      LOGGER.info("Error in doPost");
      LOGGER.debug("Error in doPost", e);
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    } catch (final NumberFormatException e) {
      e.printStackTrace();
      LOGGER.info("Error in doPost");
      LOGGER.debug("Error in doPost", e);
      message.setStatus(500);
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

      // accessToken
      if (parameters.get("accessToken") == null) {
        throw new InternalBackEndException("accessToken argument is missing!");
      } else {
        tokenValidation(parameters.get("accessToken")); // Security Validation
      }

      switch (code) {
        case DELETE :
          message.setMethod("DELETE");
          myJamManager.deleteReport(parameters.get("id"));
          break;
      }
      super.doDelete(request, response);
    } catch (final AbstractMymedException e) {
      e.printStackTrace();
      LOGGER.info("Error in doDelete");
      LOGGER.debug("Error in doDelete", e);
      message.setStatus(e.getStatus());
      message.setDescription(e.getMessage());
    }
  }
}
