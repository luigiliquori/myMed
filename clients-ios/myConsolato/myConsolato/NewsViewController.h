//
//  FirstViewController.h
//  myConsolato
//
//  Created by Emilio on 27/03/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface NewsViewController : UIViewController<UIWebViewDelegate>

@property (strong, nonatomic) IBOutlet UIWebView *webView;

@end
