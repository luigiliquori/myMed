//
//  Page.m
//  myConsolato
//
//  Created by Emilio on 14/01/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import "Page.h"

@interface Page()
{
    NSMutableArray *_subPages;
}
@end


@implementation Page

@synthesize subPages=_subPages, title=_title, text=_text, url=_url, items=_items;
@synthesize parent=_parent;
@synthesize subtitle=_subtitle;

-(id) init
{
    self = [super init];
    if (self) {
        _subPages = [[NSMutableArray alloc] init];
        _items = [[NSMutableArray alloc] init];
        self.parent = nil;
    }
    return self;
}

-(void) dealloc
{
    self.title    = nil;
    self.text     = nil;
    self.parent   = nil;
    self.subtitle = nil;
    self.url      = nil;
    [_items release];
    _items = nil;
    [_subPages release];
    _subPages = nil;
    [super dealloc];
}
@end
