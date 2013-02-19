//
//  ViewController.m
//  myConsolato
//
//  Created by Emilio on 14/01/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import "ViewController.h"
#import "NavigationModel.h"
#import "PagesViewController.h"

@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view, typically from a nib.
    [NavigationModel getInstance];
    self.title = NSLocalizedString(@"myConsolato", nil);
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

-(IBAction)action_info:(id)sender
{
    PagesViewController *vc = [[PagesViewController alloc] initWithNibName:@"PagesViewController" bundle:nil];
    vc.page = [NavigationModel getInstance].mainPage;
    [self.navigationController pushViewController:vc animated:YES];
    [vc release];
}

@end
