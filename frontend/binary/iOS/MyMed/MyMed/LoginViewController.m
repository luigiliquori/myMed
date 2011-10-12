//
//  LoginViewController.m
//  MyMed
//
//  Created by Nicolas Goles on 10/5/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import "LoginViewController.h"
#import "ViewController.h"
#import "POSTRequestBuilder.h"
#import "ConnectionStatusChecker.h"
#import "SHA1Encoder.h"
#import "SBJson.h"

#pragma mark - const definitions
static NSString * const ERROR_MESSAGE = @"message";
static NSString * const URL_MYMED_AUTH = @"http://mymed2.sophia.inria.fr/mobile/tools/authentication.php";
static NSString * const URL_MYMED_SESSION = @"http://mymed2.sophia.inria.fr/mobile/index.php"; 

#pragma mark - Private Methods
@interface LoginViewController (private)
- (NSDictionary *) POSTdictionaryWithKeys:(NSArray *) keys andObjects:(NSArray *) objects;
- (void) submitLogin:(NSString *) login andPassword:(NSString *) password;
- (void) openMyMedSessionWithLogin:(NSString *) login andPassword:(NSString *) password;
- (BOOL) displayAlertForErrorInData:(NSDictionary *) data;
- (void) presentMyMedWebView;
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
   NSDictionary *dictionary = [self POSTdictionaryWithKeys:[NSArray arrayWithObjects:@"login", @"password", nil] 
                                                andObjects:[NSArray arrayWithObjects:login, [SHA1Encoder sha512FromString:password], nil]];
    
    // Forge request and stablish connection.
    NSMutableURLRequest *request = [POSTRequestBuilder multipartPostRequestWithURL:[NSURL URLWithString:URL_MYMED_AUTH] 
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
    NSMutableURLRequest *request = [POSTRequestBuilder multipartPostRequestWithURL:[NSURL URLWithString:URL_MYMED_SESSION] 
                                                                 andDataDictionary:dictionary];
    NSURLConnection *connection = [[NSURLConnection alloc] initWithRequest:request delegate:self];
    
    if (connection) {
        NSLog(@"Open Session Success!");
    } else {
        NSLog(@"Open Session Fail!");
    }
}

- (BOOL) displayAlertForErrorInData:(NSDictionary *) data
{   
    if (!data) {
        return YES;
    }
    
    NSDictionary *error = [data objectForKey:@"error"];
    if (error) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:[error objectForKey:ERROR_MESSAGE] 
                                                        message:nil 
                                                       delegate:self 
                                              cancelButtonTitle:@"Ok" 
                                              otherButtonTitles:nil, nil];
        [alert show];
        return YES;
    }
    return NO;
}

- (void) presentMyMedWebView 
{
    ViewController *webViewController = [[ViewController alloc] initWithNibName:@"ViewController" bundle:nil];
    webViewController.delegate = self;
    webViewController.modalTransitionStyle = UIModalTransitionStyleFlipHorizontal;
    [self presentModalViewController:webViewController animated:YES];
    [webViewController loadMyMed];
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
        jsonParser = [[SBJsonParser alloc] init];
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
            [self submitLogin:self.eMailField.text andPassword:[SHA1Encoder sha512FromString:self.passwordField.text]];
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
    NSDictionary *dataDictionary = [jsonParser objectWithData:data];
    
    NSLog(@"%@", dataDictionary);
    
    if ([self displayAlertForErrorInData:dataDictionary]) {
        // Error in the JSON data or dataDictionary is nil.
    } else { //TODO: Wait until multiple sessions are validated in Backend and improve.
        
        NSString *message = [dataDictionary objectForKey:@"message"];
        
        if ([message isEqualToString:@"authentication ok"]) {
            [self openMyMedSessionWithLogin:self.eMailField.text andPassword:self.passwordField.text];
        }
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
