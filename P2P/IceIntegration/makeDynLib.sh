javac mymed/JavaIceWrapper.java
javah -jni mymed.JavaIceWrapper
./compLowLevelForDynLib.sh
./compIntDyn.sh
./dynLib.sh
