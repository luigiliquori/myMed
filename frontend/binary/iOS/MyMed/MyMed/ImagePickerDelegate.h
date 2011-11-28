//
//  ImagePickerDelegate.h
//  MyMed
//
//  Created by Nicolas Goles on 11/9/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface ImagePickerDelegate : NSObject <UINavigationControllerDelegate,
                                           UIImagePickerControllerDelegate>

- (void) imagePickerController:(UIImagePickerController *) picker didFinishPickingMediaWithInfo:(NSDictionary *) info;
- (void) imagePickerControllerDidCancel:(UIImagePickerController *) picker;

@property (nonatomic, retain) NSMutableArray *picturesArray;

@end
