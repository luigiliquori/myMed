package com.mymed.controller.example;

import java.io.File;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.List;

import org.apache.cassandra.thrift.CfDef;
import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnDef;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.KsDef;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.model.core.wrappers.cassandra.api07.StringConverter;

public class MyMedCassandra07 {

	private static final String TEST_KEYSPACE = "TestKeyspace";
	private static final String TEST_COLUMN_FAM = "TestColumnFamily";
	private static final String COL_NAME = "full_name";
	private static final String STRATEGY = "SimpleStrategy";

	public static void main(final String[] args) {

		final WrapperConfiguration conf = new WrapperConfiguration(new File("/local/mymed/backend/conf/config-new.xml"));
		final CassandraWrapper wrapper = CassandraWrapper.getInstance();

		final String listenAddress = conf.getCassandraListenAddress();
		final int thriftPort = conf.getThriftPort();

		System.out.println("Connection information:");
		System.out.println("\tListen Address: " + listenAddress);
		System.out.println("\tThrift Port   : " + thriftPort);
		System.out.println("\n");

		wrapper.setup(listenAddress, thriftPort);

		try {
			System.out.println("API and cluster information:");
			System.out.println("\tThrift Version: " + wrapper.describe_version());
			System.out.println("\tCluster Name  : " + wrapper.describe_cluster_name());
			System.out.println("\n");

			System.out.println("Keyspaces information:");
			for (final KsDef def : wrapper.describe_keyspaces()) {
				System.out.println("\tName              : " + def.name);
				System.out.println("\tReplication Factor: " + def.replication_factor);
				System.out.println("\tStrategy Class    : " + def.strategy_class);
				System.out.println("\n");
			}

			final CfDef cfDef = new CfDef(TEST_KEYSPACE, TEST_COLUMN_FAM);

			final ByteBuffer columnName = StringConverter.stringToByteBuffer(COL_NAME);
			final ColumnDef columnDef = new ColumnDef(columnName, "UTF8Type");

			final List<ColumnDef> cDefList = new ArrayList<ColumnDef>();
			cDefList.add(columnDef);

			cfDef.setColumn_metadata(cDefList);

			final List<CfDef> cfDefList = new ArrayList<CfDef>();
			cfDefList.add(cfDef);

			final KsDef ksDef = new KsDef(TEST_KEYSPACE, STRATEGY, 1, cfDefList);

			System.out.println("Adding new keyspace " + TEST_KEYSPACE + "...");
			final String newSchemaId = wrapper.system_add_keyspace(ksDef);
			System.out.println("New schema ID: " + newSchemaId + "\n");

			final KsDef def = wrapper.describe_keyspace(TEST_KEYSPACE);

			System.out.println(TEST_KEYSPACE + " information:");
			System.out.println("\tName              : " + def.name);
			System.out.println("\tReplication Factor: " + def.replication_factor);
			System.out.println("\tStrategy Class    : " + def.strategy_class);
			System.out.println("\n");

			final ColumnParent parent = new ColumnParent();
			parent.setColumn_family(TEST_COLUMN_FAM);

			final ByteBuffer name = StringConverter.stringToByteBuffer(COL_NAME);
			final ByteBuffer value = StringConverter.stringToByteBuffer("Milo Casagrande");

			final Column column = new Column(name, value, System.currentTimeMillis());

			wrapper.insert("1", parent, column, ConsistencyLevel.ONE);

			final ColumnPath path = new ColumnPath(TEST_COLUMN_FAM);
			path.setColumn(name);

			final ColumnOrSuperColumn result = wrapper.get("1", path, ConsistencyLevel.ONE);
			System.err.println(result.isSetColumn());
			final Column c = result.getColumn();
			System.out.println(StringConverter.byteBufferToString(c.name));
			System.out.println(StringConverter.byteBufferToString(c.value));

			final ByteBuffer columnName2 = StringConverter.stringToByteBuffer("birth_date");
			final ColumnDef columnDef2 = new ColumnDef(columnName2, "LongType");

			final List<ColumnDef> cDef = cfDef.column_metadata;
			cDef.add(columnDef2);

			cfDef.setColumn_metadata(cDef);
			cfDef.setComment("This is a test");

			System.err.println("Updating the ColumFamily definition...");
			wrapper.system_update_column_family(cfDef);

			// method to remove the defined keyspace
			// System.out.println("Deleting keyspace " + TEST_KEYSPACE + "...");
			// newSchemaId = wrapper.system_drop_keyspace(TEST_KEYSPACE);
			// System.out.println("New schema ID: " + newSchemaId);

		} catch (final InternalBackEndException ex) {
			ex.printStackTrace();
		} catch (final IOBackEndException ex) {
			ex.printStackTrace();
		}
	}
}
