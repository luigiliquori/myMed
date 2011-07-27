current=$(pwd)
myMedPath='/local/mymed'

# MUserBean
cd $myMedPath/backend/tools/jeldoclet_1_0/build/classes
javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . -d $myMedPath/backend/doc/xml/ $myMedPath/backend/src/com/mymed/model/data/user/MUserBean.java
mv $myMedPath/backend/doc/xml/jel.xml $myMedPath/backend/doc/xml/MUserBean.xml

# MAuthenticationBean
cd $myMedPath/backend/tools/jeldoclet_1_0/build/classes
javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . -d $myMedPath/backend/doc/xml/ $myMedPath/backend/src/com/mymed/model/data/session/MAuthenticationBean.java
mv $myMedPath/backend/doc/xml/jel.xml $myMedPath/backend/doc/xml/MAuthenticationBean.xml

# MSessionBean
cd $myMedPath/backend/tools/jeldoclet_1_0/build/classes
javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . -d $myMedPath/backend/doc/xml/ $myMedPath/backend/src/com/mymed/model/data/session/MSessionBean.java
mv $myMedPath/backend/doc/xml/jel.xml $myMedPath/backend/doc/xml/MSessionBean.xml

# MReputationBean
cd $myMedPath/backend/tools/jeldoclet_1_0/build/classes
javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . -d $myMedPath/backend/doc/xml/ $myMedPath/backend/src/com/mymed/model/data/reputation/MReputationBean.java
mv $myMedPath/backend/doc/xml/jel.xml $myMedPath/backend/doc/xml/MReputationBean.xml

# MInteractionBean
cd $myMedPath/backend/tools/jeldoclet_1_0/build/classes
javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . -d $myMedPath/backend/doc/xml/ $myMedPath/backend/src/com/mymed/model/data/reputation/MInteractionBean.java
mv $myMedPath/backend/doc/xml/jel.xml $myMedPath/backend/doc/xml/MInteractionBean.xml

# MApplicationBean
cd $myMedPath/backend/tools/jeldoclet_1_0/build/classes
javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . -d $myMedPath/backend/doc/xml/ $myMedPath/backend/src/com/mymed/model/data/application/MApplicationBean.java
mv $myMedPath/backend/doc/xml/jel.xml $myMedPath/backend/doc/xml/MApplicationBean.xml

# MApplicationModelBean
cd $myMedPath/backend/tools/jeldoclet_1_0/build/classes
javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . -d $myMedPath/backend/doc/xml/ $myMedPath/backend/src/com/mymed/model/data/application/MApplicationModelBean.java
mv $myMedPath/backend/doc/xml/jel.xml $myMedPath/backend/doc/xml/MApplicationModelBean.xml

# MApplicationViewBean
cd $myMedPath/backend/tools/jeldoclet_1_0/build/classes
javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . -d $myMedPath/backend/doc/xml/ $myMedPath/backend/src/com/mymed/model/data/application/MApplicationViewBean.java
mv $myMedPath/backend/doc/xml/jel.xml $myMedPath/backend/doc/xml/MApplicationViewBean.xml

# MOntologyBean
cd $myMedPath/backend/tools/jeldoclet_1_0/build/classes
javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . -d $myMedPath/backend/doc/xml/ $myMedPath/backend/src/com/mymed/model/data/application/MOntologyBean.java
mv $myMedPath/backend/doc/xml/jel.xml $myMedPath/backend/doc/xml/MOntologyBean.xml

# MOntologyTypeBean
cd $myMedPath/backend/tools/jeldoclet_1_0/build/classes
javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . -d $myMedPath/backend/doc/xml/ $myMedPath/backend/src/com/mymed/model/data/application/MOntologyTypeBean.java
mv $myMedPath/backend/doc/xml/jel.xml $myMedPath/backend/doc/xml/MOntologyTypeBean.xml

# MPredicateBean
cd $myMedPath/backend/tools/jeldoclet_1_0/build/classes
javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . -d $myMedPath/backend/doc/xml/ $myMedPath/backend/src/com/mymed/model/data/application/MPredicateBean.java
mv $myMedPath/backend/doc/xml/jel.xml $myMedPath/backend/doc/xml/MPredicateBean.xml

cd $current 
