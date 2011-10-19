//
//  ViewController.m
//  MyMed
//
//  Created by Nicolas Goles on 9/16/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import "ViewController.h"
#import "WebObjCBridge.h"
#import "ConnectionStatusChecker.h"
#import "UIDeviceHardware.h"

#pragma mark - Static Definitions
static NSString * const MY_MED_INDEX_URL = @"http://mymed2.sophia.inria.fr/mobile/index.php";

#pragma mark -
#pragma mark - Private Protocol for Notification Handling
@interface ViewController (NotificationObserver)
- (void) startObservingNotifications;
- (void) displayCameraView:(id) sender;
@end

@implementation ViewController (NotificationObserver)
- (void) startObservingNotifications
{
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(displayCameraView:) 
                                                 name:FN_CHOOSE_PICTURE
                                               object:nil];
}

- (void) displayCameraView:(id) sender
{
    UIImagePickerController *cameraUI = [[UIImagePickerController alloc] init];
    cameraUI.sourceType = UIImagePickerControllerSourceTypeCamera;
    cameraUI.mediaTypes = [UIImagePickerController availableMediaTypesForSourceType:UIImagePickerControllerSourceTypeCamera];
    cameraUI.allowsEditing = NO;    
    cameraUI.delegate = self;
    [self presentModalViewController:cameraUI animated:YES];
}

@end

#pragma mark -
#pragma mark - Public Interface Implementation
@implementation ViewController

@synthesize webView;

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Release any cached data, images, etc that aren't in use.
}

#pragma mark - View lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
    webView.delegate = self;
    [self startObservingNotifications];
}

- (void)viewDidUnload
{
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}

- (void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
}

- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];    
}

- (void)viewWillDisappear:(BOOL)animated
{
	[super viewWillDisappear:animated];
}

- (void)viewDidDisappear:(BOOL)animated
{
	[super viewDidDisappear:animated];
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    // Return YES for supported orientations
    return (interfaceOrientation != UIInterfaceOrientationPortraitUpsideDown);
}

#pragma mark -
#pragma mark - MyMed
- (void) loadMyMedURL:(NSURL *) url
{
    if ([ConnectionStatusChecker doesHaveConnectivity]) {
        [webView loadRequest:[NSURLRequest requestWithURL:url]];
        
        NSURLRequest *req = [NSURLRequest requestWithURL:url];
        NSURLConnection *urlConnection = [[NSURLConnection alloc] initWithRequest:req delegate:self];
        webView.scalesPageToFit = YES;
        [webView loadRequest:req];
        
        
    } else {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"No internet connection" 
                                                        message:@"You need an active internet connection to use this application" 
                                                       delegate:self 
                                              cancelButtonTitle:@"OK"
                                              otherButtonTitles:nil];
        [alert show];
        NSString *errorPage;
        
        if ([[UIDeviceHardware platformString] hasPrefix:@"iPad"]) {
            errorPage = @"error_ipad";
        } else {
            errorPage = @"error_iphone";
        }
        
        [webView loadRequest:[NSURLRequest requestWithURL:[NSURL fileURLWithPath:[[NSBundle mainBundle] pathForResource:errorPage ofType:@"html"] isDirectory:NO]]];
    }
}

#pragma mark - Javascript <--> Objective-C Bridge
- (BOOL) webView:(UIWebView *) inWebView shouldStartLoadWithRequest:(NSURLRequest *) inRequest navigationType:(UIWebViewNavigationType) inNavigationType
{
    return [WebObjCBridge webView:inWebView shouldStartLoadWithRequest:inRequest navigationType:inNavigationType];
}

- (void) dealloc
{
    [[NSNotificationCenter defaultCenter] removeObserver:self];
}

#pragma mark -
#pragma mark - NSURLConnectionDelegate
- (BOOL) connection:(NSURLConnection *) connection canAuthenticateAgainstProtectionSpace:(NSURLProtectionSpace *)protectionSpace 
{
    return [protectionSpace.authenticationMethod isEqualToString:NSURLAuthenticationMethodServerTrust];
}

- (void) connection:(NSURLConnection *) connection didReceiveAuthenticationChallenge:(NSURLAuthenticationChallenge *) challenge 
{
    [challenge.sender useCredential:[NSURLCredential credentialForTrust:challenge.protectionSpace.serverTrust] forAuthenticationChallenge:challenge];
    [challenge.sender continueWithoutCredentialForAuthenticationChallenge:challenge];
}

- (void)connection:(NSURLConnection *) connection didReceiveResponse:(NSURLResponse *) response
{
    [connection cancel];
}

#pragma mark -
#pragma mark - UIImagePickerDelegate
- (void) imagePickerController:(UIImagePickerController *) picker didFinishPickingMediaWithInfo:(NSDictionary *) info
{
}

- (void) imagePickerControllerDidCancel:(UIImagePickerController *) picker
{
    [self dismissModalViewControllerAnimated:YES];
}

@end
