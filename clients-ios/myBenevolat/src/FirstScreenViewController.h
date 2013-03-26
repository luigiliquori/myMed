//
//  FirstScreenViewController.h
//  myEurope
//
//  Created by Emilio on 26/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MessageUI/MessageUI.h>
#import <MessageUI/MFMailComposeViewController.h>

@interface FirstScreenViewController : UIViewController<UIWebViewDelegate,UIActionSheetDelegate, MFMailComposeViewControllerDelegate>
@property (nonatomic, strong) IBOutlet UIWebView *webview;
@end
