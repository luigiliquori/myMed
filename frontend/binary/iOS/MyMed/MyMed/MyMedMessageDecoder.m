//
//  MyMedMessageDecoder.m
//  MyMed
//
//  Created by Nicolas Goles on 10/14/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import "MyMedMessageDecoder.h"
#import "SBJson.h"

static SBJsonParser *jsonParser = nil;

@implementation MyMedMessageDecoder

+ (NSDictionary *) dictionaryFromMessage:(NSData *) message
{
    if (!jsonParser) {
        jsonParser = [[SBJsonParser alloc] init];
    }
    
    NSDictionary *dataDictionary = [jsonParser objectWithData:message];
    return dataDictionary;
}

@end
