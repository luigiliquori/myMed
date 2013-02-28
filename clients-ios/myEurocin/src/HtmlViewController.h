//
//  HtmlViewController.h
//  myEurope
//
//  Created by Emilio on 20/12/12.
//  Copyright (c) 2012 myMed. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface HtmlViewController : UIViewController<UIWebViewDelegate>
@property (nonatomic,retain) IBOutlet UIWebView *webview;
@property (nonatomic,retain) IBOutlet UIImageView *bgImageView;

@property (nonatomic,retain) NSString *javascript;
@property (nonatomic, readonly) BOOL isReady;

@property (nonatomic) BOOL enableLoadRequest;
@end
