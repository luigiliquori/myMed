//
//  JS2OBJC.m
//  MyMed
//
//  Created by Nicolas Goles on 10/14/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import "WebObjCBridge.h"

#pragma mark - Identifier for "objc URL"
static NSString * const KEY_OBJ_C_CALL = @"mobile_binary";

@implementation WebObjCBridge

+ (BOOL) webView:(UIWebView *) webView2 shouldStartLoadWithRequest:(NSURLRequest *) request navigationType:(UIWebViewNavigationType) navigationType
{
	NSString *requestString = [[request URL] absoluteString];
    NSRange range = [requestString rangeOfString:KEY_OBJ_C_CALL];

    if (range.length > 0) {
        NSString *objCProtocolLink = [requestString substringFromIndex:range.location];
        NSArray *components = [objCProtocolLink componentsSeparatedByString:@":"];
        
        if ([components count] > 1) {
        	if([[components objectAtIndex:1] isEqualToString:FN_CHOOSE_PICTURE]) {
                [[NSNotificationCenter defaultCenter] postNotificationName:FN_CHOOSE_PICTURE object:nil];
        	} else if([[components objectAtIndex:1] isEqualToString:FN_LOGOUT]) {
                [[NSNotificationCenter defaultCenter] postNotificationName:FN_LOGOUT object:nil];
            }
        }

        return NO;
    }
    
    return YES;
}

@end
