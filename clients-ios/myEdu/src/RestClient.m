//
//  RestClient.m
//  myEurope
//
//  Created by Emilio on 24/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import "RestClient.h"

@interface RestClient()
{
    BOOL waiting;
}
@property (nonatomic, retain) NSMutableData *receivedData;
@property (nonatomic, retain) NSURLConnection *theConnection;


@end


@implementation RestClient
@synthesize url=_url;
@synthesize receivedData=_receivedData, theConnection=_theConnection;

-(void) dealloc
{
    self.url = nil;
    [super dealloc];
}

-(NSData *) Post :(NSString *)body
{
    [UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
    NSMutableURLRequest *req=[NSMutableURLRequest requestWithURL:self.url];
    [req setHTTPMethod:@"POST"];
    [req setValue:@"application/x-www-form-urlencoded" forHTTPHeaderField:@"content-type"];
    
    
    NSData *bodyData = [body dataUsingEncoding: NSUTF8StringEncoding];
    [req setHTTPBody:bodyData];
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
        UIAlertView *av=[[UIAlertView alloc] initWithTitle:NSLocalizedString(@"Error", nil)
                                                   message:NSLocalizedString(@"Internet connection not available", nil) delegate:nil
                                         cancelButtonTitle:NSLocalizedString(@"Ok", nil) otherButtonTitles: nil];
        [av show];
        [av release];
    }
    return self.receivedData;
}

-(NSData *) Get:(NSString *)body
{
    [UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
    NSMutableURLRequest *req=[NSMutableURLRequest requestWithURL:self.url];
    [req setHTTPMethod:@"GET"];
    [req setValue:@"application/x-www-form-urlencoded" forHTTPHeaderField:@"content-type"];
    
    
    NSData *bodyData = [body dataUsingEncoding: NSUTF8StringEncoding];
    [req setHTTPBody:bodyData];
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
        UIAlertView *av=[[UIAlertView alloc] initWithTitle:NSLocalizedString(@"Error", nil)
                                                   message:NSLocalizedString(@"Internet connection not available", nil) delegate:nil
                                         cancelButtonTitle:NSLocalizedString(@"Ok", nil) otherButtonTitles: nil];
        [av show];
        [av release];
    }
    return self.receivedData;
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
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
    waiting = NO;
}



- (void)connectionDidFinishLoading:(NSURLConnection *)connection
{
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
    // do something with the data
    // receivedData is declared as a method instance elsewhere
    //NSLog(@"Succeeded! Received %d bytes of data",[self.receivedData length]);
    
    // release the connection, and the data object
    self.theConnection = nil;
    waiting = NO;
    
    //NSLog(@"%@", self.htmlData);
}

@end
