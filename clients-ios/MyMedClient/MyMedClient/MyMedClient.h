//
//  MyMedClient.h
//  MyMedClient
//
//  Created by Emilio on 13/02/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import "RestClient.h"

#define MYMED_OP_CREATE @"0"
#define MYMED_OP_READ @"1"
#define MYMED_OP_UPDATE @"2"
#define MYMED_OP_DELETE @"3"

@interface MyMedClient : RestClient

-(BOOL) getBlogPosts;
-(BOOL) Login:(NSString *)username password:(NSString *)pwd;
-(BOOL) CreateSession;

@end
