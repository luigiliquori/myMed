package com.mymed.properties;

/**
 * Structure to hold and define a properties type
 * 
 * @author Milo Casagrande
 */
public enum PropType implements IPropType {
  /**
   * The properties for the database schema
   * 
   * Here there are the column names
   */
  COLUMNS("columns", "columns.properties"),
  /**
   * General properties about mymed
   */
  GENERAL("general", "general.properties"),
  /**
   * Holder for error strings
   */
  ERRORS("errors", "errors.properties"),
  /**
   * Holder for the beans field names
   */
  FIELDS("fields", "fields.properties");

  private final String name;
  private final String fileName;

  private PropType(final String name, final String fileName) {
    this.name = name;
    this.fileName = fileName;
  }

  @Override
  public String getName() {
    return name;
  }

  @Override
  public String getFileName() {
    return fileName;
  }
}
