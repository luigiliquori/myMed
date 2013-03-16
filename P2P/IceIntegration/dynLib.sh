g++ -shared -Wl,-soname,libmymed.so.1 \
    -o libmymed.so.1.0.1  JavaIceWrapper.o IceWrapper.o IceConnection.o MessageDispatcher.o PseudoTcpRx.o PseudoTcpTx.o \
    Connection.o Message.o MovingBuffer.o PseudoTcpUtil.o  EventLogger.o p2pConnApi.o rsComm.o -static -lc -lboost_thread -lboost_serialization -lpjnath-i686-pc-linux-gnu -lpjlib-util-i686-pc-linux-gnu -lpj-i686-pc-linux-gnu
