//
//  AppointmentViewController.m
//  myConsolato
//
//  Created by Emilio on 08/04/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import "AppointmentViewController.h"

#define URL_APPUNTAMENTO @"https://prenotaonline.esteri.it/login.aspx?cidsede=100097&returnUrl=//"

@interface AppointmentViewController ()

@end

@implementation AppointmentViewController


- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        self.title = NSLocalizedString(@"Appuntamento",nil);
        self.tabBarItem.image = [UIImage imageNamed:@"calendar"];
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view from its nib.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
