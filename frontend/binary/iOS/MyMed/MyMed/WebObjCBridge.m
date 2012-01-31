//
//  JS2OBJC.m
//  MyMed
//
//  Created by Nicolas Goles on 10/14/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import "WebObjCBridge.h"
#import "MyMedMessageDecoder.h"
#import "NSString+HTML.h" // << NSString Category for HTML Escaping

enum kFunctionPublishParameter {
    kFunctionPublishParameter_Application = 2,
    kFunctionPublishParameter_UserProfile = 3,
    kFunctionPublishParameter_Predicate = 4,
    kFunctionPublishParameter_Data = 5,
    kFunctionPublishParameter_AccessToken = 6,
};

static NSString * const KEY_PUBLISH_APPLICATION = @"application";
static NSString * const KEY_PUBLISH_USER_PROFILE = @"user";
static NSString * const KEY_PUBLISH_PREDICATE = @"predicate";
static NSString * const KEY_PUBLISH_DATA = @"data";
static NSString * const KEY_PUBLISH_CODE = @"code";
static NSString * const KEY_PUBLISH_ACCESS_TOKEN = @"accessToken";

// Key to identify that the "redirect" will be handled in Objective-C
// In javascript is used document.location = "mobile_binary" + ":" + "function_name" + ":" + "param1" + ":" + "param2"
static NSString * const KEY_OBJ_C_CALL = @"mobile_binary";

#pragma mark - 
#pragma mark - Private Interface
@interface WebObjCBridge (private)
+ (NSDictionary *) publishArgumentsDictionaryFromArray:(NSArray *) components;
@end

@implementation WebObjCBridge (private)

+ (NSDictionary *) publishArgumentsDictionaryFromArray:(NSArray *) components
{   
//    NSLog(@"%@", components);
    
    NSArray *objects = [NSArray arrayWithObjects:[components objectAtIndex:kFunctionPublishParameter_Application],
                                                 [components objectAtIndex:kFunctionPublishParameter_UserProfile],
                                                 [components objectAtIndex:kFunctionPublishParameter_Predicate],
                                                 [components objectAtIndex:kFunctionPublishParameter_Data],
                                                 [components objectAtIndex:kFunctionPublishParameter_AccessToken],
                                                 [NSNumber numberWithInt:0],
                                                 nil];
    
    NSArray *keys = [NSArray arrayWithObjects:KEY_PUBLISH_APPLICATION,
                                              KEY_PUBLISH_USER_PROFILE,
                                              KEY_PUBLISH_PREDICATE,
                                              KEY_PUBLISH_DATA,
                                              KEY_PUBLISH_ACCESS_TOKEN,
                                              KEY_PUBLISH_CODE,
                                              nil];
    
    return [NSDictionary dictionaryWithObjects:objects forKeys:keys];
}

@end

#pragma mark -
#pragma mark - Public Interface Implementation
@implementation WebObjCBridge

+ (BOOL) webView:(UIWebView *) webView2 shouldStartLoadWithRequest:(NSURLRequest *) request navigationType:(UIWebViewNavigationType) navigationType
{
    NSString *requestString = [[[request URL] absoluteString] stringByReplacingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
    NSRange range = [requestString rangeOfString:KEY_OBJ_C_CALL];
    
    if (range.length > 0) {
        NSString *objCProtocolLink = [requestString substringFromIndex:range.location];
        NSLog(@"%@",objCProtocolLink);
        NSArray *components = [objCProtocolLink componentsSeparatedByString:@"::"];
        
        if ([components count] > 1) {
        	if([[components objectAtIndex:1] isEqualToString:FN_CHOOSE_PICTURE]) {
                [[NSNotificationCenter defaultCenter] postNotificationName:FN_CHOOSE_PICTURE 
                                                                    object:nil];
        	} else if([[components objectAtIndex:1] isEqualToString:FN_LOGOUT]) {
                [[NSNotificationCenter defaultCenter] postNotificationName:FN_LOGOUT 
                                                                    object:nil];
            } else if([[components objectAtIndex:1] isEqualToString:FN_PUBLISH]) {
                [[NSNotificationCenter defaultCenter] postNotificationName:FN_PUBLISH 
                                                                    object:nil 
                                                                  userInfo:[self publishArgumentsDictionaryFromArray:components]];
            }
        }

        return NO;
    }

    return YES;
}

@end
