//
//  POSTRequestBuilder.h
//  MyMed
//
//  Created by Nicolas Goles on 10/12/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface POSTRequestBuilder : NSObject

+ (NSMutableURLRequest *) multipartPostRequestWithURL:(NSURL *)url andDataDictionary:(NSDictionary *) dictionary;
+ (NSMutableURLRequest *) urlEncodedPostRequestWithURL:(NSURL *)url andDataDictionary:(NSDictionary *) dictionary;

@end
