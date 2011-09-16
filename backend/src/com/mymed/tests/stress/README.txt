* Before running the stress test for Cassandra and Mymed, read this document! *

= Why? =

The stress tests are thought to test the behavior of Cassandra and our libraries
under certain conditions. We want to test how Cassandra responds to lots of
concurrent writes and reads, and also deletes. We also want to test the performance
and the bottlenecks that might be present in our libraries.

= Configuration =

To run the stress test it is possible to run them in single Cassandra instance, but it
would be better to have a dedicated cluster of at least 4 nodes, with a replica
factor of 3. If the nodes are also on different datacenters, we want to test how
Cassandra and mymed perform over a normal network.

The configuration files are all stored in the 'conf/' directory, even the 'cassandra.yaml'
file to set up the Cassandra instance on each machine, and the 'cassandra-topology.properties'
file to configure the datacenters.

= Run the tests =

To run the test it is possible to run each executing the classes:
- UserStressTest
- SessionStressTest
- AuthenticationStressTest
- UserSessionStressTest
- AuthUserSessionStressTest

= Problems =

Looks like on an Ubuntu machine, with the creation of the connection as it stands today
in our code, we reach a limit on the number of sockets we can create quite easily.
Looks like we cannot create more than 28228 sockets. The problem in this scenario is that
the connection are fast and short, and there is not enough time for the system to release
the sockets that are in the TIME_WAIT status.
To circumvent this problem, it is possible to set a kernel value to 1: TCP_TW_REUSE.
With this parameter set to 1, it is possible to reuse the sockets in a faster way.
Other interesting parameters are: TCP_FIN_TIMEOUT, TCP_KEEPALIVE_INTERNAL.

To set the TCP_TW_REUSE:
echo 1 > /proc/sys/net/ipv4/tcp_tw_reuse