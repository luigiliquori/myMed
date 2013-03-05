#CARTELLA ICEINTEGRATION
#COMPILAZIONE e creazione libreria dinamica da usare#############################################################################################
# per produrre la libreria libmymed
# compilare ed installare la libreria pj-sip (vedere README pj-sip)

#Compilazione basso livello, che include:
# Interfaccia verso pjsip: 					p2pConnApi.c e .h
# Comunicazione con il Rendezvous server:	rsComm.c e .h
./compDyn_new.sh (crea .o)

#Creazione di JavaIceWrapper.h e .cpp
javac mymed/JavaIceWrapper.java 	#genera il .class a partire dal java (che dichiara le API e carica la libreria dinamica)
javah -jni mymed.JavaIceWrapper		#genera il .h che genera il type corretto da includere nel cpp (il cpp deve essere fatto a mano basandosi sul .h - non fa altro che chiamare i metodi cpp)

#Serve avere java e jni installato
sudo apt-get install openjdk-6-jdk

#Compilazione IceWrapper e PseudoTCP
g++ -fPIC -I/usr/lib/jvm/java-6-openjdk/include/ -g -static -c JavaIceWrapper.cpp IceWrapper.cpp IceConnection.cpp MessageDispatcher.cpp PseudoTcpRx.cpp PseudoTcpTx.cpp Connection.cpp Message.cpp MovingBuffer.cpp PseudoTcpUtil.cpp  EventLogger.cpp -lboost_serialization -lboost_thread `pkg-config --cflags --libs libpjproject`

#serve installare boost, boost thread e boost serialization
/usr/lib/jvm/java-6-openjdk/include/ # da rendere portabile, semplicemente punta a jni.h

#Creazione libreria dinamica
#1 modo: include solo le parti di pj che servono ( pjlib-util, pjnath, pj)
g++ -shared -Wl,-soname,libmymed.so.1 \
    -o libmymed.so.1.0.1  JavaIceWrapper.o IceWrapper.o IceConnection.o MessageDispatcher.o PseudoTcpRx.o PseudoTcpTx.o \
    Connection.o Message.o MovingBuffer.o PseudoTcpUtil.o  EventLogger.o p2pConnApi.o rsComm.o -static -lc -lboost_thread -lboost_serialization -lpjnath-i686-pc-linux-gnu -lpjlib-util-i686-pc-linux-gnu -lpj-i686-pc-linux-gnu

#2 modo: include tutta la libreria pj-sip
g++ -shared -Wl,-soname,libmymed.so.1 \
    -o libmymed.so.1.0.1  JavaIceWrapper.o IceWrapper.o IceConnection.o MessageDispatcher.o PseudoTcpRx.o PseudoTcpTx.o \
    Connection.o Message.o MovingBuffer.o PseudoTcpUtil.o  EventLogger.o p2pConnApi.o rsComm.o -static -lc -lboost_thread -lboost_serialization `pkg-config --cflags --libs libpjproject`

##############################################################################################################################################

### APPLICAZIONE CHAT #########################################################################################################################
#mettere in dist lib la libreria mymed creata
#si basa su swing
#in src\mymed (package separato) mettere il .class e .java JavaIceWrapper

#dentro \dist\lib
ln -s libmymed.so.1 libmymed.so		#per creare il link alla libreria giusta

#per lanciare l'applicazione:

java -Djava.library.path=/home/claudio/mymed/peter/Chat/dist/lib -jar Chat.jar claudio 7		#7 è il serviceID di mymed, claudio è l'user

############################################################################################################################################

### NOTE ##
#Wrapping:
IceWrapper.cpp 			#fa il wrapping tra c (rscomm e p2pConnApi) e cpp (PseudoTcp)
IceConnection.cpp		#implementazione di IConnection implementa l'interfaccia richiesta da PSeudoTcp
JavaIceWrapper.java	#fa il wrapping tra cpp e java

# l'API java include una funzione per fare polling sui messaggi ricevuti (non c'è ancora il collegamento callback)
# Lo stun/turn server è specificato in IceWrapper.cpp 
# std::string stunServer("mymed12.sophia.inria.fr:3478");
