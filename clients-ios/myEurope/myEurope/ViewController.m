//
//  ViewController.m
//  myEurope
//
//  Created by Emilio on 20/12/12.
//  Copyright (c) 2012 myMed. All rights reserved.
//

#import "ViewController.h"

#import <QuartzCore/QuartzCore.h>

#import "MyMedClient.h"
#import "RemoteHtmlViewController.h"
#import "CreditsViewController.h"
#import "HtmlViewController.h"
#import "ProfileViewController.h"
#import "ChecklistViewController.h"
#import "BlogCategoriesViewController.h"


typedef enum {
    AlertView_Login = 1
} AlertViewTags;

@interface ViewController () {
    HtmlViewController *_htmlVC;
}

-(IBAction)action_selection:(id)sender;

@property (nonatomic, readonly) HtmlViewController *htmlVC;

@end


#pragma mark -

@implementation ViewController

@synthesize htmlVC=_htmlVC;


- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view, typically from a nib.
    //self.navigationItem.title = NSLocalizedString(@"myEurope", nil);
    self.tableView.backgroundView = [[[UIImageView alloc] initWithImage:[UIImage imageNamed:@"background.jpg"]] autorelease];
    self.navigationItem.rightBarButtonItem = [[[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemAction target:self action:@selector(action_selection:)] autorelease];
    
    _htmlVC = [[HtmlViewController alloc] initWithNibName:@"HtmlViewController" bundle:nil];
    ;
    [_htmlVC view];
    [MyMedClient GetInstance].delegate = _htmlVC;
}
- (void)viewDidUnload
{
    [MyMedClient GetInstance].delegate = nil;
    [_htmlVC release];
    _htmlVC = nil;
    [super viewDidUnload];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return 1;

}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 5;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (UI_USER_INTERFACE_IDIOM()==UIUserInterfaceIdiomPhone)
    {
        if (indexPath.section==0) {
            return 100;
        }
        return 44;
    } else {
        if (indexPath.section==0) {
            return 100;
        }
       return 120;
    }
}

- (UITableViewCell *)getInfoCellForTableView:(UITableView *)tableView
{
    static NSString *CellIdentifier = @"InfoCell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:CellIdentifier] autorelease];
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
        if (UI_USER_INTERFACE_IDIOM()==UIUserInterfaceIdiomPad) {
            cell.textLabel.font = [UIFont italicSystemFontOfSize:24];
        } else {
            cell.textLabel.font = [UIFont italicSystemFontOfSize:11];
        }
        cell.textLabel.textAlignment = NSTextAlignmentCenter;
        cell.textLabel.backgroundColor = [UIColor clearColor];
        cell.textLabel.numberOfLines = 5;
        
        cell.backgroundView = [[[UIImageView alloc] init] autorelease];
        cell.selectedBackgroundView = [[[UIImageView alloc] init] autorelease];
    }
    UIImage *img = [UIImage imageNamed:@"infocellbg.png"];
    ((UIImageView *)cell.backgroundView).image = img;
    ((UIImageView *)cell.selectedBackgroundView).image = img;
    cell.textLabel.text = NSLocalizedString(@"MyEurope is an application of the Alcotra myMed project, which aims to link mayors and municipal transborders. The idea is to provide a tool to simplify and support the creation of European projects as myMed.", nil);
    return cell;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.section==0) {
        return [self getInfoCellForTableView:tableView];
    }
    
    static NSString *CellIdentifier = @"Cell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:CellIdentifier] autorelease];
        cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
        if (UI_USER_INTERFACE_IDIOM()==UIUserInterfaceIdiomPad) {
            cell.textLabel.font = [UIFont boldSystemFontOfSize:28];
        }
        cell.textLabel.textAlignment = NSTextAlignmentCenter;
        cell.textLabel.backgroundColor = [UIColor clearColor];
    
        cell.backgroundView = [[[UIImageView alloc] init] autorelease];
        cell.selectedBackgroundView = [[[UIImageView alloc] init] autorelease];
    }
    
    // Configure the cell...
    UIImage *i = [UIImage imageNamed:@"topandbottomrow.png"];
    ((UIImageView *)cell.backgroundView).image = i;
    ((UIImageView *)cell.selectedBackgroundView).image = [UIImage imageNamed:@"topAndBottomRowSelected.png"];
    switch (indexPath.section) {
        case 1:
            cell.textLabel.text = NSLocalizedString(@"Search for a partnership offer", nil);
            break;
        case 2:
            cell.textLabel.text = NSLocalizedString(@"Insert a partnership offer", nil);
            break;
        case 3:
            cell.textLabel.text = NSLocalizedString(@"Profile", nil);
            break;
        case 4:
            cell.textLabel.text = NSLocalizedString(@"Blog", nil);
            break;
            
        default:
            break;
    }
    
    return cell;
}


- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.section==0) {
        return;
    }
    NSString *js;
    NSString *title;
    switch (indexPath.section) {
        case 1:
            js = @"window.location.hash='#search'";
            title = NSLocalizedString(@"Search", nil);
            self.htmlVC.pagetitle = title;
            self.htmlVC.javascript = js;
            [[MyMedClient GetInstance] loadUrl];
            if (self.htmlVC.isReady) {
                [self.htmlVC.webview stringByEvaluatingJavaScriptFromString:js];
            }
            [self.navigationController pushViewController:self.htmlVC animated:YES];
            break;
            
        case 2:
            js = @"window.location.hash='#post'";
            title = NSLocalizedString(@"Insert offer", nil);
            self.htmlVC.pagetitle = title;
            self.htmlVC.javascript = js;
            [[MyMedClient GetInstance] loadUrl];
            if (self.htmlVC.isReady) {
                [self.htmlVC.webview stringByEvaluatingJavaScriptFromString:js];
            }
            [self.navigationController pushViewController:self.htmlVC animated:YES];
            break;
            
        case 3: {
            RemoteHtmlViewController *vc = [[[RemoteHtmlViewController alloc] initWithNibName:@"RemoteHtmlViewController" bundle:nil] autorelease];
            /*
            ProfileViewController *vc = [[[ProfileViewController alloc] initWithNibName:@"ProfileViewController" bundle:nil] autorelease];
             */
            vc.title = NSLocalizedString(@"Profile", nil);
            [self.navigationController pushViewController:vc animated:YES];
        }
            break;
            
        case 4: {
            BlogCategoriesViewController *vc = [[[BlogCategoriesViewController alloc] initWithNibName:@"BlogCategoriesViewController" bundle:nil] autorelease];
            vc.title = NSLocalizedString(@"Blog", nil);
            [self.navigationController pushViewController:vc animated:YES];

        }
            break;
            
        default:
            break;
    }

}


#pragma mark - Action sheet

-(IBAction)action_selection:(id)sender
{
   UIActionSheet *as = [[UIActionSheet alloc] initWithTitle:NSLocalizedString(@"myEurope", nil)
                                                   delegate:self
                                          cancelButtonTitle:NSLocalizedString(@"Cancel", nil)
                                     destructiveButtonTitle:nil
                                          otherButtonTitles:NSLocalizedString(@"Login", nil),
                        //NSLocalizedString(@"Credits", nil), NSLocalizedString(@"Help", nil), NSLocalizedString(@"Checklist", nil),
                        nil];
    [as showFromBarButtonItem:self.navigationItem.rightBarButtonItem animated:YES];
    [as release];
}

- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (buttonIndex==actionSheet.cancelButtonIndex) {
        return;
    }

    if (buttonIndex==actionSheet.firstOtherButtonIndex) {
        UIAlertView *av = [[UIAlertView alloc] initWithTitle:NSLocalizedString(@"Login", nil)
                                                     message:nil
                                                    delegate:self
                                           cancelButtonTitle:NSLocalizedString(@"Cancel", nil)
                                            otherButtonTitles:NSLocalizedString(@"Ok", nil), nil];
        av.tag = AlertView_Login;
        av.alertViewStyle = UIAlertViewStyleLoginAndPasswordInput;
        NSUserDefaults *defs = [NSUserDefaults standardUserDefaults];
        if ([defs stringForKey:DEFKEY_USERNAME]!=nil) {
            UITextField *username = [av textFieldAtIndex:0];
            username.text = [defs stringForKey:DEFKEY_USERNAME];
        }
        if ([defs stringForKey:DEFKEY_PWD]!=nil) {
            UITextField *password = [av textFieldAtIndex:1];
            password.text = [defs stringForKey:DEFKEY_PWD];
        }        
        [av show];
        [av release];
        
    } else if (buttonIndex==actionSheet.firstOtherButtonIndex+1) {
        CreditsViewController *vc=[[CreditsViewController alloc] initWithNibName:@"CreditsViewController" bundle:nil];
        [self.navigationController pushViewController:vc animated:YES];
        [vc release];

    } else if (buttonIndex==actionSheet.firstOtherButtonIndex+2) {
        RemoteHtmlViewController *vc=[[RemoteHtmlViewController alloc] initWithNibName:@"RemoteHtmlViewController" bundle:nil];
        vc.fname = @"help.htm";
        vc.pagetitle = NSLocalizedString(@"Help", nil);
        [self.navigationController pushViewController:vc animated:YES];
        [vc release];
        
    } else     if (buttonIndex==actionSheet.firstOtherButtonIndex+3) {
        ChecklistViewController *vc=[[ChecklistViewController alloc] initWithNibName:@"ChecklistViewController" bundle:nil];
        vc.title = NSLocalizedString(@"Checklist", nil);
        [self.navigationController pushViewController:vc animated:YES];
        [vc release];
    }

}

#pragma mark -

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (alertView.tag == AlertView_Login) {
        if(buttonIndex==alertView.firstOtherButtonIndex)
        {
            UITextField *username = [alertView textFieldAtIndex:0];
            UITextField *password = [alertView textFieldAtIndex:1];
            NSUserDefaults *defs = [NSUserDefaults standardUserDefaults];
            [defs setObject:username.text forKey:DEFKEY_USERNAME];
            [defs setObject:password.text forKey:DEFKEY_PWD];
            
            [[MyMedClient GetInstance] Login:username.text pwd:password.text];
        }
    }
}

- (BOOL)alertViewShouldEnableFirstOtherButton:(UIAlertView *)alertView
{
    NSString *inputText = [[alertView textFieldAtIndex:0] text];
    NSString *inputText2 = [[alertView textFieldAtIndex:1] text];
    if( [inputText length] > 0 && [inputText2 length] > 0 )
    {
        return YES;
    }
    else
    {
        return NO;
    }
}

@end
