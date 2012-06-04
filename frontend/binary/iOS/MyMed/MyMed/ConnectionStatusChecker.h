//
//  ConnectionStatusChecker.h
//  MyMed
//
//  Created by Nicolas Goles on 10/5/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import <Foundation/Foundation.h>

@class Reachability;

@interface ConnectionStatusChecker : NSObject

+ (BOOL) doesHaveConnectivity;

@end
