package com.mymed.controller.core.manager.storage;

import java.io.File;

import org.apache.cassandra.thrift.ConsistencyLevel;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.myjam.type.NewReport;
/**
 * Storage manager created ad hoc for myJam application.
 * @author iacopo
 *
 */
public class MyJamStorageManager {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/**
	 * The Default path of the wrapper config file
	 */
	public static String CONFIG_PATH = "/home/iacopo/workspace/mymed/backend/conf/myJamConfig.xml";
	
	public static ConsistencyLevel consistencyOnWrite = ConsistencyLevel.ONE;
	public static ConsistencyLevel consistencyOnRead = ConsistencyLevel.ONE;

	private final CassandraWrapper wrapper;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Default Constructor: will create a ServiceManger on top of a Cassandra
	 * Wrapper
	 * 
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public MyJamStorageManager() throws InternalBackEndException {
		this(new WrapperConfiguration(new File(CONFIG_PATH)));
	}
	
	/**
	 * /** will create a ServiceManger on top of the WrapperType And use the
	 * specific configuration file for the transport layer
	 * 
	 * @param type
	 *            Type of DHTClient used
	 * @param conf
	 *            The configuration of the transport layer
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public MyJamStorageManager(final WrapperConfiguration conf) throws InternalBackEndException {
		wrapper = new CassandraWrapper(conf.getCassandraListenAddress(), conf.getThriftPort());
	}

	public void insertReport(NewReport report) {
		// TODO Auto-generated method stub
		
	}
	
	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
}
