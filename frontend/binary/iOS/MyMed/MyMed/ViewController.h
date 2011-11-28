//
//  ViewController.h
//  MyMed
//
//  Created by Nicolas Goles on 9/16/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import <UIKit/UIKit.h>

@class ImagePickerDelegate;

@interface ViewController : UIViewController <UIWebViewDelegate
                                            , UIImagePickerControllerDelegate
                                            , UINavigationControllerDelegate>

@property (nonatomic, retain) IBOutlet UIWebView *webView;
@property (nonatomic, retain) NSURL *sessionURL;
@property (nonatomic, retain) NSArray *trustedHosts;
@property (nonatomic, retain) UIImagePickerController *imagePickerController;
@property (nonatomic, retain) ImagePickerDelegate *imagePickerDelegate;

- (void) loadMyMedURL:(NSURL *) url;

@end
