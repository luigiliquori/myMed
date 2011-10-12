//
//  LoginViewController.h
//  MyMed
//
//  Created by Nicolas Goles on 10/5/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import <UIKit/UIKit.h>

@class SBJsonParser;

@interface LoginViewController : UIViewController <UITextFieldDelegate>
{
    NSData *receivedData;
    SBJsonParser *jsonParser;
}

@property (nonatomic, retain) IBOutlet UITextField *eMailField;
@property (nonatomic, retain) IBOutlet UITextField *passwordField;

@end
