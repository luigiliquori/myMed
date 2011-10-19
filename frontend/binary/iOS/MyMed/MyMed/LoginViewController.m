//
//  LoginViewController.m
//  MyMed
//
//  Created by Nicolas Goles on 10/5/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import "LoginViewController.h"
#import "ViewController.h"
#import "MyMedMessageDecoder.h"
#import "POSTRequestBuilder.h"
#import "ConnectionStatusChecker.h"
#import "SHA1Encoder.h"

#pragma mark - const definitions

// URL'S
static NSString * const M_URL_AUTH = @"http://mymed2.sophia.inria.fr:8080/mymed_backend/AuthenticationRequestHandler";
static NSString * const M_URL_SESSION = @"http://mymed2.sophia.inria.fr:8080/mymed_backend/SessionRequestHandler";

// STATUS
static const unsigned M_STATUS_AUTH_WRONG_PASS = 403;
static const unsigned M_STATUS_AUTH_WRONG_LOGIN = 404;
static const unsigned M_STATUS_AUTH_OK = 200;

// CODES for Backend/Frontend
static const unsigned M_CREATE = 0;
static const unsigned M_READ = 1;
static const unsigned M_UPDATE = 2;
static const unsigned M_DELETE = 3;

#pragma mark - Private Methods
@interface LoginViewController (private)
- (NSDictionary *) POSTdictionaryWithKeys:(NSArray *) keys andObjects:(NSArray *) objects;
- (void) submitLogin:(NSString *) login andPassword:(NSString *) password;
- (void) openMyMedSessionWithLogin:(NSString *) login andPassword:(NSString *) password;
- (void) displayAlertWithTittle:(NSString *) tittle andMessage:(NSString *) message;
- (void) presentMyMedWebViewWithURL:(NSURL *) url;
@end

@implementation LoginViewController (private)

- (NSDictionary *) POSTdictionaryWithKeys:(NSArray *) keys andObjects:(NSArray *) objects
{
    assert([keys count] == [objects count]);
    NSDictionary *dictionary = [[NSDictionary alloc] initWithObjects:objects forKeys:keys];
    return dictionary;
}

- (void) submitLogin:(NSString *) login andPassword:(NSString *) password
{
    // Pack data for POST request
    NSDictionary *dictionary = [self POSTdictionaryWithKeys:[NSArray arrayWithObjects:@"login", @"password", @"code", nil] 
                                                andObjects:[NSArray arrayWithObjects:login, [SHA1Encoder sha512FromString:password], [NSString stringWithFormat:@"%d", M_READ], nil]];
    
    // Forge request and stablish connection.
    NSMutableURLRequest *request = [POSTRequestBuilder urlEncodedPostRequestWithURL:[NSURL URLWithString:M_URL_AUTH] 
                                                                  andDataDictionary:dictionary];
    
    NSURLConnection *connection = [[NSURLConnection alloc] initWithRequest:request delegate:self];
    
    if (connection) {
        NSLog(@"Connection Success!");
    } else {
        NSLog(@"Connection Fail");
    }
}

- (void) openMyMedSessionWithLogin:(NSString *) login andPassword:(NSString *) password
{
    // Pack Data for POST request
    NSDictionary *dictionary = [self POSTdictionaryWithKeys:[NSArray arrayWithObjects:@"login", @"password", @"signin", @"ismobile", nil] 
                                                 andObjects:[NSArray arrayWithObjects:login, [SHA1Encoder sha512FromString:password], @"true", @"true", nil]];
    
    // Forge request and stablish a connection
    NSMutableURLRequest *request = [POSTRequestBuilder multipartPostRequestWithURL:[NSURL URLWithString:M_URL_SESSION] 
                                                                 andDataDictionary:dictionary];
    NSURLConnection *connection = [[NSURLConnection alloc] initWithRequest:request delegate:self];
    
    if (connection) {
        NSLog(@"Open Session Success!");
    } else {
        NSLog(@"Open Session Fail!");
    }
}

- (void) displayAlertWithTittle:(NSString *) tittle andMessage:(NSString *) message
{   
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:tittle
                                                    message:message 
                                                   delegate:self 
                                          cancelButtonTitle:@"Ok" 
                                          otherButtonTitles:nil, nil];
    [alert show];
}

- (void) presentMyMedWebViewWithURL:(NSURL *) url
{
    ViewController *webViewController = [[ViewController alloc] initWithNibName:@"ViewController" bundle:nil];
    webViewController.modalTransitionStyle = UIModalTransitionStyleFlipHorizontal;
    [self presentModalViewController:webViewController animated:YES];
    [webViewController loadMyMedURL:url];
}

@end

#pragma mark - Public Interface
@implementation LoginViewController

@synthesize eMailField = eMailField_;
@synthesize passwordField = passwordField_;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];

    if (self) {
    }
    
    return self;
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
}

#pragma mark - View lifecycle
- (void)viewDidLoad
{
    [super viewDidLoad];
    
    //Brings out keyboard automatically on the e-mail field.
    [self.eMailField becomeFirstResponder];
}

- (void)viewDidUnload
{
    [super viewDidUnload];
    self.eMailField = nil;
    self.passwordField = nil;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

#pragma mark - UITextFieldDelegate
-(BOOL) textFieldShouldReturn:(UITextField *) textField;
{
    // Try to find next responder
    NSInteger nextTag = textField.tag + 1;
    UIResponder* nextResponder = [textField.superview viewWithTag:nextTag];
    
    if (nextResponder) {
        [nextResponder becomeFirstResponder];
    } else {
        if ([ConnectionStatusChecker doesHaveConnectivity] && ![[self.eMailField text] isEqualToString:@""]) {
            [self submitLogin:self.eMailField.text andPassword:self.passwordField.text];
        }
    }

    return NO;
}

#pragma mark - MyMedHttpHandlerDelegate
- (BOOL) connection:(NSURLConnection *)connection canAuthenticateAgainstProtectionSpace:(NSURLProtectionSpace *)protectionSpace 
{
    return [protectionSpace.authenticationMethod isEqualToString:NSURLAuthenticationMethodServerTrust];
}

- (void) connection:(NSURLConnection *)connection didReceiveAuthenticationChallenge:(NSURLAuthenticationChallenge *)challenge 
{
    [challenge.sender useCredential:[NSURLCredential credentialForTrust:challenge.protectionSpace.serverTrust] forAuthenticationChallenge:challenge];
    [challenge.sender continueWithoutCredentialForAuthenticationChallenge:challenge];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data
{    
    NSDictionary *message = [MyMedMessageDecoder dictionaryFromData:data];
    
    if ([[message objectForKey:M_KEY_STATUS] intValue] != M_STATUS_OK) {
        [self displayAlertWithTittle:@"Login Error" andMessage:(NSString *)[message objectForKey:M_KEY_DESCRIPTION]];
        return;
    }
    
    //  If We are logged in
    //  NSString Casts are needed to transform from NSCFString to NSString *
    //  Note: NSCFString doesn't support isEqualToString:
    NSString *messageHandler = (NSString *)[message objectForKey:M_KEY_HANDLER]; 
    NSString *messageMethod = (NSString *)[message objectForKey:M_KEY_METHOD];
    if ([messageHandler isEqualToString:M_HANDLER_AUTH_REQUEST] && [messageMethod isEqualToString:M_METHOD_READ]) {
        NSDictionary *data = (NSDictionary *)[message objectForKey:M_KEY_DATA];
        NSString *url = [data objectForKey:M_KEY_URL];
        NSString *token = [data objectForKey:M_KEY_ACCESS_TOKEN];
        NSString *string = [NSString stringWithFormat:@"%@?accessToken=%@", url, token];
        NSLog(@"%@", string);
        
        [self presentMyMedWebViewWithURL:[NSURL URLWithString:[NSString stringWithFormat:@"%@?accessToken=%@", url, token]]];
    }
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error
{  
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Connection Error" 
                                                    message:@"Unable to connect to MyMed, try again later."
                                                   delegate:self 
                                          cancelButtonTitle:@"Ok" 
                                          otherButtonTitles:nil, nil];
    [alert show];
}

@end
