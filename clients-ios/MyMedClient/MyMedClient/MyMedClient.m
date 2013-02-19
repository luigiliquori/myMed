//
//  MyMedClient.m
//  MyMedClient
//
//  Created by Emilio on 13/02/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import "MyMedClient.h"

@interface MyMedClient
@property (nonatomic, retain) NSString *accessToken;


@end

@implementation MyMedClient

@synthesize accessToken=_accessToken;


- (id)init {
    self = [super init];
    if (self) {
        unsigned int r = arc4random() % 9999999;
        self.accessToken=[NSString stringWithFormat:@"%d", r ];
    }
    return self;
}

#pragma mark - Blog

-(BOOL) getBlogPosts
{
    NSDictionary *dic = [NSDictionary dictionaryWithObjectsAndKeys:
                         @"1", @"code",
                         APPNAME, @"application",
                         self.accessToken, @"accessToken",
                         @"pred1Partners_search", @"predicate",
                         nil];
    NSDictionary *dat = [self GetToHandler:@"FindRequestHandler" withParameters:dic];
    NSLog(@"Result: %@", dat);
    return TRUE;
}




#pragma mark - Login

-(BOOL) Login:(NSString *)username password:(NSString *)pwd
{
    NSDictionary *dic = [NSDictionary dictionaryWithObjectsAndKeys:
                         @"1", @"code",
                         APPNAME, @"application",
                         username, @"login",
                         pwd, @"password",
                         //@"1234", @"accessToken",
                         nil];
    NSDictionary *dat = [self PostToHandler:@"AuthenticationRequestHandler" withParameters:dic ];
    NSLog(@"Result: %@", dat);
    return TRUE;
}


-(BOOL) CreateSession
{
    NSDictionary *dic = [NSDictionary dictionaryWithObjectsAndKeys:
                         MYMED_OP_CREATE, @"code",
                         APPNAME, @"application",
                         @"test", @"userID",
                         self.accessToken, @"accessToken",
                         nil];
    NSDictionary *dat = [self PostToHandler:@"SessionRequestHandler" withParameters:dic ];
    NSLog(@"Result: %@", dat);
    return TRUE;
}

@end
