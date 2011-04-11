package com.mymed.model.core.data.dht.factory;

import java.io.UnsupportedEncodingException;
import java.net.UnknownHostException;
import java.util.ArrayList;
import java.util.List;

import com.mymed.model.core.data.dht.configuration.Config;
import com.mymed.model.core.data.dht.protocol.Cassandra;
import com.mymed.model.core.data.dht.protocol.Chord;
import com.mymed.model.core.data.dht.protocol.Kad;
import com.mymed.model.core.wrapper.Wrapper;

import edu.lognet.core.protocols.p2p.IDHT;
import edu.lognet.core.protocols.p2p.exception.NodeException;

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
			List<String> addressDown = new ArrayList<String>();
			for (String a : address) {
				if (!a.equals("")) {
					String host = a.split(":")[0];
					try {
						int port = Integer.parseInt(a.split(":")[1]);
						System.out.print("Trying to join: " + host + ":" + port
								+ "...........");
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
		cassandraCli.setSimpleColumn("Mymed", "Services", "tracker", networkID
				.getBytes("UTF8"), (addressList + node.getThisNode().getIp()
						+ ":" + node.getThisNode().getPort()).getBytes("UTF8"),
						Wrapper.consistencyOnWrite);

		System.out.println("Node started!");
	}

	private static void disconnectNode(IDHT node, String networkID)
	throws UnsupportedEncodingException {
		// Cassandra is use as a tracker
		Cassandra cassandraCli = Cassandra.getInstance();
		byte[] trackerData = cassandraCli.getSimpleColumn("Mymed", "Services",
				"tracker", networkID.getBytes("UTF8"),
				Wrapper.consistencyOnRead);
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
			cassandraCli.setSimpleColumn("Mymed", "Services", "tracker", networkID
					.getBytes("UTF8"), addressList.getBytes("UTF8"),
							Wrapper.consistencyOnWrite);
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
	 * @throws UnknownHostException
	 */
	public static IDHTClient createDHTClient(IDHTClient.ClientType type,
			Config conf) {
		try {
			switch (type) {
			case CASSANDRA:
				Cassandra cassandraCli = Cassandra.getInstance();
				cassandraCli.setup(conf.getCassandraListenAddress(), conf
						.getThriftPort());
				return cassandraCli;
			case CHORD:
				Chord chordCli = Chord.getInstance();
				chordCli.setup(conf.getChordListenAddress(), conf
						.getChordStoragePort());
				connectNode(chordCli.getNode(), "chord");
				return chordCli;
			case KAD:
				Kad kadCli = Kad.getInstance();
				kadCli.setup(conf.getKadListenAddress(), conf
						.getKadStoragePort());
				connectNode(kadCli.getNode(), "kad");
				return kadCli;
			default:
				cassandraCli = Cassandra.getInstance();
				cassandraCli.setup(conf.getCassandraListenAddress(), conf
						.getThriftPort());
				return cassandraCli;
			}
		} catch (UnsupportedEncodingException e) {
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
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
