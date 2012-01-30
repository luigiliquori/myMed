package com.mymed.properties;

/**
 * Interface for defining a common properties type holder
 * 
 * @author Milo Casagrande
 */
public interface IPropType {
  /**
   * @return the name of the properties holder
   */
  String getName();

  /**
   * @return the name of the file holding the properties
   */
  String getFileName();
}
