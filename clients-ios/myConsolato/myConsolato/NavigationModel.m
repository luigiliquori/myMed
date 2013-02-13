//
//  NavigationModel.m
//  myConsolato
//
//  Created by Emilio on 14/01/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import "NavigationModel.h"

static NavigationModel *instance = nil;

@interface NavigationModel()
{
    NSXMLParser *parser;
    Page *_mainPage;
    NSMutableString *currentStringValue;
    NSMutableArray *pagesStack;
}

@end



@implementation NavigationModel
@synthesize mainPage=_mainPage;
+(NavigationModel *) getInstance
{
    if (instance==nil) {
        instance = [[NavigationModel alloc] init];
    }
    return instance;
}

- (id)init
{
    self = [super init];
    if (self) {
        parser = nil;
        _mainPage = [[Page alloc] init];
        pagesStack = [[NSMutableArray alloc] init];
        currentStringValue = [[NSMutableString alloc] initWithCapacity:50];

        NSString *path = [[NSBundle mainBundle] pathForResource:@"data" ofType:@"xml"];
        [self parseXMLFile:path];

    }
    return self;
}

- (void)dealloc
{
    [pagesStack release];
    pagesStack = nil;
    [_mainPage release];
    _mainPage = nil;
    [currentStringValue release];
    currentStringValue = nil;
    if (parser) {
        [parser release];
        parser = nil;
    }

    [super dealloc];
}

- (void)parseXMLFile:(NSString *)pathToFile {
    //BOOL success;
    [pagesStack removeAllObjects];
    [pagesStack addObject:_mainPage];
    
    NSURL *xmlURL = [NSURL fileURLWithPath:pathToFile];
    if (parser) {
        [parser release];
    }
    
    parser = [[NSXMLParser alloc] initWithContentsOfURL:xmlURL];
    [parser setDelegate:self];
    [parser setShouldResolveExternalEntities:YES];
    [parser parse];
    //success = [parser parse]; // return value not used
}


- (void)parser:(NSXMLParser *)parser didStartElement:(NSString *)elementName namespaceURI:(NSString *)namespaceURI qualifiedName:(NSString *)qName attributes:(NSDictionary *)attributeDict {
    Page *parentPage = [pagesStack lastObject];
    NSLog(@"%@", elementName);
    [currentStringValue setString:@""];
    
    if ( [elementName isEqualToString:@"page"]) {
        Page *pag = [[Page alloc] init];
        pag.title = [attributeDict objectForKey:@"title"];
        pag.subtitle = [attributeDict objectForKey:@"subtitle"];
        [parentPage.subPages addObject:pag];
        [pagesStack addObject:pag];
        
        NSMutableString *s = [[NSMutableString alloc] init];
        for (int i=0; i<[pagesStack count]; i++) {
            [s appendString:@"."];
        }
        NSLog(@"%@%@ [%@]", s, pag.title, pag.subtitle);
        [s release];
        [pag release];
        return;
    }
    
    if ( [elementName isEqualToString:@"person"] ) {
        return;
    }
    
}

- (void)parser:(NSXMLParser *)parser foundCharacters:(NSString *)string {
    [currentStringValue appendString:string];
    
}

- (void)parser:(NSXMLParser *)parser didEndElement:(NSString *)elementName namespaceURI:(NSString *)namespaceURI qualifiedName:(NSString *)qName {
    
    // ignore root and empty elements
    
    if (( [elementName isEqualToString:@"data"]) || ( [elementName isEqualToString:@"address"] )) {
        return;
    }
    
    if ( [elementName isEqualToString:@"page"] ) {
        [pagesStack removeLastObject];
        return;
    }
    if ( [elementName isEqualToString:@"text"] ) {
        Page *parentPage = [pagesStack lastObject];
        parentPage.text = currentStringValue;
        return;
    }
    
}

@end
