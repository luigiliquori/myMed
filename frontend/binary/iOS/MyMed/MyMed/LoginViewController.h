//
//  LoginViewController.h
//  MyMed
//
//  Created by Nicolas Goles on 10/5/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import <UIKit/UIKit.h>

@class ViewController;

@interface LoginViewController : UIViewController <UITextFieldDelegate>
{
    NSData *receivedData;
    NSString *accessToken;
    NSString *socialNetwork;
    ViewController *webViewController;
}

- (IBAction) submitLogin:(id)sender;

@property (nonatomic, retain) IBOutlet UITextField *eMailField;
@property (nonatomic, retain) IBOutlet UITextField *passwordField;

@end
