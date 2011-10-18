//
//  LoginViewController.h
//  MyMed
//
//  Created by Nicolas Goles on 10/5/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface LoginViewController : UIViewController <UITextFieldDelegate>
{
    NSData *receivedData;
}

@property (nonatomic, retain) IBOutlet UITextField *eMailField;
@property (nonatomic, retain) IBOutlet UITextField *passwordField;

@end
