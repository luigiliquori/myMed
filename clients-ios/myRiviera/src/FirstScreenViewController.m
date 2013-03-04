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
-(IBAction)action_options:(id)sender;
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
    
    self.navigationItem.rightBarButtonItem = [[[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemAction target:self action:@selector(action_options:)] autorelease];

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

-(IBAction)action_options:(id)sender
{
  UIActionSheet *as = [[UIActionSheet alloc] initWithTitle:nil
                                                  delegate:self
                                         cancelButtonTitle:NSLocalizedString(@"Cancel", nil)
                                    destructiveButtonTitle:nil
                                         otherButtonTitles:NSLocalizedString(@"Email about this app", nil), @"MyMed web site", nil];
    [as showFromBarButtonItem:sender animated:YES];
    [as release];
}


- (IBAction)sendEmail {
    if ([MFMailComposeViewController canSendMail])
    {
        MFMailComposeViewController *controller = [[MFMailComposeViewController
                                                    alloc] init];
        controller.mailComposeDelegate = self;
        [controller setSubject:NSLocalizedString(@"myRiviera", nil)];
        NSString *emailBody = @"<a href='https://itunes.apple.com/app/id608828203?ls=1&mt=8'>https://itunes.apple.com/us/app/myedu-mymed/id608828203</a>";
        [controller setMessageBody:emailBody isHTML:YES];
        //[controller setToRecipients:recipients];
        [self presentModalViewController:controller animated:YES];
        [controller release];
    }
    else
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Alert"
                                                        message:@"Your device is not set up for email." delegate:self
                                              cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];   
        [alert release];
    } 
}

- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (buttonIndex==actionSheet.cancelButtonIndex) {
        return;
    }
    
    if (buttonIndex==actionSheet.firstOtherButtonIndex) {
        [self sendEmail];
        return;
    } else if (buttonIndex==(actionSheet.firstOtherButtonIndex+1)) {
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.mymed.fr"]];
        return;
    }
}

-(void)mailComposeController:(MFMailComposeViewController*)controller didFinishWithResult:(MFMailComposeResult)result error:(NSError*)error {
    [self dismissModalViewControllerAnimated:YES];
}

@end
