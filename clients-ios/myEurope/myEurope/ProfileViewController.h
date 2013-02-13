//
//  ProfileViewController.h
//  myEurope
//
//  Created by Emilio on 16/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "HtmlViewController.h"


@interface ProfileViewController : UITableViewController
@property (nonatomic, retain) IBOutlet UITableViewCell *cellName;
@property (nonatomic, retain) IBOutlet UITextField *txtName;
@property (nonatomic, retain) IBOutlet UITextField *txtFamName;
@property (nonatomic, retain) IBOutlet UITextField *txtEmail;
@property (nonatomic, retain) IBOutlet UITextField *txtPwd;
@property (nonatomic, retain) IBOutlet UITextField *txtConfPwd;

@property (nonatomic, retain) IBOutlet UIButton *infoBtn;
@property (nonatomic, retain) IBOutlet UIButton *sendBtn;
@property (nonatomic, readonly) HtmlViewController *htmlVC;

- (IBAction) displayTermsAndConds:(id)sender;

@end
