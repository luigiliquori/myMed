//
//  HtmlViewController.m
//  myEurope
//
//  Created by Emilio on 20/12/12.
//  Copyright (c) 2012 myMed. All rights reserved.
//

#import "HtmlViewController.h"

#import "conf.h"

@interface HtmlViewController ()
{
    BOOL _isReady;
}
@end

@implementation HtmlViewController
@synthesize webview=_webview, bgImageView=_bgImageView;
@synthesize javascript=_javascript;
@synthesize isReady=_isReady;
@synthesize enableLoadRequest=_enableLoadRequest;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

-(void) load
{
    NSURL *u = [NSURL URLWithString:WEBAPP_URL];
    NSURLRequest *r = [[[NSURLRequest alloc] initWithURL:u cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60] autorelease];
    [self.webview loadRequest:r];
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    self.webview.alpha = 0.0f;
    _isReady = NO;
    // Do any additional setup after loading the view from its nib.
    //MyMedClient *client = [MyMedClient GetInstance];
    //[self.webview loadHTMLString:client.htmlData baseURL:client.appurl];
    //client.delegate = self;
    if ([[UIDevice currentDevice] userInterfaceIdiom] == UIUserInterfaceIdiomPad) {
        self.bgImageView.image = [UIImage imageNamed:@"background2"];
    }
    NSString *s =[NSString  stringWithFormat:@"<html><style type='text/css'>body {background-image:url('background.jpg');background-size:cover;} </style><body><h1 style=\"text-align:center;color=GhostWhite\">%@</h1><p>%@</p></body></html>",
            NSLocalizedString(@"myRiviera", nil),
            NSLocalizedString(@"Please wait...", nil)];
    [self.webview loadHTMLString:s baseURL:[NSURL fileURLWithPath:[[NSBundle mainBundle] bundlePath]]];
    
    [self performSelector:@selector(load) withObject:nil afterDelay:0.2];
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
}

- (void)viewWillDisappear:(BOOL)animated
{
    //MyMedClient *client = [MyMedClient GetInstance];
    //client.delegate = nil;
    //[self.webview loadHTMLString:client.html_empty baseURL:[NSURL fileURLWithPath:[[NSBundle mainBundle] bundlePath]]];
    [super viewWillDisappear:animated];
}


- (void)viewDidUnload
{
    //MyMedClient *client = [MyMedClient GetInstance];
    //client.delegate = nil;
    self.webview.delegate = nil;
    self.webview = nil;
    self.javascript = nil;
    self.bgImageView = nil;
    [super viewDidUnload];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}


#pragma mark - MyMedClient delegate

-(void) dataReady :(NSString *) data
{
    //NSLog(@"[HtmlViewController] Data ready");
    self.enableLoadRequest = YES;
    [self.webview loadHTMLString:data baseURL:[NSURL URLWithString:WEBAPP_URL]];
        //if (self.javascript!=nil) {
            //[self.webview stringByEvaluatingJavaScriptFromString:self.javascript];
        //}
    

}

- (void)webViewDidFinishLoad:(UIWebView *)webView
{
    _isReady = YES;
    if (self.javascript!=nil) {
        [self.webview stringByEvaluatingJavaScriptFromString:self.javascript];
    }
    if (self.webview.alpha!=1.0f) {
        [UIView animateWithDuration:0.4 animations:^{
            self.webview.alpha = 1.0f;
        }];
    }
}

- (void)webView:(UIWebView *)webView didFailLoadWithError:(NSError *)error
{
    NSString *s = [NSString  stringWithFormat:@"<html><style type='text/css'>body {background-image:url('background.jpg');background-size:cover;} </style><body><h1 style=\"text-align:center;color=GhostWhite\">%@</h1><p style=\"text-align:center\">%@<br/><br/><a href=\"%@\">%@</a></p></body></html>",
            NSLocalizedString(@"myRiviera", nil),
            NSLocalizedString(@"Server down, or no connection available.", nil),
            WEBAPP_URL,
                   NSLocalizedString(@"Reload", nil)];
    [self.webview loadHTMLString:s baseURL:[NSURL fileURLWithPath:[[NSBundle mainBundle] bundlePath]]];    
}

/*
- (BOOL)webView:(UIWebView*)webView shouldStartLoadWithRequest:(NSURLRequest*)request navigationType:(UIWebViewNavigationType)navigationType {
    //Intercept link
    
    NSURL *url = request.URL;
    
	NSString *urlString = url.absoluteString;
    const NSUInteger index = [urlString rangeOfString:@"?"].location;
    if (index != NSNotFound) {
       NSString *params = [urlString substringFromIndex:index];
      self.enableLoadRequest = NO;
    }
    //MyMedClient *client = [MyMedClient GetInstance];
    //client.appurl
    //[client loadUrl:<#(NSString *)#>]
    return self.enableLoadRequest;
}
 */
@end
