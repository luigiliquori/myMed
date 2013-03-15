//
//  AppDelegate.m
//  myEurope
//
//  Created by Emilio on 20/12/12.
//  Copyright (c) 2012 myMed. All rights reserved.
//

#import "AppDelegate.h"

#import "CreditsViewController.h"
#import "ChecklistViewController.h"
#import "FirstScreenViewController.h"
#import "conf.h"
#import "TestFlight.h"
#import "PagesViewController.h"
#import "NavigationModel.h"
#import "HtmlViewController.h"

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
}

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions
{
#if TARGET_OS_IPHONE
#ifdef TESTING
    [TestFlight setDeviceIdentifier:[[UIDevice currentDevice] uniqueIdentifier]];
#endif
    [TestFlight takeOff:@"4b067873-cc05-43a5-8e74-31ad24e51a24"];
#endif
    
    self.window = [[[UIWindow alloc] initWithFrame:[[UIScreen mainScreen] bounds]] autorelease];

    // Main
    UIColor *color = [UIColor colorWithRed:0.1 green:0.4 blue:0.65 alpha:1.0];
    
    NSString *xibName;
    if ([[UIDevice currentDevice] userInterfaceIdiom] == UIUserInterfaceIdiomPhone) {
        xibName = @"FirstScreenViewController";
    } else {
        xibName = @"FirstScreenViewControllerIPad";
    }
    
    FirstScreenViewController *firstVC = [[[FirstScreenViewController alloc] initWithNibName:xibName bundle:nil] autorelease];
    firstVC.tabBarItem.image = [UIImage imageNamed:@"sun"];
    firstVC.title = NSLocalizedString(@"myRiviera", nil);
    UINavigationController *firstNav = [[[UINavigationController alloc] initWithRootViewController:firstVC] autorelease];
    firstNav.navigationBar.tintColor = color;
    firstNav.title = NSLocalizedString(@"myRiviera", nil);
    
    
    HtmlViewController *mainVC = [[[HtmlViewController alloc] initWithNibName:@"HtmlViewController" bundle:nil] autorelease];
    mainVC.title = NSLocalizedString(@"Social Network", nil);
    mainVC.tabBarItem.image = [UIImage imageNamed:@"group"];

    
    // Credits
    CreditsViewController *creditsVc = [[[CreditsViewController alloc] initWithNibName:@"CreditsViewController" bundle:nil] autorelease];
    creditsVc.title = NSLocalizedString(@"Credits", nil);
    creditsVc.tabBarItem.image = [UIImage imageNamed:@"icoinfo"];
    
    /*
    ChecklistViewController *checkVc=[[[ChecklistViewController alloc] initWithNibName:@"ChecklistViewController" bundle:nil] autorelease];
    checkVc.title = NSLocalizedString(@"To do", nil);
    checkVc.tabBarItem.image = [UIImage imageNamed:@"checkmark.png"];
    UINavigationController *checkNav = [[[UINavigationController alloc] initWithRootViewController:checkVc] autorelease];
    checkNav.navigationBar.tintColor = color;
    */
    
    /*
    NavigationModel *navPages = [NavigationModel getInstance];
    PagesViewController *pagesVc=[[[PagesViewController alloc] initWithNibName:@"PagesViewController" bundle:nil] autorelease];
    navPages.mainPage.title = NSLocalizedString(@"Universities", nil);
    pagesVc.page = [navPages mainPage];
    UINavigationController *pagesNav = [[[UINavigationController alloc] initWithRootViewController:pagesVc] autorelease];
    pagesNav.navigationBar.tintColor = color;
    pagesNav.title = NSLocalizedString(@"Universities", nil);
    pagesNav.tabBarItem.image = [UIImage imageNamed:@"univlist"];
    */
    
    self.tabBarController = [[[UITabBarController alloc] init] autorelease];
    self.tabBarController.viewControllers = [NSArray arrayWithObjects:firstNav, mainVC, creditsVc,  nil];
    self.tabBarController.tabBar.tintColor = color;
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
