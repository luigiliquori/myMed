package com.mymed.properties;

/**
 * Structure to hold and define a properties type.
 * 
 * @author Milo Casagrande
 */
public enum PropType implements IPropType {
  /**
   * The properties for the database schema.
   * 
   * Here there are the column names
   */
  COLUMNS("columns", "columns.properties"),
  /**
   * General properties about mymed.
   */
  GENERAL("general", "general.properties"),
  /**
   * Holder for error strings.
   */
  ERRORS("errors", "errors.properties"),
  /**
   * Holder for the beans field names.
   */
  FIELDS("fields", "fields.properties"),
  /**
   * Holder of the JSON attributes.
   */
  JSON("json", "json.properties");

  private final String name;
  private final String fileName;

  private PropType(final String name, final String fileName) {
    this.name = name;
    this.fileName = fileName;
  }

  /*
   * (non-Javadoc)
   * 
   * @see com.mymed.properties.IPropType#getName()
   */
  @Override
  public String getName() {
    return name;
  }

  /*
   * (non-Javadoc)
   * 
   * @see com.mymed.properties.IPropType#getFileName()
   */
  @Override
  public String getFileName() {
    return fileName;
  }
}
