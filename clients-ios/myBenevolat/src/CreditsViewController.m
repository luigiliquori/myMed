//
//  CreditsViewController.m
//  myEurope
//
//  Created by Emilio on 20/12/12.
//  Copyright (c) 2012 myMed. All rights reserved.
//

#import "CreditsViewController.h"

@interface CreditsViewController ()

@end

NSString *URLS_CONSORTION[] = {
    @"http://www.inria.fr",
    @"http://unice.fr/",
    @"http://www.polito.it",
    @"http://www.unito.it",
    @"http://http://www.unipmn.it"};
NSString *URLS_FOUNDERS[] = {
    @"http://www.interreg-alcotra.org/",
    @"http://europa.eu",
    @"http://www.cg06.fr/",
    @"http://www.regionpaca.fr/",
    @"http://www.inria.fr",
    @"http://www.regione.piemonte.it/",
    @"http://www.mymed.fr"};

@implementation CreditsViewController

@synthesize cellsCons=_cellsCons, cellFund=_cellFund;
/*
- (id)initWithStyle:(UITableViewStyle)style
{
    self = [super initWithStyle:style];
    if (self) {
        // Custom initialization
    }
    return self;
}
*/
- (void)viewDidLoad
{
    [super viewDidLoad];

    // Uncomment the following line to preserve selection between presentations.
    // self.clearsSelectionOnViewWillAppear = NO;
 
    // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
    // self.navigationItem.rightBarButtonItem = self.editButtonItem;
    self.title = NSLocalizedString(@"Credits", nil);
}

- (void)viewDidUnload
{
    self.cellsCons = nil;
    self.cellFund = nil;
    [super viewDidLoad];
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
    return 3;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    // Return the number of rows in the section.
    if (section==0) {
        return [self.cellsCons count];
    } else if (section==1) {
            return [self.cellFund count];
    }
    return 1;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.section==0) {
        return [self.cellsCons objectAtIndex:indexPath.row];
    } else if (indexPath.section==1) {
        return [self.cellFund objectAtIndex:indexPath.row];
    }
    
    static NSString *CellIdentifier = @"Cell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:CellIdentifier];
    }
    
    // Configure the cell...
    cell.textLabel.text = [NSString stringWithFormat:@"myMed - myFSA 1.0"];
    cell.textLabel.textColor = [UIColor grayColor];
    cell.selectionStyle = UITableViewCellSelectionStyleNone;
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
    if (indexPath.section==2) {
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"http://www.mymed.fr"]];
        return;
    }
    
    if (indexPath.section==0) {
        //UITableViewCell *c = [self.cellsFund objectAtIndex:indexPath.row];
        int t = indexPath.row; //c.tag;
        if (t>=0 && t<5) {
            [[UIApplication sharedApplication] openURL:[NSURL URLWithString:URLS_CONSORTION[t]]];
        }
        return;
    }
    if (indexPath.section==1) {
        int t = indexPath.row; //c.tag;
        if (t>=0 && t<7) {
            [[UIApplication sharedApplication] openURL:[NSURL URLWithString:URLS_FOUNDERS[t]]];
        }
        return;
    }}

-(float) tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (UI_USER_INTERFACE_IDIOM()==UIUserInterfaceIdiomPhone)
        return 55;
    else
        return 60;
}
-(NSString *) tableView:(UITableView *)tableView titleForHeaderInSection:(NSInteger)section
{
    switch (section) {
        case 0:
            return NSLocalizedString(@"Consortium myMed:", nil);
            break;
            
        case 1:
            return NSLocalizedString(@"Funded by:", nil);
            break;

        default:
            return @"";
            break;
    }
}

-(IBAction)action_done:(id)sender
{
    [self dismissViewControllerAnimated:YES completion:nil];
}

@end
