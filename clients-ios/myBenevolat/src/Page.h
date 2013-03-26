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
@property (nonatomic, strong) NSString *text;
@property (nonatomic, weak) Page *parent;
@end
