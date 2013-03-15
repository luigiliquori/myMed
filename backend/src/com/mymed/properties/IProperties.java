package com.mymed.properties;

/**
 * Interface for accessing properties file
 * 
 * @author Milo Casagrande
 * 
 */
public interface IProperties {
  /**
   * Retrieve the value of the specified property.
   * 
   * @param key
   *          the name of the property to retrieve
   * @return the value of the property
   */
  String get(String key);
}
