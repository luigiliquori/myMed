package com.mymed.controller.example;

import java.io.File;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import org.apache.cassandra.thrift.CfDef;
import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnDef;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.IndexType;
import org.apache.cassandra.thrift.KsDef;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;
import org.apache.cassandra.thrift.TokenRange;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.model.core.wrappers.cassandra.api07.MConverter;

public class MyMedCassandra07 {

	private static final String TEST_KEYSPACE = "testkeyspace";
	private static final String TEST_COLUMN_FAM = "users";
	private static final String COL1_NAME = "full_name";
	private static final String COL2_NAME = "birth_date";
	private static final String STRATEGY = "SimpleStrategy";

	private static Map<String, String> names = new HashMap<String, String>();
	private static Map<String, Long> years = new HashMap<String, Long>();

	static {
		names.put("goofy", "Goofy");
		names.put("donald", "Donald Duck");
		names.put("mickey", "Mickey Mouse");
		names.put("minnie", "Minnie Mouse");

		years.put("goofy", Long.valueOf(1979));
		years.put("donald", Long.valueOf(1978));
		years.put("mickey", Long.valueOf(1978));
		years.put("minnie", Long.valueOf(1979));
	}

	public static void main(final String[] args) throws InternalBackEndException {

		final WrapperConfiguration conf = new WrapperConfiguration(new File("/local/mymed/backend/conf/config-new.xml"));

		final String listenAddress = conf.getCassandraListenAddress();
		final int thriftPort = conf.getThriftPort();

		System.out.println("Connection information:");
		System.out.println("\tListen Address: " + listenAddress);
		System.out.println("\tThrift Port   : " + thriftPort);
		System.out.println("\n");

		final CassandraWrapper wrapper = new CassandraWrapper(listenAddress, thriftPort);

		try {
			/*
			 * In order for all the operation to work, we need to open the
			 * connection to the Cassandra database... and close it at the end
			 * when all the operations have been done
			 */
			System.err.println("Opening Cassandra connection...");
			wrapper.open();

			System.out.println("API and cluster information:");
			System.out.println("\tThrift Version: " + wrapper.describe_version());
			System.out.println("\tCluster Name  : " + wrapper.describe_cluster_name());
			System.out.println("\tPartitioner   : " + wrapper.describe_partitioner());
			System.out.println("\n");

			for (final KsDef def : wrapper.describe_keyspaces()) {
				describeKeyspace(def);
			}

			/*
			 * To add a new keyspace, we need a column family definition with
			 * the definition of the column we want to insert
			 */
			final CfDef cfDef = new CfDef(TEST_KEYSPACE, TEST_COLUMN_FAM);

			final ByteBuffer columnName = MConverter.stringToByteBuffer(COL1_NAME);
			final ColumnDef columnDef = new ColumnDef(columnName, "UTF8Type");

			final List<ColumnDef> cDefList = new ArrayList<ColumnDef>();
			cDefList.add(columnDef);

			cfDef.setColumn_metadata(cDefList);

			final List<CfDef> cfDefList = new ArrayList<CfDef>();
			cfDefList.add(cfDef);

			final KsDef ksDef = new KsDef(TEST_KEYSPACE, STRATEGY, 1, cfDefList);

			System.out.println("Adding new keyspace " + TEST_KEYSPACE + "...");
			String newSchemaId = wrapper.system_add_keyspace(ksDef);
			System.out.println("New schema ID: " + newSchemaId + "\n");

			final KsDef def = wrapper.describe_keyspace(TEST_KEYSPACE);
			describeKeyspace(def);

			final ColumnParent parent = new ColumnParent(TEST_COLUMN_FAM);

			final ByteBuffer full_name = MConverter.stringToByteBuffer(COL1_NAME);
			final ByteBuffer birth_date = MConverter.stringToByteBuffer(COL2_NAME);

			// Set the workspace we want to work on
			System.err.println("Setting the workspace to work on...");
			wrapper.set_keyspace(TEST_KEYSPACE);
			System.out.println("Keyspace set to: " + TEST_KEYSPACE);

			System.err.println("Inserting some values...");
			final Iterator<Entry<String, String>> namesIter = names.entrySet().iterator();
			while (namesIter.hasNext()) {
				final String key = namesIter.next().getKey();
				final String value = names.get(key);

				final Column column = new Column(full_name, MConverter.stringToByteBuffer(value),
				        System.currentTimeMillis());

				wrapper.insert(key, parent, column, ConsistencyLevel.ONE);
			}

			final ColumnPath path = new ColumnPath(TEST_COLUMN_FAM);
			path.setColumn(full_name);

			System.err.println("Retrieving values for 'minnie'...");
			final ColumnOrSuperColumn result = wrapper.get("minnie", path, ConsistencyLevel.ONE);
			final Column c = result.getColumn();
			StringBuffer strBuf = new StringBuffer(200);
			strBuf.append("\tName: ");
			strBuf.append(MConverter.byteBufferToString(c.name));
			strBuf.append("\n\tValue: ");
			strBuf.append(MConverter.byteBufferToString(c.value));
			System.out.println(strBuf.toString());

			/*
			 * We add a new column definition for the already defined column
			 * family
			 */
			final ByteBuffer columnName2 = MConverter.stringToByteBuffer(COL2_NAME);
			final ColumnDef columnDef2 = new ColumnDef(columnName2, "LongType");
			columnDef2.setIndex_type(IndexType.KEYS);

			final List<ColumnDef> cDef = cfDef.column_metadata;
			cDef.add(columnDef2);

			cfDef.setColumn_metadata(cDef);
			cfDef.setComment("This is a comment about the keyspace!");

			/*
			 * Before updating a column family definition, it is necessary to
			 * retrieve the id of the column family definition we want to update
			 */
			final int cfId = wrapper.get_cf_id(TEST_KEYSPACE, TEST_COLUMN_FAM);
			cfDef.setId(cfId);

			System.err.println("Updating the ColumFamily definition...");
			wrapper.system_update_column_family(cfDef);

			System.err.println("Inserting some new data in the new column...");
			final Iterator<Entry<String, Long>> yearsIter = years.entrySet().iterator();
			while (yearsIter.hasNext()) {
				final String key = yearsIter.next().getKey();
				final Long value = years.get(key);

				final Column column = new Column(birth_date, MConverter.longToByteBuffer(value),
				        System.currentTimeMillis());

				wrapper.insert(key, parent, column, ConsistencyLevel.ONE);
			}

			final ColumnPath path2 = new ColumnPath(TEST_COLUMN_FAM);
			path2.setColumn(birth_date);

			System.err.println("Retrieving new values for 'minnie'...");
			final ColumnOrSuperColumn result2 = wrapper.get("minnie", path2, ConsistencyLevel.ONE);
			final Column col = result2.getColumn();
			strBuf = new StringBuffer(200);
			strBuf.append("\tName: ");
			strBuf.append(MConverter.byteBufferToString(col.name));
			strBuf.append("\n\tValue: ");
			strBuf.append(MConverter.byteBufferToLong(col.value));
			System.out.println(strBuf.toString());

			System.err.println("Updating Keyspace definition...");

			/*
			 * In order to update the keyspace definition, we need to pass an
			 * empty list of CfDef. The update action does not want any column
			 * family defined to updated the keyspace, but passing null is an
			 * error, so an empty list is used.
			 */
			final List<CfDef> famDef = new ArrayList<CfDef>();
			KsDef newDef = new KsDef(TEST_KEYSPACE, "LocalStrategy", 1, famDef);

			final KsDef oldDef = wrapper.describe_keyspace(TEST_KEYSPACE);
			describeKeyspace(oldDef);

			wrapper.system_update_keyspace(newDef);
			System.err.println("Keyspace definition updated.\n");

			newDef.clear();
			newDef = wrapper.describe_keyspace(TEST_KEYSPACE);
			describeKeyspace(newDef);

			final SlicePredicate predicate = new SlicePredicate();

			final List<ByteBuffer> columnNames = new ArrayList<ByteBuffer>();
			columnNames.add(full_name);
			columnNames.add(birth_date);

			predicate.setColumn_names(columnNames);

			/*
			 * Getting a slice means to get the columns specified in the
			 * SlicePredicate with a particular key
			 */
			System.err.println("Getting slice with column names...");
			final List<ColumnOrSuperColumn> slice = wrapper
			        .get_slice("minnie", parent, predicate, ConsistencyLevel.ONE);

			System.out.println("Slice retrieved. Number of columns retrieved: " + slice.size());

			final SliceRange sliceRange = new SliceRange();
			sliceRange.setStart(full_name);
			sliceRange.setFinish(birth_date);
			sliceRange.setReversed(true);
			sliceRange.setCount(1);

			predicate.clear();
			predicate.setSlice_range(sliceRange);

			System.err.println("Getting slice with SlicePredicate and count set to 1...");
			final List<ColumnOrSuperColumn> slice2 = wrapper.get_slice("minnie", parent, predicate,
			        ConsistencyLevel.ONE);

			System.out.println("Slice retrieved. Number of columns retrieved: " + slice2.size());

			System.err.println("Getting ring description...");
			final List<TokenRange> range = wrapper.describe_ring(TEST_KEYSPACE);
			System.out.println("Ring description: ");
			for (final TokenRange token : range) {
				System.out.println("\tStart token: " + token.start_token);
				System.out.println("\tEnd token  : " + token.end_token);

				final List<String> endPoints = token.getEndpoints();
				for (final String point : endPoints) {
					System.out.println("\t\tPoint      : " + point);
				}
			}

			// method to remove the defined keyspace
			System.out.println("Deleting keyspace " + TEST_KEYSPACE + "...");
			newSchemaId = wrapper.system_drop_keyspace(TEST_KEYSPACE);
			System.out.println("New schema ID: " + newSchemaId);

		} catch (final InternalBackEndException ex) {
			ex.printStackTrace();
		} catch (final IOBackEndException ex) {
			ex.printStackTrace();
		} finally {
			// Here we finally (no pun intended) close the connection
			wrapper.close();
		}
	}
	private static void describeKeyspace(final KsDef definition) throws InternalBackEndException {

		System.out.println("Keyspace " + definition.getName() + " information:");
		System.out.println("\tName              : " + definition.getName());
		System.out.println("\tReplication Factor: " + definition.getReplication_factor());
		System.out.println("\tStrategy Class    : " + definition.getStrategy_class());

		if (definition.getStrategy_optionsSize() > 0) {
			final Map<String, String> options = definition.getStrategy_options();
			if (options != null) {
				for (final String key : options.keySet()) {
					System.err.println("\tStrategy options: ");
					System.err.println("\t" + key);
					System.err.println("\t" + options.get(key));
				}
			}
		}

		final Iterator<CfDef> iter = definition.getCf_defsIterator();
		while (iter.hasNext()) {
			final CfDef cfDef = iter.next();
			System.err.println("\tColumn name       : " + cfDef.getName());
			System.out.println("\tKey chace size    : " + cfDef.getKey_cache_size());
			System.out.println("\tMetadata size     : " + cfDef.getColumn_metadataSize());
			System.out.println("\tComparator type   : " + cfDef.getComparator_type());
			System.out.println("\tValidation class  : " + cfDef.getDefault_validation_class());
			System.out.println("\tColumn type       : " + cfDef.getColumn_type());
			final List<ColumnDef> colDefList = cfDef.getColumn_metadata();
			for (final ColumnDef colDef : colDefList) {
				final ByteBuffer buf = ByteBuffer.wrap(colDef.getName());
				System.out.println("\tColumn def. name  : " + MConverter.byteBufferToString(buf));
				System.out.println("\tIndex name        : " + colDef.getIndex_name());
				System.out.println("\tValidation class  : " + colDef.getValidation_class());
				System.out.println("\tIndex type        : " + colDef.getIndex_type());
			}
		}

		System.out.println("\n");
	}
}
