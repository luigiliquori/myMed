package com.mymed.model.core.wrapper;

import java.io.File;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import com.mymed.controller.core.services.requesthandler.exception.IOBackEndException;
import com.mymed.controller.core.services.requesthandler.exception.InternalBackEndException;
import com.mymed.model.core.data.dht.configuration.Config;
import com.mymed.model.core.data.dht.factory.DHTClientFactory;
import com.mymed.model.core.data.dht.factory.IDHTClient;
import com.mymed.model.core.data.dht.factory.IDHTClient.ClientType;
import com.mymed.model.core.data.dht.protocol.Cassandra;
import com.mymed.model.core.wrapper.exception.WrapperException;

/**
 * @author lvanni
 * 
 *         This class represent the DAO pattern: Access to data varies depending
 *         on the source of the data. Access to persistent storage, such as to a
 *         database, varies greatly depending on the type of storage
 * 
 *         Use a Data Access Object (DAO) to abstract and encapsulate all access
 *         to the data source. The DAO manages the connection with the data
 *         source to obtain and store data.
 */
public class Wrapper implements IWrapper {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** The type of client used by the wrapper */
	private ClientType type;

	/** DHTClient */
	private IDHTClient client;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default Constructor: will create a wrapper on top of Cassandra
	 * 
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public Wrapper() throws InternalBackEndException {
		this(ClientType.CASSANDRA, new Config(new File("./conf/config.xml")));
	}

	/**
	 * will create a wrapper on top of Cassandra And use the specific
	 * configuration file for the transport layer
	 * 
	 * @param conf
	 *            The configuration of the transport layer
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public Wrapper(Config conf) throws InternalBackEndException {
		this(ClientType.CASSANDRA, conf);
	}

	/**
	 * will create a wrapper on top of the ClientType And use the default
	 * config.xml in rootpath/conf
	 * 
	 * @param type
	 *            Type of DHTClient used
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public Wrapper(ClientType type) throws InternalBackEndException {
		this(type, new Config(new File("./conf/config.xml")));
	}

	/**
	 * /** will create a wrapper on top of the ClientType And use the specific
	 * configuration file for the transport layer
	 * 
	 * @param type
	 *            Type of DHTClient used
	 * @param conf
	 *            The configuration of the transport layer
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public Wrapper(ClientType type, Config conf)
			throws InternalBackEndException {
		this.type = type;
		this.client = DHTClientFactory.createDHTClient(type, conf);
	}

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	/**
	 * Insert a new entry in the database
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param primaryKey
	 *            the ID of the entry
	 * @param args
	 *            All columnName and the their value
	 * @return true if the entry is correctly stored, false otherwise
	 * @throws WrapperException
	 * @throws InternalBackEndException
	 */
	public boolean insertInto(String tableName, String primaryKey,
			Map<String, byte[]> args) throws WrapperException,
			InternalBackEndException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"insertInto is not yet supported by the DHT type: " + type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) this.client;
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;
			// Store into Cassandra the new entry
			try {
				for (String columnName : args.keySet()) {
					node.setSimpleColumn(keyspace, columnFamily, new String(
							args.get(primaryKey), "UTF8"), columnName
							.getBytes("UTF8"), args.get(columnName),
							consistencyOnWrite);
				}
				return true;
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
				throw new WrapperException(
						"UnsupportedEncodingException with\n"
								+ "\t- columnFamily = " + columnFamily + "\n"
								+ "\t- primaryKey = " + primaryKey + "\n");
			}
		}
	}

	/**
	 * Get the value of a column family
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param primaryKey
	 *            the ID of the entry
	 * @param columnName
	 *            the name of the column
	 * @return the value of the column
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public Map<byte[], byte[]> selectAll(String tableName, String primaryKey)
			throws WrapperException, InternalBackEndException,
			IOBackEndException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"selectAll is not yet supported by the DHT type: " + type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) this.client;
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;

			return node.getEntireRow(keyspace, columnFamily, primaryKey,
					consistencyOnRead);
		}
	}

	/**
	 * Get the values of a range of columns
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param primaryKey
	 *            the ID of the entry
	 * @param columnNames
	 *            the name of the columns to return the values
	 * @return the value of the columns
	 * @throws InternalBackEndException
	 */
	public Map<byte[], byte[]> selectRange(String tableName, String primaryKey,
			List<String> columnNames) throws WrapperException,
			InternalBackEndException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"selectAll is not yet supported by the DHT type: " + type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) this.client;
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;
			// Convert the List<String> into List<byte[]>
			List<byte[]> columnNamesToByte = new ArrayList<byte[]>();
			for (String columnName : columnNames) {
				try {
					columnNamesToByte.add(columnName.getBytes("UTF8"));
				} catch (UnsupportedEncodingException e) {
					e.printStackTrace();
					throw new WrapperException(
							"UnsupportedEncodingException with\n"
									+ "\t- columnFamily = " + columnFamily
									+ "\n" + "\t- primaryKey = " + primaryKey
									+ "\n" + "\t- columnFamily = " + columnName
									+ "\n");
				}
			}
			return node.getRangeColumn(keyspace, columnFamily, primaryKey,
					columnNamesToByte, consistencyOnRead);
		}
	}

	/**
	 * Update the value of a Simple Column
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param primaryKey
	 *            the ID of the entry
	 * @param columnName
	 *            the name of the column
	 * @param value
	 *            the value updated
	 * @return true is the value is updated, false otherwise
	 * @throws WrapperException
	 * @throws InternalBackEndException
	 */
	public void updateColumn(String tableName, String primaryKey,
			String columnName, byte[] value) throws WrapperException,
			InternalBackEndException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"selectAll is not yet supported by the DHT type: " + type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) this.client;
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;

			try {
				node.setSimpleColumn(keyspace, columnFamily, primaryKey,
						columnName.getBytes("UTF8"), value, consistencyOnWrite);
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
				throw new WrapperException(
						"UnsupportedEncodingException with\n"
								+ "\t- columnFamily = " + columnFamily + "\n"
								+ "\t- primaryKey = " + primaryKey + "\n"
								+ "\t- columnFamily = " + columnName + "\n");
			}
		}
	}

	/**
	 * Get the value of an entry column
	 * 
	 * @param tableName
	 *            the name of the Table/ColumnFamily
	 * @param primaryKey
	 *            the ID of the entry
	 * @param columnName
	 *            the name of the column
	 * @return the value of the column
	 * @throws WrapperException
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public byte[] selectColumn(String tableName, String primaryKey,
			String columnName) throws WrapperException,
			InternalBackEndException, IOBackEndException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"selectColumn is not yet supported by the DHT type: "
							+ type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) this.client;
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;

			try {
				return node.getSimpleColumn(keyspace, columnFamily, primaryKey,
						columnName.getBytes("UTF8"), consistencyOnRead);
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
				throw new WrapperException(
						"UnsupportedEncodingException with\n"
								+ "\t- columnFamily = " + columnFamily + "\n"
								+ "\t- primaryKey = " + primaryKey + "\n"
								+ "\t- columnFamily = " + columnName + "\n");
			}
		}
	}

	/**
	 * Remove a specific column defined by the columnName
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @param columnName
	 * @throws WrapperException
	 * @throws InternalBackEndException
	 * @throws UnsupportedEncodingException
	 */
	public void removeColumn(String tableName, String key, String columnName)
			throws WrapperException, InternalBackEndException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"selectColumn is not yet supported by the DHT type: "
							+ type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) this.client;
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;
			try {
				node.removeColumn(keyspace, columnFamily, keyspace, columnName
						.getBytes("UTF8"), consistencyOnWrite);
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
				throw new WrapperException(
						"removeColumn failed because of a UnsupportedEncodingException");
			}
		}

	}

	/**
	 * Remove an entry in the columnFamily
	 * 
	 * @param keyspace
	 * @param columnFamily
	 * @param key
	 * @throws WrapperException
	 * @throws InternalBackEndException
	 * @throws UnsupportedEncodingException
	 */
	public void removeAll(String tableName, String key)
			throws WrapperException, InternalBackEndException {
		if (!type.equals(ClientType.CASSANDRA)) {
			throw new WrapperException(
					"selectColumn is not yet supported by the DHT type: "
							+ type);
		} else {
			// The main database is managed by Cassandra
			Cassandra node = (Cassandra) this.client;
			// keyspace is similar to the database name
			String keyspace = "Mymed";
			// columnFamily is similar to the table name
			String columnFamily = tableName;
			node
					.removeAll(keyspace, columnFamily, keyspace,
							consistencyOnWrite);
		}

	}

	/* --------------------------------------------------------- */
	/* Common DHT operations */
	/* --------------------------------------------------------- */
	/**
	 * Common put operation The DHT type is by default Cassandra
	 * 
	 * @param key
	 * @param value
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public void put(String key, byte[] value) throws IOBackEndException,
			InternalBackEndException {
		this.client.put(key, value);
	}

	/**
	 * Common get operation The DHT type is by default Cassandra
	 * 
	 * @param key
	 * @throws InternalBackEndException
	 * @throws IOBackEndException
	 */
	public byte[] get(String key) throws IOBackEndException,
			InternalBackEndException {
		return this.client.getValue(key);
	}

}
