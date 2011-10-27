//
//  ViewController.h
//  MyMed
//
//  Created by Nicolas Goles on 9/16/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController <UIWebViewDelegate
                                            , UIImagePickerControllerDelegate
                                            , UINavigationControllerDelegate>
{
    NSURL *sessionURL;
    NSArray *trustedHosts;
    UIImagePickerController *imagePickerController;
}

@property (nonatomic, retain) IBOutlet UIWebView *webView;

- (void) loadMyMedURL:(NSURL *) url;

@end
