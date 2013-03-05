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
#import "ImagePickerDelegate.h"
#import "POSTRequestBuilder.h"
#import "MyMedMessageDecoder.h"

enum CameraAlertButtonIndex {
    CameraAlertButtonIndexCamera = 1,
    CameraAlertButtonIndexExistingPicture = 2,
};

#pragma mark - Static Definitions
static NSString * const MY_MED_INDEX_URL = @"http://www.mymed.fr/index.php";
static NSString * const MY_MED_PUBLISH_URL = @"http://www.mymed.fr:8080/backend/PublishRequestHandler";

#pragma mark -
#pragma mark - Private Methods for Notification Handling
@interface ViewController (NotificationObserver)
- (void) startObservingNotifications;
- (void) choosePicture:(NSNotification *) notification;
- (void) publish:(NSNotification *) notification;
- (void) displayCameraView:(id) sender;
- (void) alertView:(UIAlertView *) alert clickedButtonAtIndex:(NSInteger) buttonIndex;
@end

@implementation ViewController (NotificationObserver)
- (void) startObservingNotifications
{
    // Subscribe to Choose Picture Notifications
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(choosePicture:) 
                                                 name:FN_CHOOSE_PICTURE
                                               object:nil];
    
    // Subscribe to MyMed Publish Notifications
    [[NSNotificationCenter defaultCenter] addObserver:self 
                                             selector:@selector(publish:) 
                                                 name:FN_PUBLISH 
                                               object:nil];
}

- (void) choosePicture:(NSNotification *) notification
{    
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Picture Source"
                                                    message:nil
                                                   delegate:self
                                          cancelButtonTitle:@"Cancel"
                                          otherButtonTitles:@"Camera", @"Existing Picture", nil];
    [alert show];
}

- (void) publish: (NSNotification *) notification
{
    NSMutableURLRequest *request = [POSTRequestBuilder multipartPostRequestWithURL:[NSURL URLWithString:MY_MED_PUBLISH_URL] 
                                                                 andDataDictionary:[notification userInfo]];
    
    NSURLConnection *connection = [[NSURLConnection alloc] initWithRequest:request delegate:self];
    
    if (!connection) {
        NSLog(@"Publish Connection Failed");
    }
}

- (void) alertView:(UIAlertView *)alert clickedButtonAtIndex:(NSInteger) buttonIndex
{
    if (!self.imagePickerController) {
        self.imagePickerDelegate = [[ImagePickerDelegate alloc] init];
        self.imagePickerController = [[UIImagePickerController alloc] init];
        self.imagePickerController.delegate = self.imagePickerDelegate;
        self.imagePickerController.allowsEditing = YES;
    }
    
    switch (buttonIndex) {
        case CameraAlertButtonIndexCamera:
            //Init the Image Picker in case user want's to publish a Picture            
            self.imagePickerController.sourceType = UIImagePickerControllerSourceTypeCamera;
            [self presentModalViewController:self.imagePickerController animated:YES];            
            break;
        case CameraAlertButtonIndexExistingPicture:
            // Find existing Picture
            self.imagePickerController.sourceType = UIImagePickerControllerSourceTypePhotoLibrary;
            [self presentModalViewController:self.imagePickerController animated:YES];         
            break;            
        default:
            break;
    }
}

- (void) displayCameraView:(id) sender
{    
    self.imagePickerController.sourceType = UIImagePickerControllerSourceTypeCamera;
    self.imagePickerController.mediaTypes = [UIImagePickerController availableMediaTypesForSourceType:UIImagePickerControllerSourceTypeCamera];
    [self presentModalViewController:self.imagePickerController animated:YES];
}

@end

#pragma mark -
#pragma mark - Public Interface Implementation
@implementation ViewController

@synthesize webView;
@synthesize sessionURL;
@synthesize trustedHosts;
@synthesize imagePickerController;
@synthesize imagePickerDelegate;

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
    webView.scalesPageToFit = NO; 
    [self startObservingNotifications];
    trustedHosts = [NSArray arrayWithObjects:@"138.96.242.100", nil];
}

- (void)viewDidUnload
{
    [super viewDidUnload];
    imagePickerController = nil;
    trustedHosts = nil;
    sessionURL = nil;
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
        NSURLRequest *req = [NSURLRequest requestWithURL:url];
//        NSURLConnection *urlConnection = [[NSURLConnection alloc] initWithRequest:req delegate:self];  // Little "hack" to use unsigned SSL certificate.
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
- (BOOL)connection:(NSURLConnection *) connection canAuthenticateAgainstProtectionSpace:(NSURLProtectionSpace *) protectionSpace 
{
    return [protectionSpace.authenticationMethod isEqualToString:NSURLAuthenticationMethodServerTrust];
}

- (void)connection:(NSURLConnection *)connection didReceiveAuthenticationChallenge:(NSURLAuthenticationChallenge *)challenge 
{
    if ([challenge.protectionSpace.authenticationMethod isEqualToString:NSURLAuthenticationMethodServerTrust]) {
        if ([trustedHosts containsObject:challenge.protectionSpace.host]) {
            [challenge.sender useCredential:[NSURLCredential credentialForTrust:challenge.protectionSpace.serverTrust] forAuthenticationChallenge:challenge];
        }
    }
    
    [challenge.sender continueWithoutCredentialForAuthenticationChallenge:challenge];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data
{    
//    NSDictionary *message = [MyMedMessageDecoder dictionaryFromData:data];
//    NSLog(@"--------------------");
//    NSLog(@"%@", message);
}

- (void)connection:(NSURLConnection *) connection didReceiveResponse:(NSURLResponse *) response
{
//    NSLog(@"Cancel bogus SSL connection. -- to remove warning install valid SSL certificate on server");
//    [connection cancel];
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
