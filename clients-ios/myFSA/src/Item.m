//
//  Item.m
//  myFSA
//
//  Created by Emilio on 21/02/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import "Item.h"

@implementation Item
@synthesize title=_title, url=_url, subtitle=_subtitle, desc=_desc, address=_address;

-(void) dealloc
{
    self.title    = nil;
    self.subtitle = nil;
    self.url      = nil;
    self.desc = nil;
    self.address = nil;

    [super dealloc];
}
@end
