//
//  ViewController.m
//  MyMed
//
//  Created by Nicolas Goles on 9/16/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import "ViewController.h"
#import "Reachability.h"

NSString * const MY_MED_URL = @"http://mymed2.sophia.inria.fr/mobile/";
NSString * const GOOGLE_URL = @"google.fr"; //Don't add HTTP or www. for Reachability.

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
    
    if ([self reachable]) {
        [webView loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:MY_MED_URL]]];
    } else {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"No internet connection" 
                                                        message:@"You need an active internet connection to use this application" 
                                                       delegate:self 
                                              cancelButtonTitle:@"OK"
                                              otherButtonTitles:nil];
        [alert show];
    }

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

#pragma mark - Reachable

- (BOOL) reachable
{
    Reachability *r = [Reachability reachabilityWithHostName:GOOGLE_URL];
    NetworkStatus internetStatus = [r currentReachabilityStatus];
    
    if (internetStatus == kNotReachable) {
        return NO;
    }
    
    return YES;
}

@end
