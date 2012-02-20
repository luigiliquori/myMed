package com.mymed.controller.core.manager.registration;

import java.net.InetAddress;
import java.net.UnknownHostException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import ch.qos.logback.classic.Logger;

import com.google.gson.Gson;
import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.authentication.AuthenticationManager;
import com.mymed.controller.core.manager.authentication.IAuthenticationManager;
import com.mymed.controller.core.manager.pubsub.IPubSubManager;
import com.mymed.controller.core.manager.pubsub.PubSubManager;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.HashFunction;
import com.mymed.utils.MLogger;
import com.mymed.utils.Mail;

/**
 * 
 * @author lvanni
 */
public class RegistrationManager implements IRegistrationManager {

  private static final Logger LOGGER = MLogger.getLogger();

  private final IPubSubManager pubSubManager;
  private final IAuthenticationManager authenticationManager;
  private final Gson gson;

  public RegistrationManager() throws InternalBackEndException {
    pubSubManager = new PubSubManager();
    authenticationManager = new AuthenticationManager();
    gson = new Gson();
  }

  @Override
  public void create(final MUserBean user, final MAuthenticationBean authentication) throws AbstractMymedException {
    // PUBLISH A NEW REGISTATION PENDING TASK
    final List<MDataBean> dataList = new ArrayList<MDataBean>();
    try {
      final MDataBean dataUser = new MDataBean();
      dataUser.setKey("user");
      dataUser.setOntologyID("0");
      dataUser.setValue(gson.toJson(user));

      final MDataBean dataAuthentication = new MDataBean();
      dataAuthentication.setKey("authentication");
      dataAuthentication.setOntologyID("0");
      dataAuthentication.setValue(gson.toJson(authentication));

      dataList.add(dataUser);
      dataList.add(dataAuthentication);
    } catch (final JsonSyntaxException e) {
      throw new InternalBackEndException("User/Authentication jSon format is not valid");
    }

    final HashFunction h = new HashFunction("myMed");
    final String accessToken = h.SHA1ToString(user.getLogin() + System.currentTimeMillis());

    pubSubManager.create("myMed", accessToken, accessToken, user, dataList);

    final StringBuilder contentBuilder = new StringBuilder(250);
    try {
      // TODO add international support
      contentBuilder.append("Bienvenu sur myMed.\nPour finaliser votre inscription cliquez sur le lien: http://");
      // TODO fix the host name one final
      contentBuilder.append(InetAddress.getLocalHost().getCanonicalHostName());
      contentBuilder.append("?registration=ok&accessToken=");
      contentBuilder.append(accessToken);
      contentBuilder.append("\n\n------\nL'équipe myMed");

      contentBuilder.trimToSize();

      // Send the mail
      new Mail("mymed.subscribe@gmail.com", user.getEmail(), "Bienvenu sur myMed", contentBuilder.toString());
    } catch (final UnknownHostException e) {
      LOGGER.debug("Impossible to find the host", e);
      throw new InternalBackEndException(e); // NOPMD
    }
  }

  @Override
  public void read(final String accessToken) throws AbstractMymedException {
    // Reteive the user profile
    final String userID = pubSubManager.read("myMed", accessToken).get(0).get("publisherID");
    final List<Map<String, String>> dataList = pubSubManager.read("myMed", accessToken, userID);
    MUserBean userBean = null;
    MAuthenticationBean authenticationBean = null;

    try {
      for (final Map<String, String> dataEntry : dataList) {
        if (dataEntry.get("key").equals("user")) {
          userBean = gson.fromJson(dataEntry.get("value"), MUserBean.class);
        } else if (dataEntry.get("key").equals("authentication")) {
          authenticationBean = gson.fromJson(dataEntry.get("value"), MAuthenticationBean.class);
        }
      }
    } catch (final JsonSyntaxException e) {
      LOGGER.debug("JSON string is not valid", e);
      throw new InternalBackEndException("User/Authentication jSon format is not valid"); // NOPMD
    }

    // register the new user
    if (userBean != null && authenticationBean != null) {
      authenticationManager.create(userBean, authenticationBean);
      // delete pending tasks
      pubSubManager.delete("mymed", accessToken, userBean);
    }
  }
}
