g++ -g -o iceInt -O0 -lcurses  main.cpp IceWrapper.cpp IceConnection.cpp rsComm.o p2pConnApi.o uiApi.o `pkg-config --cflags --libs libpjproject`

