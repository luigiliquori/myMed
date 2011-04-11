package com.mymed.model.core.data.dht.factory;

import java.io.UnsupportedEncodingException;
import java.net.UnknownHostException;

import com.mymed.model.core.data.dht.configuration.Config;
import com.mymed.model.core.data.dht.protocol.Cassandra;
import com.mymed.model.core.data.dht.protocol.Chord;
import com.mymed.model.core.data.dht.protocol.Kad;
import com.mymed.model.core.wrapper.Wrapper;

import edu.lognet.core.protocols.p2p.IDHT;

/**
 * Represent a factory of no-sql database node
 * 
 * @author lvanni
 * 
 */
public class DHTClientFactory {

	// If the client is not a Freerider, (^Cassandra)
	// the running node must join
	// an existing dht network before use it
	private static void connectNode(IDHT node, String networkID)
	throws UnsupportedEncodingException {
		// Cassandra is use as a tracker
		Cassandra cassandraCli = Cassandra.getInstance();
		// cassandraCli.setSimpleColumn("Mymed", "Services", "tracker",
		// "address", value, level)
		byte[] trackerData = cassandraCli.getSimpleColumn("Mymed", "Services",
				"tracker", networkID.getBytes("UTF8"),
				Wrapper.consistencyOnRead);
		String addressList = "";
		if (trackerData != null) {
			addressList = new String(trackerData, "UTF8");

			String[] address = addressList.split(",");
			for(String a : address){
				if(!a.equals("")){
					String host = a.split(":")[0];
					int port = Integer.parseInt(a.split(":")[1]);
					node.join(host, port);
				}
			} 
		}
		cassandraCli.setSimpleColumn("Mymed", "Services", "tracker", networkID
				.getBytes("UTF8"), (addressList + ","
						+ node.getThisNode().getIp() + ":" + node.getThisNode()
						.getPort()).getBytes("UTF8"), Wrapper.consistencyOnWrite);
	}

	// If the client is not a Freerider, (^Cassandra)
	// the running node must disconnect
	// the existing dht network
	private static void disconnectNode(IDHT node, String networkID)
	throws UnsupportedEncodingException {
		// Cassandra is use as a tracker
		Cassandra cassandraCli = Cassandra.getInstance();
		// cassandraCli.setSimpleColumn("Mymed", "Services", "tracker",
		// "address", value, level)
		byte[] trackerData = cassandraCli.getSimpleColumn("Mymed", "Services",
				"tracker", networkID.getBytes("UTF8"),
				Wrapper.consistencyOnRead);
		String addressList = "";
		if (trackerData != null) {
			addressList = new String(trackerData, "UTF8");
			addressList.replace("," + node.getThisNode().getIp() + ":"
					+ node.getThisNode().getPort(), "");
			addressList.replace(node.getThisNode().getIp() + ":"
					+ node.getThisNode().getPort() + ",", "");
			addressList.replace(node.getThisNode().getIp() + ":"
					+ node.getThisNode().getPort(), "");
		}
		cassandraCli.setSimpleColumn("Mymed", "Services", "tracker", networkID
				.getBytes("UTF8"), addressList.getBytes("UTF8"),
				Wrapper.consistencyOnWrite);
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
	 * @throws UnknownHostException
	 */
	public static IDHTClient createDHTClient(IDHTClient.ClientType type, Config conf) {
		try {
			switch (type) {
			case CASSANDRA:
				Cassandra cassandraCli = Cassandra.getInstance();
				cassandraCli.setup(conf.getCassandraListenAddress(), conf.getThriftPort());
				return cassandraCli;
			case CHORD:
				Chord chordCli = Chord.getInstance();
				chordCli.setup(conf.getChordListenAddress(), conf.getChordStoragePort());
				connectNode(chordCli.getNode(), "chord");
				return chordCli;
			case KAD:
				Kad kadCli = Kad.getInstance();
				kadCli.setup(conf.getKadListenAddress(), conf.getKadStoragePort());
				connectNode(kadCli.getNode(), "kad");
				return kadCli;
			default:
				cassandraCli = Cassandra.getInstance();
				cassandraCli.setup(conf.getCassandraListenAddress(), conf.getThriftPort());
				return cassandraCli;
			}
		}  catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		return null;
	}

	/**
	 * Destroy the running node used by the client
	 * 
	 * @param type
	 *            type of the node
	 */
	public static void destroyDHTClient(IDHTClient.ClientType type) {
		try {
			switch (type) {
			case CHORD:
				Chord chordCli = Chord.getInstance();
				disconnectNode(chordCli.getNode(), "chord");
				chordCli.getNode().kill();
				break;
			case KAD:
				Kad kadCli = Kad.getInstance();
				disconnectNode(kadCli.getNode(), "kad");
				kadCli.getNode().kill();
				break;
			default:
				break;
			}
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
	}
}
