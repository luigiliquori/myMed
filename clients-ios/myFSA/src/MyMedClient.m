//
//  myMedClient.m
//  myEurope
//
//  Created by Emilio on 02/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import "myMedClient.h"

@interface MyMedClient()
{
    NSURL *_appurl;
}
-(NSString *) modifyRespose :(NSData *)data;

@property (nonatomic, retain) NSMutableData *receivedData;
@property (nonatomic, retain) NSURLConnection *theConnection;
@end

static MyMedClient *instance=nil;



@implementation MyMedClient
@synthesize receivedData=_receivedData, theConnection=_theConnection;

@synthesize appurl=_appurl;
@synthesize delegate=_delegate;
@synthesize htmlData=_htmlData;


+(MyMedClient *) GetInstance
{
    if (instance==nil) {
        instance = [[MyMedClient alloc] init];
    }
    return instance;
}

-(id) init
{
    self = [super init];
    if (self) {
        _appurl = [[NSURL URLWithString:WEBAPP_URL] retain];
        //[self performSelectorOnMainThread:@selector(loadUrl) withObject:nil waitUntilDone:NO];
    }
    return self;
}

-(void) dealloc
{
    [_appurl release];
    _appurl = nil;
    self.delegate = nil;

    if (self.theConnection!=nil) {
        [self.theConnection cancel];
        self.theConnection = nil;
    }
    self.receivedData = nil;
    
    [super dealloc];
}

-(NSString *)getHtml_pleaseWait
{
    return [NSString  stringWithFormat:@"<html><style type='text/css'>body {background-image:url('background.jpg');background-size:cover;} </style><body><h1 style=\"text-align:center;color=GhostWhite\">%@</h1><p>%@</p></body></html>",
            NSLocalizedString(@"myFSA", nil),
            NSLocalizedString(@"Please wait...", nil)];
}
-(NSString *)getHtml_noConnection;
{
    return [NSString  stringWithFormat:@"<html><style type='text/css'>body {background-image:url('background.jpg');background-size:cover;} </style><body><h1 style=\"text-align:center;color=GhostWhite\">%@</h1><p style=\"text-align:center\">%@</p></body></html>",
            NSLocalizedString(@"myFSA", nil),
            NSLocalizedString(@"Server down, or no connection available.", nil)];
}


-(NSString *)getHtml_empty;
{
    return [NSString  stringWithFormat:@"<html><style type='text/css'>body {background-image:url('background.jpg');background-size:cover;} </style><body><h1 style=\"text-align:center;color=GhostWhite\">%@</h1></body></html>",
            NSLocalizedString(@"myFSA", nil)];
}

-(void) loadUrl
{
    [UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
    NSMutableURLRequest *req=[NSMutableURLRequest requestWithURL:_appurl];
    [req setHTTPMethod:@"POST"];
    self.theConnection=[[[NSURLConnection alloc] initWithRequest:req delegate:self] autorelease];
    if (self.theConnection) {
        // Create the NSMutableData to hold the received data.
        // receivedData is an instance variable declared elsewhere.
        self.receivedData = [NSMutableData data];
        
    } else {
        UIAlertView *av=[[UIAlertView alloc] initWithTitle:NSLocalizedString(@"Error", nil)
                                                   message:NSLocalizedString(@"Internet connection not available", nil) delegate:nil
                                         cancelButtonTitle:NSLocalizedString(@"Ok", nil) otherButtonTitles: nil];
        [av show];
        [av release];
    }
    
    //NSLog(@"Loading %@", [u absoluteString]);
}


-(void) loadUrl: (NSString *) params
{
    [UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
    NSURL *url = [NSURL URLWithString:[NSString stringWithFormat:@"%@%@", WEBAPP_URL, params]];
    NSMutableURLRequest *req=[NSMutableURLRequest requestWithURL:url];
    [req setHTTPMethod:@"POST"];
    self.theConnection=[[[NSURLConnection alloc] initWithRequest:req delegate:self] autorelease];
    if (self.theConnection) {
        // Create the NSMutableData to hold the received data.
        // receivedData is an instance variable declared elsewhere.
        self.receivedData = [NSMutableData data];
    } else {
        UIAlertView *av=[[UIAlertView alloc] initWithTitle:NSLocalizedString(@"Error", nil)
                                                   message:NSLocalizedString(@"Internet connection not available", nil) delegate:nil
                                         cancelButtonTitle:NSLocalizedString(@"Ok", nil) otherButtonTitles: nil];
        [av show];
        [av release];
    }
    
    //NSLog(@"Loading %@", [u absoluteString]);
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
    if (self.delegate!=nil) {
        [self.delegate dataReady:self.html_noConnection];
    }
}



- (void)connectionDidFinishLoading:(NSURLConnection *)connection
{
    [UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
    // do something with the data
    // receivedData is declared as a method instance elsewhere
    //NSLog(@"Succeeded! Received %d bytes of data",[self.receivedData length]);
    
    // release the connection, and the data object
    self.theConnection = nil;
    self.htmlData = [self modifyRespose:self.receivedData];

    //NSLog(@"%@", self.htmlData);
    
    if (self.delegate!=nil) {
        [self.delegate dataReady: self.htmlData];
    }
    self.receivedData = nil;
}

#pragma mark -

-(NSString *) modifyRespose :(NSData *)data
{
    NSString *string = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
    NSMutableString *sout = [NSMutableString stringWithCapacity:data.length];
    unsigned length = [string length];
    unsigned paraStart = 0, paraEnd = 0, contentsEnd = 0;
    //NSMutableArray *array = [NSMutableArray array];
    NSRange currentRange;
    while (paraEnd < length)
    {
        [string getParagraphStart:&paraStart end:&paraEnd
                      contentsEnd:&contentsEnd forRange:NSMakeRange(paraEnd, 0)];
        currentRange = NSMakeRange(paraStart, contentsEnd - paraStart);
        //[array addObject:[string substringWithRange:currentRange]];
        NSString *s = [string substringWithRange:currentRange];
        if (s!=nil) {
            NSRange r = [s rangeOfString:@"data-role=\"header\""];
            if (r.length==0) {
                [sout appendString:s];
            }
        }
        
    }
    [string release];
    return sout;
}

-(void) Login :(NSString *)login pwd:(NSString *)password
{
    [UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
    NSURL *url = [NSURL URLWithString:[NSString stringWithFormat:@"%@/index.php?action=login",WEBAPP_URL]];
    NSMutableURLRequest *req=[NSMutableURLRequest requestWithURL:url];
    [req setHTTPMethod:@"POST"];
    [req setValue:@"application/x-www-form-urlencoded" forHTTPHeaderField:@"content-type"];
    

    NSString *postString = [NSString stringWithFormat:@"signin=1&login=%@&password=%@", login, password];
    NSData *data = [postString dataUsingEncoding: NSUTF8StringEncoding];
    [req setHTTPBody:data];
   //NSLog( @"%@ -> %@", postString, [[[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding] autorelease] );
    
    self.theConnection=[[[NSURLConnection alloc] initWithRequest:req delegate:self] autorelease];
    if (self.theConnection) {
        // Create the NSMutableData to hold the received data.
        // receivedData is an instance variable declared elsewhere.
        self.receivedData = [NSMutableData data];
    } else {
        UIAlertView *av=[[UIAlertView alloc] initWithTitle:NSLocalizedString(@"Error", nil)
                                                   message:NSLocalizedString(@"Internet connection not available", nil) delegate:nil
                                         cancelButtonTitle:NSLocalizedString(@"Ok", nil) otherButtonTitles: nil];
        [av show];
        [av release];
    }
    
}
@end
