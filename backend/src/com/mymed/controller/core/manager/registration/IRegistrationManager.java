package com.mymed.controller.core.manager.registration;

import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.model.data.user.MUserBean;

/**
 * 
 * @author lvanni
 */
public interface IRegistrationManager {

  /**
   * 
   * @param user
   * @param authentication
   * @throws AbstractMymedException
   */
  void create(MUserBean user, MAuthenticationBean authentication, String application) throws AbstractMymedException;

  /**
   * 
   * @param accessToken
   * @throws AbstractMymedException
   */
  void read(String accessToken) throws AbstractMymedException;
  
  
  /** v2 */
  void read(String application, String accessToken) throws AbstractMymedException;
}
