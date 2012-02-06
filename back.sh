ant clean -Ddir=lib
ant backend -Daddress=mymed20.sophia.inria.fr
cp /local/mymed/build/lib/mymed_backend.war /local/glassfish3/glassfish/domains/domain1/autodeploy/
