//
//  RestClient.h
//  myEurope
//
//  Created by Emilio on 24/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface RestClient : NSObject
@property (nonatomic, retain) NSURL * url;

-(NSData *) Post :(NSString *)body;
-(NSData *) Get:(NSString *)body;

@end
