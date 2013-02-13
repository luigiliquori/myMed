//
//  ChecklistViewController.m
//  myEurope
//
//  Created by Emilio on 20/01/13.
//  Copyright (c) 2013 myMed. All rights reserved.
//

#import "ChecklistViewController.h"

@interface ListItem: NSObject<NSCoding>
@property (atomic, retain) NSString *name;
@property (atomic) BOOL checked;

@end

@implementation ListItem
@synthesize name, checked;

- (id)initWithCoder:(NSCoder *)coder {
    self = [super init];
    if (self) {
        self.name = [coder decodeObjectForKey:@"kname"];
        self.checked = [coder decodeBoolForKey:@"kchkd"];
    }
    return self;
}
-(void) dealloc
{
    self.name = nil;
    [super dealloc];
}
- (void)encodeWithCoder:(NSCoder *)coder {
    [coder encodeObject:self.name forKey:@"kname"];
    [coder encodeBool:checked forKey:@"kchkd"];
}

@end


@interface ChecklistViewController ()
{
    NSMutableArray *list;
}

-(void) save;
-(void) load;
-(IBAction)action_add:(id)sender;
@end

@implementation ChecklistViewController

- (id)initWithStyle:(UITableViewStyle)style
{
    self = [super initWithStyle:style];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];

    // Uncomment the following line to preserve selection between presentations.
    // self.clearsSelectionOnViewWillAppear = NO;
 
    // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
    //self.navigationItem.rightBarButtonItem = self.editButtonItem;
    self.navigationItem.leftBarButtonItem = self.editButtonItem;
    self.navigationItem.rightBarButtonItem = [[[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemAdd target:self action:@selector(action_add:)] autorelease];
    
    list = [[NSMutableArray alloc]init];
    
}

-(void) viewWillAppear:(BOOL)animated
{
    [self load];
    [super viewWillAppear:animated];
}

-(void) viewWillDisappear:(BOOL)animated
{
    [self save];
    [super viewWillDisappear:animated];
}

- (void)viewDidUnload
{
    [list release];
    list = nil;
    [super viewDidUnload];
}
- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - Table view data source

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    // Return the number of sections.
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return [list count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *CellIdentifier = @"Cell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:CellIdentifier] autorelease];
        cell.selectionStyle = UITableViewCellSelectionStyleNone;
    }
    
    ListItem *itm = [list objectAtIndex:indexPath.row];
    cell.textLabel.text = itm.name;
    if (itm.checked) {
        cell.accessoryType = UITableViewCellAccessoryCheckmark;
    } else {
        cell.accessoryType = UITableViewCellAccessoryNone;
    }
    
    return cell;
}

/*
// Override to support conditional editing of the table view.
- (BOOL)tableView:(UITableView *)tableView canEditRowAtIndexPath:(NSIndexPath *)indexPath
{
    // Return NO if you do not want the specified item to be editable.
    return YES;
}
*/

/*
// Override to support editing the table view.
- (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (editingStyle == UITableViewCellEditingStyleDelete) {
        // Delete the row from the data source
        [tableView deleteRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationFade];
    }   
    else if (editingStyle == UITableViewCellEditingStyleInsert) {
        // Create a new instance of the appropriate class, insert it into the array, and add a new row to the table view
    }   
}
*/

/*
// Override to support rearranging the table view.
- (void)tableView:(UITableView *)tableView moveRowAtIndexPath:(NSIndexPath *)fromIndexPath toIndexPath:(NSIndexPath *)toIndexPath
{
}
*/

/*
// Override to support conditional rearranging of the table view.
- (BOOL)tableView:(UITableView *)tableView canMoveRowAtIndexPath:(NSIndexPath *)indexPath
{
    // Return NO if you do not want the item to be re-orderable.
    return YES;
}
*/

#pragma mark - Table view delegate

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    ListItem *itm = [list objectAtIndex:indexPath.row];
    itm.checked = !itm.checked;
    [self.tableView reloadRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:UITableViewRowAnimationFade];
}

-(IBAction)action_add:(id)sender
{
    UIAlertView *av = [[UIAlertView alloc] initWithTitle:NSLocalizedString(@"New item", nil)
                                                 message:NSLocalizedString(@"Please write the new checklist item", nil)
                                                delegate:self
                                       cancelButtonTitle:NSLocalizedString(@"Cancel", nil)
                                       otherButtonTitles:NSLocalizedString(@"Ok", nil), nil];
    av.alertViewStyle = UIAlertViewStylePlainTextInput;
    [av show];
    [av release];
}

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (buttonIndex==alertView.cancelButtonIndex) {
        return;
    }
    
    ListItem *itm = [[[ListItem alloc] init] autorelease];
    itm.name = [alertView textFieldAtIndex:0].text;
    [list addObject:itm];
    [self.tableView reloadData];
}

- (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath
{
    [list removeObjectAtIndex:indexPath.row];
    [self.tableView deleteRowsAtIndexPaths:[NSArray arrayWithObject:indexPath] withRowAnimation:UITableViewRowAnimationRight];
}


// ---------------------------------------------------

-(void) save
{
    NSMutableData *data = [NSMutableData dataWithCapacity:(([list count]+1)*64)];
    NSKeyedArchiver *archiver = [[NSKeyedArchiver alloc] initForWritingWithMutableData:data];
    int d = [list count];
    [archiver encodeInt:d forKey:@"lcnt"];
    for (int i=0; i<[list count]; i++) {
        ListItem *itm = [list objectAtIndex:i];
        [archiver encodeObject:itm];
    }
    [archiver finishEncoding];
    
    NSArray *searchPaths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES);
    NSString *archivePath = [NSString stringWithFormat:@"%@/checklist.dat", [searchPaths objectAtIndex:0]];
    [data writeToFile:archivePath atomically:YES];
    //NSLog(@"Saving in %@: %d", archivePath, result);
    [archiver release];
}

-(void) loadFirstInfo
{
    ListItem *itm;
    
    
    itm = [[[ListItem alloc] init] autorelease];
    itm.name = NSLocalizedString(@"Check www.interreg-alcotra.org", nil);
    [list addObject:itm];
    
    itm = [[[ListItem alloc] init] autorelease];
    itm.name = NSLocalizedString(@"Prepare the transborder cooperation agreement", nil);
    [list addObject:itm];

    itm = [[[ListItem alloc] init] autorelease];
    itm.name = NSLocalizedString(@"Fill the project form", nil);
    [list addObject:itm];
    
    itm = [[[ListItem alloc] init] autorelease];
    itm.name = NSLocalizedString(@"Write the technical description", nil);
    [list addObject:itm];
    
    itm = [[[ListItem alloc] init] autorelease];
    itm.name = NSLocalizedString(@"Prepare the technical and administrative description", nil);
    [list addObject:itm];
    
    itm = [[[ListItem alloc] init] autorelease];
    itm.name = NSLocalizedString(@"Two copies in Italian", nil);
    [list addObject:itm];
    
    itm = [[[ListItem alloc] init] autorelease];
    itm.name = NSLocalizedString(@"Two copies in French", nil);
    [list addObject:itm];
    
    itm = [[[ListItem alloc] init] autorelease];
    itm.name = NSLocalizedString(@"Digital copy", nil);
    [list addObject:itm];

    [self.tableView reloadData];
   
}

-(void) load
{
    [list removeAllObjects];
    NSArray *searchPaths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES);
    NSString *archivePath = [NSString stringWithFormat:@"%@/checklist.dat", [searchPaths objectAtIndex:0]];
    NSData *data = [NSData dataWithContentsOfFile:archivePath];
    if (data==nil) {
        [self loadFirstInfo];
        return;
    }
    
    NSKeyedUnarchiver *unarchiver = [[NSKeyedUnarchiver alloc] initForReadingWithData:data];
    const int d = [unarchiver decodeIntForKey:@"lcnt"];
    for (int i=0; i<d; i++) {
        ListItem *li = [unarchiver decodeObject];
        [list addObject:li];
        //NSLog(@"%@: %d", li.name, li.checked);
    }
    [unarchiver finishDecoding];
    [unarchiver release];
    [self.tableView reloadData];
}
@end
