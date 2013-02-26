//
//  Page.h
//  myConsolato
//
//  Created by Emilio on 14/01/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Item.h"

@interface Page : Item

@property (nonatomic, readonly) NSMutableArray *subPages;
@property (nonatomic, readonly) NSMutableArray *items;
@property (nonatomic, retain) NSString *text;
@property (nonatomic, assign) Page *parent;
@end
