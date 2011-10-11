//
//  LoginViewController.h
//  MyMed
//
//  Created by Nicolas Goles on 10/5/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "MyMedHttpHandler.h"

@class MyMedHttpHandler;

@interface LoginViewController : UIViewController <UITextFieldDelegate, MyMedHttpHandlerDelegate>
{
    MyMedHttpHandler *httpHandler;
}

@property (nonatomic, retain) IBOutlet UITextField *eMailField;
@property (nonatomic, retain) IBOutlet UITextField *passwordField;

@end
