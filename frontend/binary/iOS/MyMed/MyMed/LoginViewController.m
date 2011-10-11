//
//  LoginViewController.m
//  MyMed
//
//  Created by Nicolas Goles on 10/5/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import "LoginViewController.h"
#import "ConnectionStatusChecker.h"
#import "MyMedHttpHandler.h"
#import "SHA1Encoder.h"

#pragma mark - const definitions

static NSString * const ERROR_MESSAGE = @"message";
static NSString * const URL_MYMED_LOGIN = @"http://mymed2.sophia.inria.fr:8080/mymed_backend/AuthenticationRequestHandler";

#pragma mark - Private Methods

@interface LoginViewController (private)

- (void) submitLogin:(NSString *) login andPassword:(NSString *) password;
- (BOOL) displayAlertForErrorInData:(NSDictionary *) data;

@end

@implementation LoginViewController (private)

- (void) submitLogin:(NSString *) login andPassword:(NSString *) password
{
    [httpHandler submitLoginURL:[NSString stringWithFormat:@"%@?code=1&login=%@&password=%@", URL_MYMED_LOGIN, login, password]];    
}

- (BOOL) displayAlertForErrorInData:(NSDictionary *) data
{
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

@end

#pragma mark - Public Interface

@implementation LoginViewController

@synthesize eMailField = eMailField_;
@synthesize passwordField = passwordField_;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];

    if (self) {
        httpHandler = [[MyMedHttpHandler alloc] init];
        [httpHandler setDelegate:self];
    }
    
    return self;
}

- (void)didReceiveMemoryWarning
{
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc that aren't in use.
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
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    // Return YES for supported orientations
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
            //NSLog(@"%@", [SHA1Encoder SHA1HashForString:self.passwordField.text]);
            //[self submitLogin:self.eMailField.text andPassword:[SHA1Encoder SHA1HashForString:self.passwordField.text]];
        }
    }

    return NO;
}

#pragma mark - MyMedHttpHandlerDelegate

- (void) receivedHTTPData:(NSDictionary *) data
{    
    if ([self displayAlertForErrorInData:data]) {
        // Handle Error
    }
}

- (void) connectionError:(NSError *)error
{
    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Connection Error" 
                                                    message:@"Unable to connect to MyMed, try again later."
                                                   delegate:self 
                                          cancelButtonTitle:@"Ok" 
                                          otherButtonTitles:nil, nil];
    [alert show];
}


@end
