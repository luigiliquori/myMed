//
//  SHA1Encoder.m
//  MyMed
//
//  Created by Nicolas Goles on 10/6/11.
//  Copyright (c) 2011 LOGNET. All rights reserved.
//

#import "SHA1Encoder.h"
#import <CommonCrypto/CommonDigest.h>
#import <CommonCrypto/CommonHMAC.h>

@implementation SHA1Encoder

+ (NSString *) sha512FromString:(NSString *) source
{
    const char *s = [source cStringUsingEncoding:NSASCIIStringEncoding];
    NSData *keyData = [NSData dataWithBytes:s length:strlen(s)];
    uint8_t digest[CC_SHA512_DIGEST_LENGTH] = {0};

    CC_SHA512(keyData.bytes, keyData.length, digest);

    NSData *out = [NSData dataWithBytes:digest length:CC_SHA512_DIGEST_LENGTH];
    NSString *hash = [out description];
    hash = [hash stringByReplacingOccurrencesOfString:@" " withString:@""];
    hash = [hash stringByReplacingOccurrencesOfString:@"<" withString:@""];
    hash = [hash stringByReplacingOccurrencesOfString:@">" withString:@""];
    return hash;
}

@end
