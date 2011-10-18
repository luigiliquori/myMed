//
//  JS2OBJC.h
//  MyMed
//
//  Created by Nicolas Goles on 10/14/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import <Foundation/Foundation.h>

#pragma mark - Static Obj-C Function Names
static NSString * const FN_CHOOSE_PICTURE = @"choose_picture";

@interface WebObjCBridge : NSObject <UIWebViewDelegate, UINavigationControllerDelegate>

+ (BOOL) webView:(UIWebView *) webView2 shouldStartLoadWithRequest:(NSURLRequest *) request navigationType:(UIWebViewNavigationType) navigationType;

@end
