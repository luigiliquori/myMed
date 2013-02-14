//
//  RemoteHtmlViewController.h
//  myEurope
//
//  Created by Emilio on 02/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface HtmlViewController : UIViewController<UIWebViewDelegate, NSURLConnectionDelegate>
@property (nonatomic,retain) IBOutlet UIWebView *webview;
@property (nonatomic,retain) NSString *url;
@property (nonatomic,retain) NSString *fname;
@property (nonatomic,retain) NSString *htmlString;
@end
