//
//  FirstScreenViewController.m
//  myEurope
//
//  Created by Emilio on 26/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import "FirstScreenViewController.h"
#import <QuartzCore/QuartzCore.h>

@interface FirstScreenViewController ()

@end

@implementation FirstScreenViewController
@synthesize webview=_webview;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    NSString *p = [[NSBundle mainBundle] pathForResource:@"first" ofType:@"html" inDirectory:nil];
    NSString *html = [NSString stringWithContentsOfFile:p encoding:NSUTF8StringEncoding error:nil];
    self.webview.delegate = self;
    self.webview.alpha = 0;
    [self.webview loadHTMLString:html baseURL:[NSURL fileURLWithPath:[[NSBundle mainBundle] bundlePath]]];
    [[self.webview layer] setCornerRadius:10];
    [self.webview setClipsToBounds:YES];

}
- (void)viewDidUnload
{
    self.webview = nil;
    [super viewDidUnload];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)webViewDidFinishLoad:(UIWebView *)webView
{
    [UIView animateWithDuration:0.7 delay:0.2
                        options:UIViewAnimationOptionCurveEaseInOut
                     animations:^{
        self.webview.alpha = 1.0f;
    }
                     completion:nil];
}

@end
