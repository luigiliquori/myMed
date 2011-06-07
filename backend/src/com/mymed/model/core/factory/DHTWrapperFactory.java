package com.mymed.model.core.factory;

import java.net.UnknownHostException;
import java.util.ArrayList;
import java.util.List;

import org.apache.cassandra.thrift.ColumnPath;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.AbstractDHTWrapper;
import com.mymed.model.core.wrappers.cassandra.api06.CassandraWrapper;
import com.mymed.model.core.wrappers.chord.ChordWrapper;
import com.mymed.model.core.wrappers.kad.KadWrapper;

import edu.lognet.core.protocols.p2p.IDHT;
import edu.lognet.core.protocols.p2p.exception.NodeException;

/**
 * Represent a factory of no-sql database node
 * 
 * @author lvanni
 * 
 */
public class DHTWrapperFactory implements IDHTWrapperFactory {

	/*
	 * If the client is not a Freerider, (^Cassandra) the running node must join
	 * an existing dht network before use it
	 */
	private static void connectNode(IDHT node, String networkID)
			throws InternalBackEndException {
		try {
			StorageManager storageManager = new StorageManager();
			byte[] trackerData = storageManager.selectColumn("Services",
					"tracker", networkID);

			String addressList = "";
			if (trackerData != null) {
				addressList = new String(trackerData, "UTF8");
				String[] address = addressList.split(",");
				List<String> addressDown = new ArrayList<String>();
				for (String a : address) {
					if (!a.equals("")) {
						String host = a.split(":")[0];
						try {
							int port = Integer.parseInt(a.split(":")[1]);
							System.out.print("Trying to join: " + host + ":"
									+ port + "...........");
							node.join(host, port);
							System.out.println("OK");
							break;
						} catch (NodeException e) {
							System.err.println("Fail!");
							// e.printStackTrace();
							System.err
									.println("Tracker updating, please wait...\n");
							addressDown.add(a); // blacklist this address
						}
					}
				}

				// Construct the new list
				addressList = "";
				for (String a : address) {
					if (!a.equals("") && !addressDown.contains(a)) {
						addressList += a + ",";
					}
				}
			}

			// update the tracker
			storageManager.insertColumn("Services", "tracker", networkID,
					(addressList + node.getThisNode().getIp() + ":" + node
							.getThisNode().getPort()).getBytes("UTF8"));

			System.out.println("Node started!");
		} catch (Exception e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}

	private static void disconnectNode(IDHT node, String networkID)
			throws InternalBackEndException {
		try {
			StorageManager storageManager = new StorageManager();
			byte[] trackerData = storageManager.selectColumn("Services",
					"tracker", networkID);

			String addressList = "";
			if (trackerData != null) {
				addressList = new String(trackerData, "UTF8");

				String[] address = addressList.split(",");
				List<String> addressDown = new ArrayList<String>();
				// Construct the new list
				addressList = "";
				for (String a : address) {
					if (!a.equals("")
							&& !a.equals(node.getThisNode().getIp() + ":"
									+ node.getThisNode().getPort())) {
						addressList += a + ",";
					}
				}

				// update the tracker
				storageManager.insertColumn("Services", "tracker", networkID,
						addressList.getBytes("UTF8"));
			}
		} catch (Exception e) {
			throw new InternalBackEndException(e.getMessage());
		}
	}

	/* --------------------------------------------------------- */
	/* public static methods */
	/* --------------------------------------------------------- */
	/**
	 * Return a node of type IDHT
	 * 
	 * @param type
	 *            type of the node
	 * @return IDHT No-sql database node
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 * @throws UnknownHostException
	 */
	public static AbstractDHTWrapper createDHTWrapper(WrapperType type,
			WrapperConfiguration conf) throws InternalBackEndException {
		switch (type) {
		case CASSANDRA:
			CassandraWrapper cassandraCli = CassandraWrapper.getInstance();
			cassandraCli.setup(conf.getCassandraListenAddress(), conf
					.getThriftPort());
			return cassandraCli;
		case CHORD:
			ChordWrapper chordCli = ChordWrapper.getInstance();
			chordCli.setup(conf.getChordListenAddress(), conf
					.getChordStoragePort());
			connectNode(chordCli.getNode(), "chord");
			return chordCli;
		case KAD:
			KadWrapper kadCli = KadWrapper.getInstance();
			kadCli.setup(conf.getKadListenAddress(), conf.getKadStoragePort());
			connectNode(kadCli.getNode(), "kad");
			return kadCli;
		default:
			cassandraCli = CassandraWrapper.getInstance();
			cassandraCli.setup(conf.getCassandraListenAddress(), conf
					.getThriftPort());
			return cassandraCli;
		}
	}

	/**
	 * Destroy the running node used by the client
	 * 
	 * @param type
	 *            type of the node
	 * @throws IOBackEndException
	 * @throws InternalBackEndException
	 */
	public static void destroyDHTWrapper(WrapperType type)
			throws InternalBackEndException, IOBackEndException {
		switch (type) {
		case CHORD:
			ChordWrapper chordCli = ChordWrapper.getInstance();
			disconnectNode(chordCli.getNode(), "chord");

			chordCli.getNode().kill();
			break;
		case KAD:
			KadWrapper kadCli = KadWrapper.getInstance();
			disconnectNode(kadCli.getNode(), "kad");
			kadCli.getNode().kill();
			break;
		default:
			break;
		}
	}
}
