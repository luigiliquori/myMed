//
//  RemoteHtmlViewController.m
//  myEurope
//
//  Created by Emilio on 02/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import "RemoteHtmlViewController.h"
#import "MyMedClient.h"

@interface RemoteHtmlViewController ()
-(void) loadUrl;
//@property (nonatomic, retain) NSMutableData *receivedData;
//@property (nonatomic, retain) NSURLConnection *theConnection;

@end

@implementation RemoteHtmlViewController

@synthesize webview=_webview, url=_url, fname=_fname, pagetitle=_pagetitle;
//@synthesize receivedData=_receivedData, theConnection=_theConnection;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view from its nib.
    if (self.url != nil) {
        NSString *s = [MyMedClient GetInstance].html_pleaseWait;
        [self.webview loadHTMLString:s baseURL:[NSURL fileURLWithPath:[[NSBundle mainBundle] bundlePath]]];
        [self performSelector:@selector(loadUrl) withObject:nil afterDelay:0.2];
        
    } else if (self.fname!=nil) {
        NSString *p = [[NSBundle mainBundle] pathForResource:self.fname ofType:nil inDirectory:nil];
        NSString *html = [NSString stringWithContentsOfFile:p encoding:NSUTF8StringEncoding error:nil];
        
        [self.webview loadHTMLString:html baseURL:[NSURL fileURLWithPath:[[NSBundle mainBundle] bundlePath]]];
        
    } else {
        [self.webview loadHTMLString:[MyMedClient GetInstance].html_empty baseURL:[NSURL fileURLWithPath:[[NSBundle mainBundle] bundlePath]]];
    }
    self.navigationItem.title = self.pagetitle;
}

-(void) loadUrl
{
    [UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
    NSURL *u = [NSURL URLWithString:self.url];
    NSURLRequest *req=[NSURLRequest requestWithURL:u cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:60];
    /*
    self.theConnection=[[[NSURLConnection alloc] initWithRequest:req delegate:self] autorelease];
    if (self.theConnection) {
        // Create the NSMutableData to hold the received data.
        // receivedData is an instance variable declared elsewhere.
        self.receivedData = [NSMutableData data];
    } else {
        UIAlertView *av=[[UIAlertView alloc] initWithTitle:NSLocalizedString(@"Error", nil)
                                                   message:NSLocalizedString(@"Internet connection not available", nil) delegate:nil
                                         cancelButtonTitle:NSLocalizedString(@"Ok", nil) otherButtonTitles: nil];
        [av show];
        [av release];
    }
    */
    [self.webview loadRequest:req];
    //NSLog(@"Loading %@", [u absoluteString]);
}

- (void)viewDidUnload
{
    self.webview.delegate = nil;
    self.webview = nil;
    self.url = nil;
    self.pagetitle = nil;
    self.fname = nil;
    /*
    self.receivedData = nil;
    if (self.theConnection!=nil) {
        [self.theConnection cancel];
    }
    self.theConnection = nil;
     */
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
    [super viewDidUnload];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - WebView delegate

- (void)webViewDidFinishLoad:(UIWebView *)webView
{
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
    //NSLog(@"Load done: %@", self.url);
}

- (void)webView:(UIWebView *)webView didFailLoadWithError:(NSError *)error
{
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
    //NSLog(@"*** Load failed: %@ (Is loading:%d)", self.url, webView.isLoading);
    //NSLog(@"***              %@", error.localizedDescription);
    NSString *s = [MyMedClient GetInstance].html_noConnection;
    [self.webview loadHTMLString:s baseURL:[NSURL fileURLWithPath:[[NSBundle mainBundle] bundlePath]]];
    
}


#pragma mark - Url connection delegate
/*
- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response
{
    // This method is called when the server has determined that it
    // has enough information to create the NSURLResponse.
    
    // It can be called multiple times, for example in the case of a
    // redirect, so each time we reset the data.
    
    // receivedData is an instance variable declared elsewhere.
    [self.receivedData setLength:0];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data
{
    // Append the new data to receivedData.
    // receivedData is an instance variable declared elsewhere.
    [self.receivedData appendData:data];
}

- (void)connection:(NSURLConnection *)connection
  didFailWithError:(NSError *)error
{
    // release the connection, and the data object
    self.theConnection = nil;
    // receivedData is declared as a method instance elsewhere
    self.receivedData = nil;
    
    // inform the user
    NSLog(@"Connection failed! Error - %@ %@",
          [error localizedDescription],
          [[error userInfo] objectForKey:NSURLErrorFailingURLStringErrorKey]);
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
}

-(NSString *) modifyRespose :(NSData *)data
{
    NSString *string = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
    NSMutableString *sout = [NSMutableString stringWithCapacity:data.length];
    unsigned length = [string length];
    unsigned paraStart = 0, paraEnd = 0, contentsEnd = 0;
    //NSMutableArray *array = [NSMutableArray array];
    NSRange currentRange;
    while (paraEnd < length)
    {
        [string getParagraphStart:&paraStart end:&paraEnd
                      contentsEnd:&contentsEnd forRange:NSMakeRange(paraEnd, 0)];
        currentRange = NSMakeRange(paraStart, contentsEnd - paraStart);
        //[array addObject:[string substringWithRange:currentRange]];
        NSString *s = [string substringWithRange:currentRange];
        if (s!=nil) {
            NSRange r = [s rangeOfString:@"<h1>myEurope</h1>"];
            if (r.length==0) {
                [sout appendString:s];
            }
        }
        
    }
    [string release];
    return sout;
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection
{
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
    // do something with the data
    // receivedData is declared as a method instance elsewhere
    NSLog(@"Succeeded! Received %d bytes of data",[self.receivedData length]);
    
    // release the connection, and the data object
    self.theConnection = nil;
    NSString *s = [self modifyRespose:self.receivedData];
    [self.webview loadHTMLString:s baseURL:[NSURL URLWithString:self.url]];
    self.receivedData = nil;
}
*/
@end
