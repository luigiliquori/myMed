//
//  PagesViewController.m
//  myConsolato
//
//  Created by Emilio on 15/01/13.
//  Copyright (c) 2013 Escogitare. All rights reserved.
//

#import "PagesViewController.h"

@interface PagesViewController ()
-(IBAction)action_options:(id)sender;
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
    if (self.page.url!=nil) {
        self.navigationItem.rightBarButtonItem = [[[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemAction target:self action:@selector(action_options:)] autorelease];
    }

    NSAssert(self.page!=nil, @"self.page is nil");
    self.navigationItem.title = self.page.title;
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
    //if (self.page.text!=nil) {
    //    ns++;
    //}
    return ns+2;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    // Return the number of rows in the section.
    int count = 0;
    if (section<[self.page.subPages count]) {
        count = 1;
        
    } else if (section==[self.page.subPages count]) {
        count = [self.page.items count];
        
    } else  {
       count = (self.page.text==nil) ? 0 : 1;
    }
    return count;
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


- (UITableViewCell *) itemCellForTableView:(UITableView *)tableView :(int) row{
    static NSString *CellIdentifier = @"CellItem";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle  reuseIdentifier:CellIdentifier] autorelease];
        cell.accessoryType = UITableViewCellAccessoryNone;
        //cell.textLabel.textAlignment = NSTextAlignmentCenter;
        cell.textLabel.backgroundColor = [UIColor clearColor];
        
        cell.detailTextLabel.backgroundColor = [UIColor clearColor];
        
        cell.backgroundView = [[[UIImageView alloc] init] autorelease];
        cell.selectedBackgroundView = [[[UIImageView alloc] init] autorelease];
    }
    
    // Configure the cell...
    UIImage *rowBackground;
    UIImage *selectionBackground;
    const NSInteger sectionRows = [self.page.items count];
    if (row == 0 && row == sectionRows - 1)
    {
        rowBackground = [UIImage imageNamed:@"topandbottomrow"];
        selectionBackground = [UIImage imageNamed:@"topAndBottomRowSelected"];
    }
    else if (row == 0)
    {
        rowBackground = [UIImage imageNamed:@"toprow.png"];
        selectionBackground = [UIImage imageNamed:@"toprowselected.png"];
    }
    else if (row == sectionRows - 1)
    {
        rowBackground = [UIImage imageNamed:@"bottomrow.png"];
        selectionBackground = [UIImage imageNamed:@"bottomrowselected.png"];
    }
    else
    {
        rowBackground = [UIImage imageNamed:@"row.png"];
        selectionBackground = [UIImage imageNamed:@"rowselected.png"];
    }
    ((UIImageView *)cell.backgroundView).image = rowBackground;
    ((UIImageView *)cell.selectedBackgroundView).image = selectionBackground;
    
    // Configure the cell...
    
    Item *i = [self.page.items objectAtIndex:row];
    cell.textLabel.text = i.title;
    cell.detailTextLabel.text = i.url;
    if (i.url!=nil && [i.url length]>0) {
        cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
    }

    return cell;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.section == [self.page.subPages count]+1) {
        return [self textCellForTableView:tableView];
    }
    if (indexPath.section == [self.page.subPages count]) {
        return [self itemCellForTableView:tableView :indexPath.row];
    }

    Page *p = [self.page.subPages objectAtIndex:indexPath.section];
    NSString *CellIdentifier;
    if (p.subtitle!=nil) {
        CellIdentifier = @"CellA";
    } else {
        CellIdentifier = @"CellB";
    }

    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        if (p.subtitle!=nil) {
            cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleSubtitle  reuseIdentifier:CellIdentifier] autorelease];
        } else {
            cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault  reuseIdentifier:CellIdentifier] autorelease];
        }
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
    cell.textLabel.text = p.title;
    if (p.subtitle!=nil) {
        cell.detailTextLabel.text = p.subtitle;
    } else {
        cell.detailTextLabel.text = @"";
    }
    //NSLog(@"**%@ [%@]", p.title, p.subtitle);

    
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
        if (indexPath.section>=([self.page.subPages count]+1)) {
            return 120;
        }
    return 44;

}

#pragma mark - Table view delegate

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    /*
    if (indexPath.section>=[self.page.subPages count]) {
        return;
    }
    
    
    Page *p = [self.page.subPages objectAtIndex:indexPath.section];
    PagesViewController *vc = [[PagesViewController alloc] initWithNibName:@"PagesViewController" bundle:nil];
    vc.page = p;
    [self.navigationController pushViewController:vc animated:YES];
    [vc release];
     */
    Item *itm = [self.page.items objectAtIndex:indexPath.row];
    if (itm.url!=nil) {
        UIActionSheet *as = [[UIActionSheet alloc] initWithTitle:itm.title delegate:self cancelButtonTitle:NSLocalizedString(@"Cancel", nil) destructiveButtonTitle:nil otherButtonTitles:NSLocalizedString(@"Open web site", nil), nil];
        [as showFromTabBar:self.tabBarController.tabBar];
        as.tag = indexPath.row;
        [as release];
    }
}


-(IBAction)action_options:(id)sender
{
    UIActionSheet *as = [[UIActionSheet alloc] initWithTitle:self.page.title
                                                    delegate:self
                                           cancelButtonTitle:NSLocalizedString(@"Cancel", nil)
                                      destructiveButtonTitle:nil
                                           otherButtonTitles:NSLocalizedString(@"Open web site", nil), nil];
    [as showFromBarButtonItem:sender animated:YES];
    [as release];
}

- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (buttonIndex==actionSheet.cancelButtonIndex) {
        return;
    }
    if (buttonIndex==actionSheet.firstOtherButtonIndex) {
        Item *itm = [self.page.items objectAtIndex:actionSheet.tag];
        if (itm.url!=nil) {
            NSString *surl = [NSString stringWithFormat:@"http://%@", itm.url];
            [[UIApplication sharedApplication] openURL:[NSURL URLWithString:surl]];
        }

    }
}
@end
