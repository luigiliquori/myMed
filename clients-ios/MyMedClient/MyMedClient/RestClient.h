//
//  RestClient.h
//  myEurope
//
//  Created by Emilio on 24/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface RestClient : NSObject

-(NSDictionary *) PostToHandler:(NSString *)hndlr withParameters :(NSDictionary *)dic;
-(NSDictionary *) GetToHandler:(NSString *)hndlr withParameters :(NSDictionary *)dic;


@end
