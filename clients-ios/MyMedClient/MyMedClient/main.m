//
//  main.m
//  MyMedClient
//
//  Created by Emilio on 03/02/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "MyMedClient.h"

int main(int argc, const char * argv[])
{

    @autoreleasepool {
        NSLog(@"Hello, World!");
        MyMedClient *rc = [[MyMedClient alloc] init];
        NSLog(@"------------------------\nCreateSession");
        [rc CreateSession];
        //[rc Login:@"c301856@rmqkr.net" password:@"cime3di4rapa"];
        NSLog(@"------------------------\n\ngetBlogPosts");
        [rc getBlogPosts];
        [rc release];
        
    }
    return 0;
}

