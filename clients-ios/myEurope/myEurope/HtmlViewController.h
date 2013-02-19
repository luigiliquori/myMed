//
//  HtmlViewController.h
//  myEurope
//
//  Created by Emilio on 20/12/12.
//  Copyright (c) 2012 myMed. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "MyMedClient.h"

@interface HtmlViewController : UIViewController<UIWebViewDelegate, MyMedClientDelegate>
@property (nonatomic,retain) IBOutlet UIWebView *webview;
@property (nonatomic,retain) IBOutlet UIImageView *bgImageView;

@property (nonatomic,retain) NSString *pagetitle;
@property (nonatomic,retain) NSString *javascript;
@property (nonatomic, readonly) BOOL isReady;

@property (nonatomic) BOOL enableLoadRequest;
@end
