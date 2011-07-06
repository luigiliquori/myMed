package com.mymed.model.core.configuration;

import java.io.File;
import java.io.IOException;
import java.net.InetAddress;
import java.net.UnknownHostException;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

/**
 * 
 * @author lvanni
 */
public class WrapperConfiguration {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** Cassandra */
	private String cassandraListenAddress;
	private int thriftPort;

	/** Synapse */
	private String chordListenAddress;
	private int chordStoragePort;
	private String kadListenAddress;
	private int kadStoragePort;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * Register a new Configuration for the backend Node
	 * 
	 * @param chordListenAddress
	 * @param chordStoragePort
	 * @param kadListenAddress
	 * @param kadStoragePort
	 */
	public WrapperConfiguration(String cassandraListenAddress, int thriftPort,
			String chordListenAddress, int chordStoragePort,
			String kadListenAddress, int kadStoragePort) {
		this.cassandraListenAddress = cassandraListenAddress;
		this.thriftPort = thriftPort;
		this.chordListenAddress = chordListenAddress;
		this.chordStoragePort = chordStoragePort;
		this.kadListenAddress = kadListenAddress;
		this.kadStoragePort = kadStoragePort;
	}

	/**
	 * Register a new Configuration for the backend Node
	 * 
	 * @param file
	 *            THe xml configuration file
	 */
	public WrapperConfiguration(File file) {
		try {
			DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
			DocumentBuilder db = dbf.newDocumentBuilder();
			Document doc = db.parse(file);
			doc.getDocumentElement().normalize();
			NodeList backend = doc.getElementsByTagName("backend");
			NodeList config = backend.item(0).getChildNodes();

			for (int i = 0; i < config.getLength(); i++) {
				Node fstNode = config.item(i);
				if (fstNode.getNodeType() == Node.ELEMENT_NODE) {
					NodeList cassandraInfo = config.item(i).getChildNodes();
					if (config.item(i).getNodeName().equals("cassandra")) {
						for (int c = 0; c < cassandraInfo.getLength(); c++) {
							Node info = cassandraInfo.item(c);
							if (info.getNodeType() == Node.ELEMENT_NODE) {
								if (info.getNodeName().equals("ListenAddress")) {
									this.cassandraListenAddress = info
											.getFirstChild().getTextContent();
								} else if (info.getNodeName().equals(
										"ThriftPort")) {
									this.thriftPort = Integer.parseInt(info
											.getFirstChild().getTextContent());
								}
							}
						}
					} else if (config.item(i).getNodeName().equals("synapse")) {
						NodeList dhts = config.item(i).getChildNodes();
						for (int s = 0; s < dhts.getLength(); s++) {
							Node node = dhts.item(s);
							if (node.getNodeType() == Node.ELEMENT_NODE) {
								NodeList dhtInfo = dhts.item(s).getChildNodes();
								String address = "";
								int port = 0;
								for (int j = 0; j < cassandraInfo.getLength(); j++) {
									Node info = dhtInfo.item(j);
									if (info.getNodeType() == Node.ELEMENT_NODE) {
										if (info.getNodeName().equals(
												"ListenAddress")) {
											address = info.getFirstChild()
													.getTextContent();
										} else if (info.getNodeName().equals(
												"StoragePort")) {
											port = Integer.parseInt(info
													.getFirstChild()
													.getTextContent());
										}
									}
								}
								if (dhts.item(s).getNodeName().equals("chord")) {
									this.chordListenAddress = address;
									this.chordStoragePort = port;
								} else if (dhts.item(s).getNodeName().equals(
										"kad")) {
									this.kadListenAddress = address;
									this.kadStoragePort = port;
								}
							}
						}
					}
				}
			}
		} catch (ParserConfigurationException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (SAXException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// If the config xml file is not found, the configuration
			// will be defined with the default values
			String host = "127.0.0.1";
			try {
				host = InetAddress.getLocalHost().getHostAddress();
			} catch (UnknownHostException e1) {
				e1.printStackTrace();
			}
			this.cassandraListenAddress = host;
			this.thriftPort = 4201;
			this.chordListenAddress = host;
			this.chordStoragePort = 0;
			this.kadListenAddress = host;
			this.kadStoragePort = 0;
			System.out.println("\nWRARNING: no config xml file found!");
		}
	}

	@Override
	public String toString() {
		String str = "Cassandra:\n" + "\t ListenAddress = "
				+ this.cassandraListenAddress + "\n" + "\t ThriftPort = "
				+ this.thriftPort + "\n" + "Synapse:\n" + "\t. Chord:\n"
				+ "\t\t ListenAddress = " + this.chordListenAddress + "\n"
				+ "\t\t StoragePort = " + this.chordStoragePort + "\n"
				+ "\t. Kad:\n" + "\t\t ListenAddress = "
				+ this.kadListenAddress + "\n" + "\t\t StoragePort = "
				+ this.kadStoragePort + "\n";
		return str;
	}

	/* --------------------------------------------------------- */
	/* GETTER&SETTER */
	/* --------------------------------------------------------- */
	public String getCassandraListenAddress() {
		return cassandraListenAddress;
	}

	public void setCassandraListenAddress(String cassandraListenAddress) {
		this.cassandraListenAddress = cassandraListenAddress;
	}

	public int getThriftPort() {
		return thriftPort;
	}

	public void setThriftPort(int thriftPort) {
		this.thriftPort = thriftPort;
	}

	public String getChordListenAddress() {
		return chordListenAddress;
	}

	public void setChordListenAddress(String chordListenAddress) {
		this.chordListenAddress = chordListenAddress;
	}

	public int getChordStoragePort() {
		return chordStoragePort;
	}

	public void setChordStoragePort(int chordStoragePort) {
		this.chordStoragePort = chordStoragePort;
	}

	public String getKadListenAddress() {
		return kadListenAddress;
	}

	public void setKadListenAddress(String kadListenAddress) {
		this.kadListenAddress = kadListenAddress;
	}

	public int getKadStoragePort() {
		return kadStoragePort;
	}

	public void setKadStoragePort(int kadStoragePort) {
		this.kadStoragePort = kadStoragePort;
	}

	/* --------------------------------------------------------- */
	/* Test */
	/* --------------------------------------------------------- */
	public static void main(String args[]) {
		WrapperConfiguration conf = new WrapperConfiguration(new File(
				"./conf/config.xml"));
		System.out.println(conf);
	}
}
