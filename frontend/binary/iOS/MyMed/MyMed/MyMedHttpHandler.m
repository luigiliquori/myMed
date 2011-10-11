//
//  MyMedHttpHandler.m
//  MyMed
//
//  Created by Nicolas Goles on 10/6/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import "MyMedHttpHandler.h"
#import "SBJson.h"

@implementation MyMedHttpHandler

@synthesize delegate = delegate_;

- (id) init
{
    self = [super init];
    
    if (self) {
        jsonParser = [[SBJsonParser alloc] init];
    }
    
    return self;
}

- (void) submitLoginURL:(NSString *) loginURL
{
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:loginURL]
                                             cachePolicy:NSURLRequestUseProtocolCachePolicy 
                                         timeoutInterval:5.0];

    NSURLConnection *connection = [[NSURLConnection alloc] initWithRequest:request delegate:self];
    
    if (connection) {
        // Sucessful
    } else {
        // Error
    }
}

- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response
{
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data
{
    NSDictionary *dataDictionary = [jsonParser objectWithData:data];
    
    if ([self.delegate respondsToSelector:@selector(receivedHTTPData:)] && dataDictionary) {
        [self.delegate receivedHTTPData:dataDictionary];
    }
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error
{   
    if ([self.delegate respondsToSelector:@selector(connectionError:)]){
        [self.delegate connectionError:error];
    }
}

@end
