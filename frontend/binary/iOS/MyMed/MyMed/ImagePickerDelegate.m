//
//  ImagePickerDelegate.m
//  MyMed
//
//  Created by Nicolas Goles on 11/9/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import "ImagePickerDelegate.h"

@implementation ImagePickerDelegate 

@synthesize picturesArray;

- (void) imagePickerController:(UIImagePickerController *) picker didFinishPickingMediaWithInfo:(NSDictionary *) info
{
    UIImage *image = [info objectForKey:UIImagePickerControllerEditedImage];
    
    if (!image) {
        image = [info objectForKey:UIImagePickerControllerOriginalImage];
    }
    
    [picker dismissModalViewControllerAnimated:YES];
}

- (void) imagePickerControllerDidCancel:(UIImagePickerController *) picker
{
    
}

@end
