//
//  PagesViewController.m
//  myConsolato
//
//  Created by Emilio on 15/01/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import "PagesViewController.h"

@interface PagesViewController ()

@end

@implementation PagesViewController

@synthesize page=_page;


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
    // self.navigationItem.rightBarButtonItem = self.editButtonItem;
    NSAssert(self.page!=nil, @"self.page is nil");
    self.title  = self.page.title;
}

-(void)viewDidUnload
{
    self.page = nil;
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
    NSAssert(self.page!=nil, @"self.page is nil");
    int ns = [self.page.subPages count];
    if (self.page.text!=nil) {
        ns++;
    }
    return ns;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    // Return the number of rows in the section.
    return 1;
}

- (UITableViewCell *) textCellForTableView:(UITableView *)tableView{
    static NSString *CellIdentifier = @"CellTxt";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:CellIdentifier] autorelease];
        cell.textLabel.font = [UIFont systemFontOfSize:12];
        cell.textLabel.numberOfLines = 5;
    }
    cell.textLabel.text = self.page.text;
    return cell;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.section == [self.page.subPages count]) {
        return [self textCellForTableView:tableView];
    }
    static NSString *CellIdentifier = @"Cell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle  reuseIdentifier:CellIdentifier] autorelease];
        cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
        //cell.textLabel.textAlignment = NSTextAlignmentCenter;
        cell.textLabel.backgroundColor = [UIColor clearColor];
        
        cell.detailTextLabel.backgroundColor = [UIColor clearColor];
        
        cell.backgroundView = [[[UIImageView alloc] init] autorelease];
        cell.selectedBackgroundView = [[[UIImageView alloc] init] autorelease];
    }
    
    // Configure the cell...
    UIImage *i = [UIImage imageNamed:@"topandbottomrow.png"];
    ((UIImageView *)cell.backgroundView).image = i;
    ((UIImageView *)cell.selectedBackgroundView).image = [UIImage imageNamed:@"topAndBottomRowSelected.png"];
    
    // Configure the cell...
    Page *p = [self.page.subPages objectAtIndex:indexPath.section];
    cell.textLabel.text = p.title;
    if (p.subtitle!=nil) {
        cell.detailTextLabel.text = p.subtitle;
    } else {
        cell.detailTextLabel.text = @"";
    }
    NSLog(@"**%@ [%@]", p.title, p.subtitle);

    
    //if ([p.subPages count]>0 || p.text!=nil) {
    //    cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
    //} else  {
    //    cell.accessoryType = UITableViewCellAccessoryNone;
    //}
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

-(float) tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
        if (indexPath.section>=[self.page.subPages count]) {
            return 120;
        }
    return 44;

}

#pragma mark - Table view delegate

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.section>=[self.page.subPages count]) {
        return;
    }
    
    Page *p = [self.page.subPages objectAtIndex:indexPath.section];
    PagesViewController *vc = [[PagesViewController alloc] initWithNibName:@"PagesViewController" bundle:nil];
    vc.page = p;
    [self.navigationController pushViewController:vc animated:YES];
    [vc release];
}

@end
