//* User server program  *
//deve fare due cose: memorizzare i nuovi client

#include "unistd.h"
#include "errno.h"
#include "sys/types.h"
#include "sys/socket.h"
#include "netinet/in.h"
#include "netdb.h"

#include "stdlib.h"
#include "stdio.h"
#include "string.h"
#include "strings.h"
#include "sys/wait.h"
#include <sys/file.h>
#include <unistd.h>

#define MAX_USERS 50
#define MAX_CHAR 200

#define ADD_USER 101
#define QUERY 102
#define EXIT 103
#define CLEAR 104

#define MAX_USER_REACHED 301
#define NO_USERS 302
#define MISSING_ARGUMENT 303
#define EXISTING_USER 304
#define CLEAR_FAILED 305

//Function Prototypes
void myabort(char *);
int parserequest(char *);
void itoa(int, char*, int);
void strreverse(char*, char*);
int usercount();

//Struct
struct users{
    char name[MAX_CHAR];
    struct sockaddr_in User_Address;
};

//Some Global Variables
struct users Users;
char ip4[INET_ADDRSTRLEN];
char username[MAX_CHAR];
char *filename="Users.my";
int serverport = 3000;
int port=0;
int usr_no=0;
char * eptr = NULL;
int listen_socket, client_socket;
struct sockaddr_in Server_Address, Client_Address;
socklen_t csize;
pid_t processid;
int childcount = 0;
FILE *fd;

int main(int argc, char **argv){
char buf[MAX_CHAR*MAX_USERS];
int n=0;
int result=0;
int finish=1;
//Step 0: Process Command Line
 if (argc > 2){
     myabort("Usage: server");
 }
if (argc == 2){
     serverport =  (int) strtol(argv[1], &eptr, 10);
     if (*eptr != '\0') myabort("Invalid Port Number!");
 }
//open the file and check
fd=fopen(filename,"r");
if (fd==NULL){
    printf("Il file degli utenti \"%s\" non esiteva..",filename);
    sprintf(buf,"touch %s", filename);
    if (system(buf)==-1)
	printf("ERRORE nella creazione del file!\n");
    else
	printf("..creato ora!\n");
}
else fclose(fd);
//Step 1: Create a socket
  listen_socket = socket(PF_INET, SOCK_STREAM, 0);
  if (listen_socket == -1) myabort("socket()");

//inizialize the struct
  bzero(&Users, sizeof(Users));
//Step 2: Setup Address structure
 bzero(&Server_Address, sizeof(Server_Address));
  Server_Address.sin_family = AF_INET;
  Server_Address.sin_port = htons(serverport);
  Server_Address.sin_addr.s_addr = INADDR_ANY;

//Step 3: Bind the socket to the port
  result = bind(listen_socket, (struct sockaddr *) &Server_Address, sizeof(Server_Address));
  if (result == -1) myabort("bind()");

//Step 4:Listen to the socket
  result = listen(listen_socket, 1);
  if (result == -1) myabort("listen()");

printf("\nThe forkserver :%d\n",ntohs(Server_Address.sin_port));
fflush(stdout);
//Step 5: Setup an infinite loop to make connections
while(1){

//Accept a Connection
     csize = sizeof(Client_Address);
     client_socket = accept( listen_socket,(struct sockaddr *) &Client_Address,&csize);
     if (client_socket == -1) myabort("accept()");

     printf( "\nClient Accepted!\n" );

    //fork this process into a child and parent
      processid = fork();

       //Check the return value given by fork(), if negative then error,
      //if 0 then it is the child.
      if ( processid == -1){
	  myabort("fork()");
      }else if (processid == 0){
	  /*Child Process*/

	close(listen_socket);
	  //loop until client closes

	 while (finish){

	    //read string from client
	    bzero(&buf, sizeof(buf));

	    //n = read(client_socket,(char *) &tmp, 100);
	    n = recv(client_socket,(char *) &buf, MAX_CHAR, 0);

	    if (n == -1) myabort("recv()");
	    if (n == 0) break;
	    buf[n-1] = '\0';
	    printf( "From client: %s\n",buf);

	    if ((result=parserequest(buf))>0){
		printf("Request %d OK!\n", result);
		bzero(&buf, sizeof(buf));
		switch(result){
		    case ADD_USER:

			//rewind(fd);
			//lockf((int)fd,F_LOCK,0);
			fd=fopen(filename,"a+");
			fprintf(fd,"%s %s %d\n",Users.name,ip4, ntohs(Users.User_Address.sin_port));
			fclose(fd);
			//lockf((int)fd,F_ULOCK,0);
			inet_ntop(AF_INET, &(Users.User_Address.sin_addr), ip4, INET_ADDRSTRLEN);
			sprintf(buf,"%d %s %s %d\n",ADD_USER, Users.name,ip4, ntohs(Users.User_Address.sin_port));
			break;
		    case EXISTING_USER:
			//todo use binary file to update!
			sprintf(buf, "%d User name already exist!",EXISTING_USER);
			break;
		    case MAX_USER_REACHED:
			sprintf(buf,"%d Maximum user reached! not added\n", MAX_USER_REACHED);
			break;
		    case QUERY:
			usr_no=0;
			bzero(&buf, sizeof(buf));
			buf[0]='\0';
			fd=fopen(filename,"r");
			while (fscanf(fd,"%s %s %d\n",username,ip4,&port)!=EOF){
			    strncat(buf,username,strlen(username));
			    strncat(buf, " ", 1);
			    strncat(buf,ip4,strlen(ip4));
			    strncat(buf, " ", 1);
			    itoa(port, ip4, 10);
			    strncat(buf,ip4,strlen(ip4));
			    //strncat(buf,Users[i].name,strlen(Users[i].name));
			    //strncat(buf, ":", 1);
			    //inet_ntop(AF_INET, &(Users[i].User_Address.sin_addr), ip4, INET_ADDRSTRLEN);
			    //strncat(buf,ip4,INET_ADDRSTRLEN);
			    //strncat(buf, ":", 1);
			    //itoa(Users[i].User_Address.sin_port, ip4, 10);
			    //strncat(buf, ip4, INET_ADDRSTRLEN);
			    strncat(buf, ";", 1);
			    usr_no++;
			}
			buf[strlen(buf)]='\0';
			fclose(fd);
			if (usr_no==0)
			    sprintf(buf,"%d No users\n", NO_USERS);
			break;
		    case MISSING_ARGUMENT:
			sprintf(buf, "%d Missing user name!",MISSING_ARGUMENT);
			break;
		    case CLEAR:
			sprintf(buf,"rm %s",filename);
			if (system(buf)==-1)
			    sprintf(buf, "%d Clear failed!",CLEAR_FAILED);
			else{
			    sprintf(buf,"touch %s",filename);
			    if (system(buf)==-1)
				sprintf(buf, "%d Clear failed!",CLEAR_FAILED);
			    else
				sprintf(buf, "%d List cleared!",CLEAR);
			}
			break;
		    case EXIT:
			finish=0;
			break;
		}
		if (finish){
		    printf("Sending %s\n",buf);
		    n = send(client_socket, buf, strlen(buf),0 );
		    if ( n == -1) myabort("send() answer to client");
		}
	    }
	    else{
		printf("Error BAD Request!\n");
		sprintf(buf, "Error BAD Request!\n");
		n = send(client_socket, buf, strlen(buf),0 );
		if ( n == -1) myabort("send() answer to client");
		continue;
	    }

	}//end inner while
	printf("Closing Client socket %d!\n", client_socket);
	close(client_socket);

	//Child exits
	exit(0);
     }

      //Parent Process
    printf("\nChild process spawned with id number:  %d",processid );
    //increment the number of children processes
    childcount++;
    while(childcount){
	processid = waitpid( (pid_t) - 1, NULL, WNOHANG );
	if (processid < 0) myabort("waitpid()");
	else if (processid == 0) break;
	 else childcount--;
    }

}
close(listen_socket);

exit(0);

}

int parserequest(char *req){
    char *split; //for parsing the request
    int esiste=0;

    split = strtok (req," ");
    printf("Command %s ", split);

    if (strcmp(split, "a")==0){
      usr_no=usercount();
      if (usr_no>=MAX_USERS){
	  printf("Maximum users reached!\n");
	  return MAX_USER_REACHED;
      }
      else{
	  printf("%d Users: I will add another  user...\n",usr_no);
	  split = strtok (NULL, " ");
	  if(split != NULL)
	  {
	      fd=fopen(filename,"r");
	     // lockf((int)fd,F_ULOCK,0);
	      while(fscanf(fd,"%s %s %d\n", username,ip4,&port)!=EOF){ //fscanf(fd,"%s %s %d\n",username,ip4,&port)!=EOF
		  //fscanf(fd,"%s %s %d\n", username,ip4,&port);
		  printf("Comparing %s [split] with %s [user]\n",split, username);
		  if (strcmp(split,username)==0){
		      esiste=1;
		  }
	      }
	      fclose(fd);
	      if (esiste==0){
		  strncpy(Users.name,split,strlen(split));
		  Users.name[strlen(split)+1]='\0';
		  printf("- - Adding user %s\n",split);
		  Users.User_Address.sin_addr=Client_Address.sin_addr;
		  inet_ntop(AF_INET, &(Users.User_Address.sin_addr), ip4, INET_ADDRSTRLEN);
		  printf("- - User IP %s PORT %d \n",ip4, ntohs(Client_Address.sin_port));
		  Users.User_Address.sin_port=Client_Address.sin_port;
		  return ADD_USER;
	      }
	      else
		  return EXISTING_USER;

	  }
	  else
	  {
	      printf("Request Error: missing argument to add user..\n" );
	      return MISSING_ARGUMENT;
	  }
      }
    }
    if (strcmp(split, "q")==0){
      return QUERY;
    }
    if (strcmp(split, "c")==0){
      return CLEAR;
    }
    if (strcmp(split, "exit")==0){
      return EXIT;
    }

    printf("Request Error: Received an unrecognized command..\n" );
    return -1;

}
int usercount(){
  char line[MAX_CHAR];
  int countline=0;

  fd=fopen(filename,"r");
 // lockf((int)fd,F_ULOCK,0);
  while(!feof(fd)){
      fscanf(fd,"%s\n",line);
      countline++;
  }
  return countline;
}

void myabort(char * msg){
    printf("Error!:  %s" ,  msg);
    exit(1);
}
/**

 * Ansi C "itoa" based on Kernighan & Ritchie's "Ansi C":

 */

void strreverse(char* begin, char* end) {

	char aux;
	while(end>begin)
		aux=*end, *end--=*begin, *begin++=aux;

}

void itoa(int value, char* str, int base) {

	static char num[] = "0123456789abcdefghijklmnopqrstuvwxyz";
	char* wstr=str;
	int sign;

	// Validate base
	if (base<2 || base>35){ *wstr='\0'; return; }

	// Take care of sign
	if ((sign=value) < 0) value = -value;

	// Conversion. Number is reversed.
	do *wstr++ = num[value%base]; while(value/=base);
	if(sign<0) *wstr++='-';
	*wstr='\0';

	// Reverse string
	strreverse(str,wstr-1);
}
