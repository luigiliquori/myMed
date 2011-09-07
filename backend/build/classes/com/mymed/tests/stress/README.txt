* Before running the stress test for Cassandra and Mymed, read this document! *

= Why? =

The stress tests are thought to test the behavior of Cassandra and our libraries
under certain conditions. We want to test how Cassandra responds to lots of
concurrent writes and reads, and also deletes. We also want to test the performance
and the bottlenecks that might be present in our libraries.

= Configuration =

To run the stress tests, we need at least a 4 nodes Cassandra cluster with a replica
factor of 3. If the nodes are also on different data center, we want to test how
Cassandra and mymed perform over a normal network.
