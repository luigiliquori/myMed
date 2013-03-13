//
//  CreditsViewController.h
//  myEurope
//
//  Created by Emilio on 20/12/12.
//  Copyright (c) 2012 myMed. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface CreditsViewController : UITableViewController

@property (nonatomic, retain) IBOutletCollection(UITableViewCell) NSArray *cellsCons;
@property (nonatomic, retain) IBOutletCollection(UITableViewCell) NSArray *cellFund;

@end
