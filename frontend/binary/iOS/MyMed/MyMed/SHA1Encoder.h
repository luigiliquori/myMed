//
//  SHA1Encoder.h
//  MyMed
//
//  Created by Nicolas Goles on 10/6/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface SHA1Encoder : NSObject

+ (NSString *) sha512FromString:(NSString *)source;

@end
