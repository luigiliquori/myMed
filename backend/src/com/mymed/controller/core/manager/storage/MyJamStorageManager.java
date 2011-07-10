package com.mymed.controller.core.manager.storage;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.SuperColumn;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.myjam.locator.GeoLocationOutOfBoundException;
import com.mymed.myjam.locator.Locator;
import com.mymed.myjam.type.NewReport;
import com.mymed.myjam.type.ReportId;
import com.mymed.myjam.type.WrongFormatException;
import com.mymed.utils.MConverter;
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

	/**
	 * TODO This class must be aware of the username and the userId.
	 */
	private final String userId = "iacopoId";
	private final String userName = "iacopo"; 
	
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

	/* --------------------------------------------------------- */
	/* public methods */
	/* --------------------------------------------------------- */
	
	/**
	 * Insert a new report on the DB
	 * @param report
	 * @throws InternalBackEndException
	 */
	public void insertReport(NewReport report) throws InternalBackEndException{
		try {
			Map<String,Map<String,List<Mutation>>> reportInsertionMap = new HashMap<String,Map<String,List<Mutation>>>();	
			
			/**
			 * Data preparation
			 */
			Map<String,String> reportValuesMap = report.getMapAttributes();
			
			long locId = Locator.getLocationId(report.getPos().getLatitudeDegree(),
					report.getPos().getLongitudeDegree());
			String areaId = String.valueOf(Locator.getAreaId(locId));
			/** The convention is to use microseconds since 1 Jenuary 1970*/
			long timestamp = (long) (System.currentTimeMillis()*1E3);									
			ReportId reportId = new ReportId(timestamp,userId);
			
			/**
			 * SuperColumn insertion in CF Location 
			 **/
			Map<String,List<Mutation>> locationMap = new HashMap<String,List<Mutation>>();
			List<Mutation> locMutationList = new ArrayList<Mutation>(1);
			locationMap.put("Location",locMutationList);
			Mutation locMutation = new Mutation();
			locMutationList.add(locMutation);
			SuperColumn locationSC = new SuperColumn();
			Column locCol = new Column(
					MConverter.stringToByteBuffer(report.getReportType().name()),
					reportId.ReportIdAsByteBuffer(),
					timestamp);
			locCol.setTtl(report.getPermanence().getDuration());
			locationSC.setName(MConverter.longToByteBuffer(locId));
			locationSC.addToColumns(locCol);
			locMutation.setColumn_or_supercolumn(
					new ColumnOrSuperColumn().setSuper_column(locationSC));
			//Insertion in the map
			reportInsertionMap.put(areaId, locationMap);
			
			/**
			 * Columns insertion in CF Report 
			 **/
			Map<String,List<Mutation>> reportMap = new HashMap<String,List<Mutation>>();
			List<Mutation> reportMutationList = new ArrayList<Mutation>(5);
			reportMap.put("Report",reportMutationList);
			Mutation userMutation = new Mutation();
			userMutation.setColumn_or_supercolumn(new ColumnOrSuperColumn().setColumn(
					new Column(MConverter.stringToByteBuffer("user"),
							MConverter.stringToByteBuffer(userName),
							timestamp)));
			reportMutationList.add(userMutation);
			Iterator<String> iterator = reportValuesMap.keySet().iterator();
			while (iterator.hasNext()){
				String key = iterator.next();
				Mutation mutation = new Mutation();
				mutation.setColumn_or_supercolumn(new ColumnOrSuperColumn().setColumn(
						new Column(MConverter.stringToByteBuffer(key),
								MConverter.stringToByteBuffer(reportValuesMap.get(key)),
								timestamp)));
				reportMutationList.add(mutation);
			}
			//Insertion in the map
			reportInsertionMap.put(reportId.toString(), reportMap);
			
			/**
			 * Columns insertion in CF UserReport 
			 **/
			Map<String,List<Mutation>> urMap = new HashMap<String,List<Mutation>>();
			List<Mutation> urMutationList = new ArrayList<Mutation>(1);
			urMap.put("UserReport",urMutationList);
			Mutation urMutation = new Mutation();
			urMutation.setColumn_or_supercolumn(new ColumnOrSuperColumn().setColumn(
					new Column(reportId.ReportIdAsByteBuffer(),
							MConverter.stringToByteBuffer(" "),
							timestamp)));
			urMutationList.add(urMutation);
			//Insertion in the map
			reportInsertionMap.put(userId, urMap);		
			
			//Cassandra call
			wrapper.open();
			wrapper.set_keyspace("myJamKeyspace");
			wrapper.batch_mutate(reportInsertionMap, consistencyOnWrite);
		} catch (InternalBackEndException e) {
			e.printStackTrace();
		} catch (GeoLocationOutOfBoundException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (IllegalArgumentException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} finally {
			wrapper.close();
		}
	}
	
}
