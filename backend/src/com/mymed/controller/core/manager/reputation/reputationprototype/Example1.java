/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputationprototype;

import java.util.ArrayList;
import java.util.List;

import org.apache.cassandra.thrift.Cassandra;
import org.apache.cassandra.thrift.CfDef;
import org.apache.cassandra.thrift.KsDef;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;

public class Example1 {
    private static final String HOST = "localhost";
    private static final int PORT = 4201;
    /**
    * Creates a new keyspace and CF.
    */
    public static void main(String... args) throws Exception {
        String keyspaceName = "Keyspace2";
        System.out.println("Creating new keyspace: "+ keyspaceName);
        //Create Keyspace
        KsDef k = new KsDef();
        k.setName(keyspaceName);
        k.setReplication_factor(1);
        k.setStrategy_class("org.apache.cassandra.locator.SimpleStrategy");
        
        //creo la lista delle columnfamily del keyspace per ora vuota
        List<CfDef> cfDefs = new ArrayList<CfDef>();
        k.setCf_defs(cfDefs);
        
        //Connect to Server
        TTransport tr = new TSocket(HOST, PORT);
        TFramedTransport tf = new TFramedTransport(tr);
        TProtocol proto = new TBinaryProtocol(tf);
        Cassandra.Client client = new Cassandra.Client(proto);
        tr.open();
        //Add the new keyspace
        client.system_add_keyspace(k);
        System.out.println("Added keyspace: "+ keyspaceName);

        //setto il keyspace per questa sessione
        client.set_keyspace(keyspaceName);
        
        //aggiungo una column family chiamata Standard1
        CfDef cfDef = new CfDef(keyspaceName, "Standard1");
        client.system_add_column_family(cfDef);
    }
}