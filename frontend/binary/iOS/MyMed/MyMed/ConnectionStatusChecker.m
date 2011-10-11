//
//  ConnectionStatusChecker.m
//  MyMed
//
//  Created by Nicolas Goles on 10/5/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import "ConnectionStatusChecker.h"
#import "Reachability.h"

// Static Definitions
static NSString * const GOOGLE_URL = @"google.fr";

// Static Atributes
static Reachability *reachability = nil;

@implementation ConnectionStatusChecker

+ (BOOL) doesHaveConnectivity
{
    if (!reachability) {
        reachability = [Reachability reachabilityWithHostName:GOOGLE_URL];
    }
    

    NetworkStatus internetStatus = [reachability currentReachabilityStatus];
    
    if (internetStatus == kNotReachable) {
        return NO;
    }
    
    return YES;
}

@end
