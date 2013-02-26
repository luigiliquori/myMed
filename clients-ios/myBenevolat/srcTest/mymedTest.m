//
//  myEuropeTest.m
//  myEuropeTest
//
//  Created by Emilio on 24/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import "myEuropeTest.h"
#import "RestClient.h"
#import "conf.h"

@implementation myEuropeTest

- (void)setUp
{
    [super setUp];
    
    // Set-up code here.
}

- (void)tearDown
{
    // Tear-down code here.
    
    [super tearDown];
}

- (void)testPost
{
    RestClient *cln = [[RestClient alloc] init];
    cln.url = [NSURL URLWithString:WEBAPP_URL];
    [cln Post:@""];
    [cln release];
    //STFail(@"Unit tests are not implemented yet in myEuropeTest");
}

@end
