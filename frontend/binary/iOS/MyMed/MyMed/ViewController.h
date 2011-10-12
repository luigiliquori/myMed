//
//  ViewController.h
//  MyMed
//
//  Created by Nicolas Goles on 9/16/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController

@property (nonatomic, retain) id delegate;
@property (nonatomic, retain) IBOutlet UIWebView *webView;

- (void) loadMyMed;

@end
