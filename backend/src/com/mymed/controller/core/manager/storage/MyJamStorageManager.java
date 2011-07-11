package com.mymed.controller.core.manager.storage;

import java.io.File;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;
import org.apache.cassandra.thrift.SuperColumn;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.myjam.locator.GeoLocationOutOfBoundException;
import com.mymed.myjam.locator.Location;
import com.mymed.myjam.locator.Locator;
import com.mymed.myjam.type.MyJamTypes.ReportType;
import com.mymed.myjam.type.MNewReportBean;
import com.mymed.myjam.type.MReportBean;
import com.mymed.myjam.type.ReportId;
import com.mymed.myjam.type.MReportInfoBean;
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
	
	public static String KEYSPACE ="myJamKeyspace";

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
	public void insertReport(MNewReportBean report) throws InternalBackEndException{
		try {
			Map<String,Map<String,List<Mutation>>> reportInsertionMap = new HashMap<String,Map<String,List<Mutation>>>();
			boolean insert = false;

			/**
			 * Data preparation
			 */
			Map<String,String> reportValuesMap = report.getMapAttributes();
			//Map<String,byte[]> reportValuesMap = report.getAttributeToMap();

			long locId = Locator.getLocationId((double) (report.getLatitude()/1E6),
					(double) (report.getLongitude()/1E6));
			String areaId = String.valueOf(Locator.getAreaId(locId));
			/** The convention is to use microseconds since 1 Jenuary 1970*/
			long timestamp = (long) (System.currentTimeMillis()*1E3);									
			ReportId reportId = new ReportId(timestamp,userId);
			/** Transport Opening*/
			wrapper.open();
			wrapper.set_keyspace(KEYSPACE);

			/** Check if the position is already occupied.*/
			ColumnPath columnPath = new ColumnPath("Location");
			columnPath.setSuper_column(MConverter.longToByteBuffer(locId));
			columnPath.setColumn(MConverter.stringToByteBuffer(report.getReportType().name()));
			try {
				//TODO Define the consistency level to use on this operation.
				wrapper.get(areaId, columnPath, consistencyOnRead);
			} catch (IOBackEndException e) {
				insert = true;
			}
			if (!insert)
				throw new InternalBackEndException("Position occupied.");
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
			//TODO Integrate with getAttibuteToMap (AbstractMBean)
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
			wrapper.batch_mutate(reportInsertionMap, consistencyOnWrite);
		} catch (InternalBackEndException e) {
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (GeoLocationOutOfBoundException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (IllegalArgumentException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} finally {
			wrapper.close();
		}
	}

	/**
	 * Returns a list of reports, located in the circular area defined by latitude,
	 * longitude and radius.
	 * @param latitude
	 * @param longitude
	 * @param diameter
	 * @return
	 * @throws InternalBackEndException
	 */
	public List<MReportBean> getReports(double latitude,double longitude,int diameter) throws InternalBackEndException{
		List<MReportBean> resultReports = new LinkedList<MReportBean>();
		try{
			/**
			 * Data structures preparation
			 */
			List<ColumnOrSuperColumn> reports = new LinkedList<ColumnOrSuperColumn>();
			List<long[]> covId = Locator.getCoveringLocationId(latitude, longitude, diameter);
			List<String> areaIds = new LinkedList<String>();
			Iterator<long[]> covIdIterator = covId.iterator();
			/**
			 * Cassandra calls
			 */
			wrapper.open();
			wrapper.set_keyspace(KEYSPACE);
			while(covIdIterator.hasNext()){
				long[] range = covIdIterator.next();
				long startAreaId = Locator.getAreaId(range[0]);
				long endAreaId = Locator.getAreaId(range[1]);
				for (long ind=startAreaId;ind<=endAreaId;ind++){
					areaIds.add(String.valueOf(ind));
				}
				SliceRange locRange = new SliceRange();
				SlicePredicate locPred = new SlicePredicate().setSlice_range(locRange);
				locRange.start = MConverter.longToByteBuffer(range[0]);
				locRange.finish = MConverter.longToByteBuffer(range[1]);
				Map<ByteBuffer,List<ColumnOrSuperColumn>> mapRep = wrapper.multiget_slice(areaIds, new ColumnParent("Location"), locPred, consistencyOnRead);
				Iterator<ByteBuffer> mapRepoIterator = mapRep.keySet().iterator();
				while(mapRepoIterator.hasNext()){
					ByteBuffer locByteBuff = mapRepoIterator.next();
					reports.addAll(mapRep.get(locByteBuff));
				}			
				areaIds.clear();
			}
			/**
			 * Data processing
			 */
			Iterator<ColumnOrSuperColumn> reportIterator = reports.iterator();
			while (reportIterator.hasNext()){
				SuperColumn tempSc = reportIterator.next().getSuper_column();
				long posId = MConverter.byteBufferToLong(ByteBuffer.wrap(tempSc.getName()));
				Location reportLoc = Locator.getLocationFromId(posId);
				double distance = reportLoc.distanceGCTo(new Location(latitude,longitude));
				if (distance <= diameter){
					Iterator<Column> sColIterator = tempSc.getColumnsIterator();
					while (sColIterator.hasNext()){
						Column currCol = sColIterator.next();
						MReportBean reportBean = new MReportBean();
						reportBean.setReportType(ReportType.valueOf(MConverter.byteBufferToString(
								ByteBuffer.wrap(currCol.getName()))));
						ReportId repId = ReportId.parseByteBuffer(ByteBuffer.wrap(currCol.getValue()));
						reportBean.setReportId(repId.toString());
						reportBean.setLatitude((int) (reportLoc.getLatitude()*1E6));
						reportBean.setLongitude((int) (reportLoc.getLongitude()*1E6));
						resultReports.add(reportBean);
					}
				}
			}			
			return resultReports;
		} catch (InternalBackEndException e) {
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (GeoLocationOutOfBoundException e){
			throw new InternalBackEndException(e.getMessage());
		} catch (IllegalArgumentException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		} catch (WrongFormatException e){
			throw new InternalBackEndException("Wrong report Id: "+e.getMessage());
		} finally {
			wrapper.close();
		}
	}

	public MReportInfoBean getReportInfo(ReportId reportId) throws InternalBackEndException{
		byte[] emptyByteArray = new byte[0];
		MReportInfoBean resultReportInfo = new MReportInfoBean();
		
		SliceRange sr = new SliceRange();
		sr.setStart(emptyByteArray);
		sr.setFinish(emptyByteArray);
		sr.setCount(5);
		try{
			wrapper.open();
			wrapper.set_keyspace(KEYSPACE);
			List<ColumnOrSuperColumn> listCoS = wrapper.get_slice(reportId.toString(), new ColumnParent().setColumn_family("Report"),
					new SlicePredicate().setSlice_range(sr), consistencyOnRead);
			//resultReportInfo.getClass().
			return resultReportInfo;
		}catch(InternalBackEndException e){
			throw new InternalBackEndException("Wrong parameter: "+e.getMessage());
		}finally{
			wrapper.close();
		}
	}


}
