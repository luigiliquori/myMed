g++ -g -o iceInt -O0 -lcurses -L. -lmmice main.cpp IceConnection.cpp `pkg-config --cflags --libs libpjproject` 

