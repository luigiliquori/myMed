//
//  FirstViewController.m
//  myConsolato
//
//  Created by Emilio on 27/03/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import "NewsViewController.h"

@interface NewsViewController ()

@end

@implementation  NewsViewController

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        self.title = NSLocalizedString(@"News", nil);
        self.tabBarItem.image = [UIImage imageNamed:@"newspaper"];
    }
    return self;
}
							
- (void)viewDidLoad
{
    [super viewDidLoad];
    NSURL *url = [NSURL URLWithString:@"http://www.consnizza.esteri.it/Consolato_Nizza"];
    NSURLRequest *req = [NSURLRequest requestWithURL:url];
	[self.webView loadRequest:req];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
