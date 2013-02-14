//
//  Page.h
//  myConsolato
//
//  Created by Emilio on 14/01/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface Page : NSObject

@property (nonatomic, readonly) NSMutableArray *subPages;
@property (nonatomic, readonly) NSMutableArray *items;
@property (nonatomic, retain) NSString *title;
@property (nonatomic, retain) NSString *subtitle;
@property (nonatomic, retain) NSString *text;
@property (nonatomic, retain) NSString *url;
@property (nonatomic, assign) Page *parent;
@end
