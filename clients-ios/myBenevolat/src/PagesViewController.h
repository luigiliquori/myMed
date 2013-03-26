//
//  PagesViewController.h
//  myConsolato
//
//  Created by Emilio on 15/01/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "Page.h"

@interface PagesViewController : UITableViewController<UIActionSheetDelegate>
@property (nonatomic, strong) Page *page;

@end
