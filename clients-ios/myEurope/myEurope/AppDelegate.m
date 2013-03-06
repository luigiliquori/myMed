//
//  AppDelegate.m
//  myEurope
//
//  Created by Emilio on 20/12/12.
//  Copyright (c) 2012 myMed. All rights reserved.
//

#import "AppDelegate.h"

#import "ViewController.h"
#import "MyMedClient.h"
#import "RemoteHtmlViewController.h"
#import "CreditsViewController.h"
#import "ChecklistViewController.h"
#import "FirstScreenViewController.h"
#import "conf.h"

@implementation AppDelegate

@synthesize tabBarController=_tabBarController;


- (void)dealloc
{
    self.window = nil;
    self.tabBarController = nil;

    [super dealloc];
}

-(void) preloadData
{
    // To force the preloading of web data
    [MyMedClient GetInstance];
    
}

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions
{
#if TARGET_OS_IPHONE
#ifdef TESTING
    [TestFlight setDeviceIdentifier:[[UIDevice currentDevice] uniqueIdentifier]];
#endif
    [TestFlight takeOff:@"9f666649-4b5d-40dc-98f5-e205270b2a7b"];
#endif
    
    self.window = [[[UIWindow alloc] initWithFrame:[[UIScreen mainScreen] bounds]] autorelease];

    // Main
    /*
    UIViewController *vcMain;
    if ([[UIDevice currentDevice] userInterfaceIdiom] == UIUserInterfaceIdiomPhone) {
        vcMain = [[[ViewController alloc] initWithNibName:@"ViewController_iPhone" bundle:nil] autorelease];
    } else {
        vcMain = [[[ViewController alloc] initWithNibName:@"ViewController_iPad" bundle:nil] autorelease];
    }
    vcMain.title = NSLocalizedString(@"myEurope", nil);
    UINavigationController *navMain = [[[UINavigationController alloc] initWithRootViewController:vcMain] autorelease];
    navMain.navigationBar.tintColor = [UIColor colorWithRed:0.3 green:0.6 blue:0.85 alpha:1.0];
    navMain.title = NSLocalizedString(@"Social Network", nil);
    navMain.tabBarItem.image = [UIImage imageNamed:@"group"];
    */
    
    NSString *xibName;
    if ([[UIDevice currentDevice] userInterfaceIdiom] == UIUserInterfaceIdiomPhone) {
        xibName = @"FirstScreenViewController";
    } else {
        xibName = @"FirstScreenViewControllerIPad";
    }
    
    FirstScreenViewController *firstVC = [[[FirstScreenViewController alloc] initWithNibName:xibName bundle:nil] autorelease];
    firstVC.title = NSLocalizedString(@"myEurope", nil);
    firstVC.tabBarItem.image = [UIImage imageNamed:@"icoeu"];
    
    
    RemoteHtmlViewController *mainVC = [[[RemoteHtmlViewController alloc] initWithNibName:@"RemoteHtmlViewController" bundle:nil] autorelease];
    mainVC.title = NSLocalizedString(@"Social Network", nil);
    mainVC.tabBarItem.image = [UIImage imageNamed:@"group"];
    mainVC.url = WEBAPP_URL;
    
    
    // Credits
    CreditsViewController *creditsVc = [[[CreditsViewController alloc] initWithNibName:@"CreditsViewController" bundle:nil] autorelease];
    creditsVc.title = NSLocalizedString(@"Credits", nil);
    creditsVc.tabBarItem.image = [UIImage imageNamed:@"icoinfo"];
    
    /*
    RemoteHtmlViewController *helpVc=[[[RemoteHtmlViewController alloc] initWithNibName:@"RemoteHtmlViewController" bundle:nil]  autorelease];
    helpVc.fname = @"help.htm";
    helpVc.pagetitle = NSLocalizedString(@"Help", nil);
    helpVc.title = NSLocalizedString(@"Help", nil);
    helpVc.tabBarItem.image = [UIImage imageNamed:@"icohelp.png"];
    */
    
    ChecklistViewController *checkVc=[[[ChecklistViewController alloc] initWithNibName:@"ChecklistViewController" bundle:nil] autorelease];
    checkVc.title = NSLocalizedString(@"Checklist", nil);
    checkVc.tabBarItem.image = [UIImage imageNamed:@"checkmark.png"];
    UINavigationController *checkNav = [[[UINavigationController alloc] initWithRootViewController:checkVc] autorelease];
    checkNav.navigationBar.tintColor = [UIColor colorWithRed:0.1 green:0.4 blue:0.65 alpha:1.0];

    
    self.tabBarController = [[[UITabBarController alloc] init] autorelease];
    self.tabBarController.viewControllers = [NSArray arrayWithObjects:firstVC, mainVC, checkNav, creditsVc,  nil];
    self.tabBarController.tabBar.tintColor = [UIColor colorWithRed:0.1 green:0.4 blue:0.65 alpha:1.0];
    if ([self.tabBarController respondsToSelector:@selector(restorationIdentifier)]) {
        self.tabBarController.restorationIdentifier = @"tabbar";
    }
    self.window.rootViewController = self.tabBarController;

    
    [self.window makeKeyAndVisible];
    [self performSelector:@selector(preloadData) withObject:nil afterDelay:0.4];
    return YES;
}

- (void)applicationWillResignActive:(UIApplication *)application
{
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
}

- (void)applicationDidEnterBackground:(UIApplication *)application
{
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later. 
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
}

- (void)applicationWillEnterForeground:(UIApplication *)application
{
    // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
}

- (void)applicationDidBecomeActive:(UIApplication *)application
{
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
}

- (void)applicationWillTerminate:(UIApplication *)application
{
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
}

@end
