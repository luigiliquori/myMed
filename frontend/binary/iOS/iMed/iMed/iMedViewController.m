//
//  iMedViewController.m
//  iMed
//
//  Created by Riccardo Loti on 09/10/12.
//  Copyright (c) 2012 MyMed. All rights reserved.
//

#import "iMedViewController.h"

@interface iMedViewController ()
  
@end

@implementation iMedViewController
@synthesize webView;

- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view, typically from a nib.
   [self.webView loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:@"http://www.mymed.fr"]]];
}

- (void)viewDidUnload
{
  [self setWebView:nil];
  [self setWebView:nil];
    [super viewDidUnload];
    // Release any retained subviews of the main view.
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
  if ([[UIDevice currentDevice] userInterfaceIdiom] == UIUserInterfaceIdiomPhone) {
      return (interfaceOrientation != UIInterfaceOrientationPortraitUpsideDown);
  } else {
      return YES;
  }
}

@end
