//
//  myMedClient.h
//  myEurope
//
//  Created by Emilio on 02/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "conf.h"

@protocol MyMedClientDelegate <NSObject>
-(void) dataReady :(NSString *)data;
@end




@interface MyMedClient : NSObject<NSURLConnectionDelegate>

+(MyMedClient *) GetInstance;

@property (readonly) NSURL *appurl;
@property (readonly, getter = getHtml_empty) NSString *html_empty;
@property (readonly, getter = getHtml_noConnection) NSString *html_noConnection;
@property (readonly, getter = getHtml_pleaseWait) NSString *html_pleaseWait;

@property (nonatomic, retain) NSString *htmlData;

@property (assign) NSObject<MyMedClientDelegate> *delegate;

-(void) Login :(NSString *)login pwd:(NSString *)password;
-(void) loadUrl: (NSString *) params;
-(void) loadUrl;
@end
