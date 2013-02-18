//
//  RestClient.m
//  myEurope
//
//  Created by Emilio on 24/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import "RestClient.h"
#import "conf.h"


@interface RestClient()
{
    BOOL waiting;
}
@property (nonatomic, retain) NSMutableData *receivedData;
@property (nonatomic, retain) NSURLConnection *theConnection;
@end


@implementation RestClient

@synthesize receivedData=_receivedData, theConnection=_theConnection;

- (id)init {
    self = [super init];
    if (self) {
    }
    return self;
}


-(void) dealloc
{
    self.receivedData=nil;
    self.theConnection=nil;
    self.accessToken=nil;
    [super dealloc];
}

- (NSString *)urlEncodeValue:(NSString *)str
{
    NSString *result = (NSString *) CFURLCreateStringByAddingPercentEscapes(kCFAllocatorDefault, (CFStringRef)str, NULL, CFSTR("?=&+"), kCFStringEncodingUTF8);
    return [result autorelease];
}

#pragma mark - Network methods

-(NSDictionary *) PostToHandler:(NSString *)hndlr withParameters :(NSDictionary *)dic
{
    
    NSURL *url = [NSURL URLWithString:[NSString stringWithFormat:@"%@/%@", MYMED_BACKEND_URL, hndlr]];
#if TARGET_OS_IPHONE
    [UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
#endif
    NSMutableURLRequest *req=[NSMutableURLRequest requestWithURL:url];
    [req setHTTPMethod:@"POST"];
    [req setValue:@"application/x-www-form-urlencoded" forHTTPHeaderField:@"content-type"];
    NSMutableString *body = [NSMutableString stringWithCapacity:128];
    if (dic!=nil) {
        NSString *sep = @"";
        NSString *k;
        NSEnumerator *kenum = dic.keyEnumerator;
        while ((k = [kenum nextObject])) {
            NSString *v = [dic objectForKey:k];
            [body appendFormat:@"%@%@=%@", sep, k, [self urlEncodeValue:v]];
            NSLog(@"    - '%@'='%@'", k, v);
            sep=@"&";
        }
    }
    
    NSData *bodyData = [body dataUsingEncoding: NSUTF8StringEncoding];
    [req setHTTPBody:bodyData];
    NSLog(@"Post to %@ with body: '%@'", [url absoluteString], body);
    //NSLog( @"%@ -> %@", postString, [[[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding] autorelease] );
    BOOL error = NO;
    @synchronized(self) {
        waiting = YES;
        self.theConnection=[[[NSURLConnection alloc] initWithRequest:req delegate:self] autorelease];
        if (self.theConnection) {
            self.receivedData = [NSMutableData data];
            const double t0 = [[NSDate date] timeIntervalSinceReferenceDate];
            double t1;
            do {
                [[NSRunLoop mainRunLoop] runUntilDate:[NSDate dateWithTimeIntervalSinceNow:0.15]];
                t1 = [[NSDate date] timeIntervalSinceReferenceDate];
            } while (waiting && (t1-t0)<2);
            
        } else {
            error = YES;
            NSLog(@"Error");
      }
    }
    
    if (error) {
#if TARGET_OS_IPHONE
        UIAlertView *av=[[UIAlertView alloc] initWithTitle:NSLocalizedString(@"Error", nil)
                                                   message:NSLocalizedString(@"Internet connection not available", nil) delegate:nil
                                         cancelButtonTitle:NSLocalizedString(@"Ok", nil) otherButtonTitles: nil];
        [av show];
        [av release];
#else
        CFUserNotificationDisplayNotice(0, kCFUserNotificationPlainAlertLevel,
                                        NULL, NULL, NULL, CFSTR("Error"),
                                        CFSTR("Internet connection not available"),
                                        CFSTR("OK"));
#endif
        NSLog(@"Internet connection not available");
    }
    
    NSError* errorobj=nil;
    NSDictionary* json = [NSJSONSerialization JSONObjectWithData:self.receivedData
                          options:kNilOptions
                          error:&errorobj];
    
    return json;
}


-(NSDictionary *) GetToHandler:(NSString *)hndlr withParameters :(NSDictionary *)dic
{
    NSMutableString *surl = [NSMutableString stringWithFormat:@"%@/%@", MYMED_BACKEND_URL, hndlr];
    if (dic!=nil) {
        NSString *sep = @"?";
        NSString *k;
        NSEnumerator *kenum = dic.keyEnumerator;
        while ((k = [kenum nextObject])) {
            NSString *v = [dic objectForKey:k];
            [surl appendFormat:@"%@%@=%@", sep,k,[self urlEncodeValue:v]];
            NSLog(@"    - '%@'='%@'", k, v);
            sep = @"&";
        }
    }
    
    NSURL *url = [NSURL URLWithString:surl];
    NSLog(@"Get to %@", [url absoluteString]);
#if TARGET_OS_IPHONE
    [UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
#endif
    NSMutableURLRequest *req=[NSMutableURLRequest requestWithURL:url];
    [req setHTTPMethod:@"GET"];
    [req setValue:@"application/x-www-form-urlencoded" forHTTPHeaderField:@"content-type"];

    
    //NSLog( @"%@ -> %@", postString, [[[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding] autorelease] );
    BOOL error = NO;
    @synchronized(self) {
        waiting = YES;
        self.theConnection=[[[NSURLConnection alloc] initWithRequest:req delegate:self] autorelease];
        if (self.theConnection) {
            self.receivedData = [NSMutableData data];
            const double t0 = [[NSDate date] timeIntervalSinceReferenceDate];
            double t1;
            do {
                [[NSRunLoop mainRunLoop] runUntilDate:[NSDate dateWithTimeIntervalSinceNow:0.15]];
                t1 = [[NSDate date] timeIntervalSinceReferenceDate];
            } while (waiting && (t1-t0)<2);
            
        } else {
            error = YES;
        }
    }
    
    if (error) {
#if TARGET_OS_IPHONE
        UIAlertView *av=[[UIAlertView alloc] initWithTitle:NSLocalizedString(@"Error", nil)
                                                   message:NSLocalizedString(@"Internet connection not available", nil) delegate:nil
                                         cancelButtonTitle:NSLocalizedString(@"Ok", nil) otherButtonTitles: nil];
        [av show];
        [av release];
#else
        NSLog(@"Internet connection not available");
#endif
    }
    
    NSError* errorobj=nil;
    NSDictionary* json = [NSJSONSerialization JSONObjectWithData:self.receivedData
                                                         options:kNilOptions
                                                           error:&errorobj];
    return json;
}
#pragma mark - Url connection delegate

- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response
{
    // This method is called when the server has determined that it
    // has enough information to create the NSURLResponse.
    
    // It can be called multiple times, for example in the case of a
    // redirect, so each time we reset the data.
    
    // receivedData is an instance variable declared elsewhere.
    [self.receivedData setLength:0];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data
{
    // Append the new data to receivedData.
    // receivedData is an instance variable declared elsewhere.
    [self.receivedData appendData:data];
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error
{
    // release the connection, and the data object
    self.theConnection = nil;
    // receivedData is declared as a method instance elsewhere
    self.receivedData = nil;
    
    // inform the user
    NSLog(@"Connection failed! Error - %@ %@",
          [error localizedDescription],
          [[error userInfo] objectForKey:NSURLErrorFailingURLStringErrorKey]);
#if TARGET_OS_IPHONE
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
#endif
    waiting = NO;
}



- (void)connectionDidFinishLoading:(NSURLConnection *)connection
{
#if TARGET_OS_IPHONE
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
#endif
    // do something with the data
    // receivedData is declared as a method instance elsewhere
    //NSLog(@"Succeeded! Received %d bytes of data",[self.receivedData length]);
    
    // release the connection, and the data object
    self.theConnection = nil;
    waiting = NO;
    
    //NSLog(@"%@", self.htmlData);
}




@end
