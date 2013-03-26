//
//  CreditsViewController.h
//  myEurope
//
//  Created by Emilio on 20/12/12.
//  Copyright (c) 2012 myMed. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface CreditsViewController : UIViewController<UITableViewDataSource,UITableViewDelegate>

@property (nonatomic, strong) IBOutletCollection(UITableViewCell) NSArray *cellsCons;
@property (nonatomic, strong) IBOutletCollection(UITableViewCell) NSArray *cellFund;
-(IBAction)action_done:(id)sender;
@end
