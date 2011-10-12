//
//  POSTRequestBuilder.m
//  MyMed
//
//  Created by Nicolas Goles on 10/12/11.
//  Copyright (c) 2011 GandoGames. All rights reserved.
//

#import "POSTRequestBuilder.h"

@implementation POSTRequestBuilder

/** Creates a multipart HTTP POST request without aid of external libraries.
 *  @param url is the target URL for the POST request
 *  @param dictionary is a key/value dictionary with the DATA of the multipart post.
 *  
 *  Should be constructed like:
 *      NSArray *keys = [[NSArray alloc] initWithObjects:@"login", @"password", nil];
 *      NSArray *objects = [[NSArray alloc] initWithObjects:@"TheLoginName", @"ThePassword!", nil];    
 *      NSDictionary *dictionary = [[NSDictionary alloc] initWithObjects:objects forKeys:keys];
 */
+ (NSMutableURLRequest *) multipartPostRequestWithURL:(NSURL *)url andDataDictionary:(NSDictionary *) dictionary
{
    // Create a POST request
    NSMutableURLRequest *myMedRequest = [NSMutableURLRequest requestWithURL:url];
    [myMedRequest setHTTPMethod:@"POST"];
    
    // Add HTTP header info
    // Note: POST boundaries are described here: http://www.vivtek.com/rfc1867.html
    // and here http://www.w3.org/TR/html4/interact/forms.html
    NSString *POSTBoundary = [NSString stringWithString:@"0xKhTmLbOuNdArY"];
    [myMedRequest addValue:[NSString stringWithFormat:@"multipart/form-data; boundary=%@", POSTBoundary] forHTTPHeaderField:@"Content-Type"];
    
    // Add HTTP Body
    NSMutableData *POSTBody = [NSMutableData data];
    [POSTBody appendData:[[NSString stringWithFormat:@"--%@\r\n",POSTBoundary] dataUsingEncoding:NSUTF8StringEncoding]];
    
    // Add Key/Values to the Body
    NSEnumerator *enumerator = [dictionary keyEnumerator];
    NSString *key;
    
    while ((key = [enumerator nextObject])) {
        [POSTBody appendData:[[NSString stringWithFormat:@"Content-Disposition: form-data; name=\"%@\"\r\n\r\n", key] dataUsingEncoding:NSUTF8StringEncoding]];
        [POSTBody appendData:[[NSString stringWithFormat:@"%@", [dictionary objectForKey:key]] dataUsingEncoding:NSUTF8StringEncoding]];
        [POSTBody appendData:[[NSString stringWithFormat:@"\r\n--%@\r\n", POSTBoundary] dataUsingEncoding:NSUTF8StringEncoding]];
    }
    
    // Add the closing -- to the POST Form
    [POSTBody appendData:[[NSString stringWithFormat:@"\r\n--%@--\r\n", POSTBoundary] dataUsingEncoding:NSUTF8StringEncoding]]; 
    
    // Add the body to the myMedRequest & return
    [myMedRequest setHTTPBody:POSTBody];
    return myMedRequest;
}

@end
