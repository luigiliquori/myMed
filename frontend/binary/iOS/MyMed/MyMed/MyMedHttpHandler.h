//
//  MyMedHttpHandler.h
//  MyMed
//
//  Created by Nicolas Goles on 10/6/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import <Foundation/Foundation.h>

@protocol MyMedHttpHandlerDelegate

- (void) receivedHTTPData:(NSDictionary *) data;
- (void) connectionError:(NSError *) error;

@end

@class SBJsonParser;

@interface MyMedHttpHandler : NSObject
{
    id delegate_;
    NSData *receivedData;
    SBJsonParser *jsonParser;
}

@property (nonatomic, retain) id delegate;

- (void) submitLoginURL:(NSString *) loginURL;

@end
