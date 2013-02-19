//
//  NavigationModel.h
//  myConsolato
//
//  Created by Emilio on 14/01/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Page.h"

@interface NavigationModel : NSObject<NSXMLParserDelegate>

+(NavigationModel *) getInstance;

- (void)parseXMLFile:(NSString *)pathToFile;

@property (nonatomic, readonly) Page *mainPage;
@end
