//
//  AppDelegate.h
//  MyMed
//
//  Created by Nicolas Goles on 9/16/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import <UIKit/UIKit.h>

@class LoginViewController;

@interface AppDelegate : UIResponder <UIApplicationDelegate>

@property (strong, nonatomic) UIWindow *window;
@property (strong, nonatomic) LoginViewController *loginViewController;

@end
