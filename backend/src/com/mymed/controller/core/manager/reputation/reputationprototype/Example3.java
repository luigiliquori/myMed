/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputationprototype;

import org.apache.cassandra.thrift.Cassandra;
import org.apache.cassandra.thrift.CfDef;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;
/**
 *
 * @author piccolo
 */
public class Example3 {
    
        private static final String HOST = "localhost";
    private static final int PORT = 4201;
    public static void main(String a[]) throws Exception{
        
        //Connect to Server
        TTransport tr = new TSocket(HOST, PORT);
        TFramedTransport tf = new TFramedTransport(tr);
        TProtocol proto = new TBinaryProtocol(tf);
        Cassandra.Client client = new Cassandra.Client(proto);
        tr.open();
        
        client.set_keyspace("Keyspace2");
        
        //creo la supercolumnfamily
        CfDef superCf = new CfDef("Keyspace2","ListStandard1");
        superCf.setColumn_type("Super");
        superCf.setSubcomparator_type("AsciiType");
        client.system_add_column_family(superCf);
    }
    
}
