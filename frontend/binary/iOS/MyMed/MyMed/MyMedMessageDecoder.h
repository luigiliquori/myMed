//
//  MyMedMessageDecoder.h
//  MyMed
//
//  Created by Nicolas Goles on 10/14/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import <Foundation/Foundation.h>

// Static values for MyMed Handlers
static NSString * const M_HANDLER_AUTH_REQUEST = @"com.mymed.controller.core.requesthandler.AuthenticationRequestHandler";

// Static values for MyMed Methods
static NSString * const M_METHOD_READ = @"READ";
static NSString * const M_METHOD_WRITE = @"WRITE";
static NSString * const M_METHOD_CREATE = @"CREATE";
static NSString * const M_METHOD_DELETE = @"DELETE";

// Keys of a MyMed Message Dictionary
static NSString * const M_KEY_STATUS = @"status";
static NSString * const M_KEY_HANDLER = @"handler";
static NSString * const M_KEY_METHOD = @"method";
static NSString * const M_KEY_DESCRIPTION = @"description";
static NSString * const M_KEY_DATA = @"data";
static NSString * const M_KEY_URL = @"url";
static NSString * const M_KEY_ACCESS_TOKEN = @"accessToken";
static NSString * const M_KEY_SOCIAL_NETWORK = @"socialNetwork";
static NSString * const M_KEY_SOCIAL_MYMED = @"mymed";
static NSString * const M_KEY_SOCIAL_FACEBOOK = @"facebook";

// OK Value for a MyMed Message Status
static const unsigned M_STATUS_OK = 200;

@interface MyMedMessageDecoder : NSObject

+ (NSDictionary *) dictionaryFromData:(NSData *) data;
+ (NSDictionary *) dictionaryFromString:(NSString *) string;

@end
