package com.mymed.properties;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.util.HashMap;
import java.util.Map;
import java.util.Properties;

import com.mymed.utils.MLogger;

/**
 * Manager for the properties file of mymed
 * <p>
 * It is implemented as a singleton, and there is a cache that stores
 * 
 * @author Milo Casagrande
 * 
 */
public class PropertiesManager {

  private static final PropertiesManager MANAGER = new PropertiesManager();

  /*
   * Default capacity of the cache, increase it in case we have more properties
   * to handle
   */
  private static final int CAPACITY = 4;

  // Simple cache mechanism
  private final Map<String, IProperties> propertiesCache;

  private PropertiesManager() {
    propertiesCache = new HashMap<String, IProperties>(CAPACITY);
  }

  public static PropertiesManager getInstance() {
    return MANAGER;
  }

  public IProperties getManager(final IPropType value) {
    IProperties manager = null;

    if (propertiesCache.containsKey(value.getName())) {
      manager = propertiesCache.get(value.getName());
    } else {
      manager = new PropertiesHandler(value.getFileName());
      propertiesCache.put(value.getName(), manager);
    }

    return manager;
  }

  /**
   * Inner class to define and provide a handler to the properties file in the
   * file system
   * 
   * @author Milo Casagrande
   * 
   */
  private class PropertiesHandler implements IProperties {
    private final Properties properties;

    public PropertiesHandler(final String fileName) {
      properties = new Properties();
      InputStream in = null;

      try {
        in = this.getClass().getClassLoader().getResourceAsStream(fileName);
        properties.load(in);
      } catch (final FileNotFoundException ex) {
        // We should never, ever, ever get here!
        MLogger.getLogger().debug("Impossible to find '{}' properties file in the PATH", fileName, ex);
      } catch (final IOException ex) {
        // Nor here!
        MLogger.getLogger().debug("Error loading the properties from the '{}' file", fileName, ex);
      } finally {
        try {
          if (in != null) {
            in.close();
          }
        } catch (final IOException ex) {
          MLogger.getLogger().debug("Error closing stream", ex);
        }
      }
    }

    @Override
    public String get(final String key) {
      return properties.getProperty(key);
    }
  }
}
