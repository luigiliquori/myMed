//
//  NavigationModel.m
//  myConsolato
//
//  Created by Emilio on 14/01/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import "NavigationModel.h"
#import "Item.h"

static NavigationModel *instance = nil;

@interface NavigationModel()
{
    NSXMLParser *parser;
    Page *_mainPage;
    NSMutableArray *pagesStack;
}

@property (nonatomic, retain) NSMutableString *currentStringValue;
@end



@implementation NavigationModel
@synthesize mainPage=_mainPage, currentStringValue=_currentStringValue;

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
    self.currentStringValue = nil;
    if (parser) {
        [parser release];
        parser = nil;
    }

    [super dealloc];
}

- (void)parseXMLFile:(NSString *)pathToFile {
    [pagesStack removeAllObjects];
    [pagesStack addObject:_mainPage];
    
    NSURL *xmlURL = [NSURL fileURLWithPath:pathToFile];
    if (parser) {
        [parser release];
    }
    
    parser = [[NSXMLParser alloc] initWithContentsOfURL:xmlURL];
    [parser setDelegate:self];
    [parser setShouldResolveExternalEntities:YES];
    const BOOL success = [parser parse];
    NSAssert(success, @"Parser error: %@", parser.parserError.localizedDescription);
}


- (void)parser:(NSXMLParser *)parser didStartElement:(NSString *)elementName namespaceURI:(NSString *)namespaceURI qualifiedName:(NSString *)qName attributes:(NSDictionary *)attributeDict {
    Page *parentPage = [pagesStack lastObject];
    //NSLog(@"%@", elementName);
    self.currentStringValue = [NSMutableString string];
    
    if ( [elementName isEqualToString:@"page"]) {
        Page *pag = [[Page alloc] init];
        pag.title = [attributeDict objectForKey:@"title"];
        pag.subtitle = [attributeDict objectForKey:@"subtitle"];
        pag.url = [attributeDict objectForKey:@"url"];
        [parentPage.subPages addObject:pag];
        [pagesStack addObject:pag];
        
        //NSLog(@"%@ [%@]", pag.title, pag.subtitle);
        [pag release];
        return;
    } else if ( [elementName isEqualToString:@"item"] ) {
        Item *itm = [[Item alloc] init];
        itm.title = [attributeDict objectForKey:@"title"];
        itm.subtitle = [attributeDict objectForKey:@"subtitle"];
        itm.url = [attributeDict objectForKey:@"url"];
        itm.desc = [attributeDict objectForKey:@"desc"];
        itm.address = [NSString stringWithFormat:@"%@, %@ %@", [attributeDict objectForKey:@"address"], [attributeDict objectForKey:@"CAP"], [attributeDict objectForKey:@"ville"]];
        [parentPage.items addObject:itm];
        //NSLog(@"%@ [%@]", itm.title, itm.url);
        [itm release];
        return;
    }
    
}

- (void)parser:(NSXMLParser *)parser foundCharacters:(NSString *)string {
    [self.currentStringValue appendString:string];
    
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
        parentPage.text = self.currentStringValue;
        //NSLog(@"TEXT: %@", parentPage.text);
        return;
    }
    
}

@end
