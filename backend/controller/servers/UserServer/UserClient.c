/*
 * UserClient.c
 *
 *  Created on: Nov 19, 2010
 *      Author: claudio
 */

//* client */

#include "stdlib.h"
#include "stdio.h"
#include "string.h"
#include "unistd.h"
#include "sys/socket.h"
#include "sys/types.h"
#include "netinet/in.h"
#include "strings.h"
#include "arpa/inet.h"

#define BUFFER    1024
#define MAX_CHAR 200


int main(int argc, char **argv)
{
    struct sockaddr_in serv;
    int sock;
    char in[BUFFER];
    char out[BUFFER];
    int len;


    if((sock = socket(AF_INET, SOCK_STREAM, 0)) == -1)
    {
        perror("socket");
        exit(-1);
    }

    serv.sin_family = AF_INET;
    serv.sin_port = htons(atoi(argv[2]));
    serv.sin_addr.s_addr = inet_addr(argv[1]);
    bzero(&serv.sin_zero, 8);

    printf("\nThe TCPclient %d\n",ntohs(serv.sin_port));
        fflush(stdout);


    if((connect(sock, (struct sockaddr *)&serv, sizeof(struct sockaddr_in))) == -1)
    {
        perror("connect");
        exit(-1);
    }

    while(1)
    {
        printf("\nMenu: \n");
        printf("-----------------\n");
        printf("a [user]	--> \"adds a user\" \n");
        printf("q		--> \"return user list\" \n");
        printf("c		--> \"cancel all the list\" \n");
        printf("exit		--> \"exits\" \n");
        printf("-----------------\n");
        printf("Input: ");

        fgets(in, BUFFER, stdin);

        send(sock, in, strlen(in), 0);

        if (strcmp(in,"exit\n")==0)
            break;
        len = recv(sock, out, BUFFER, 0);
        out[len] = '\0';
        printf("Output: %s\n", out);
    }

    close(sock);
    return 0;


}
