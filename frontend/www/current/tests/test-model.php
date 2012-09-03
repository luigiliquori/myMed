<?

# Test of model functionnalities 

// ---------------------------------------------------------------------
// Constants
// ---------------------------------------------------------------------
define('APPLICATION_NAME', "MyUnitTests");
define('APP_ROOT', __DIR__);
define('MYMED_ROOT', __DIR__ . '/..');

// Account should have been created previously;
$LOGIN="raphael.jolivet+test@gmail.com";
$PASS="password";
$USERID="";

// ---------------------------------------------------------------------
// Init
// ---------------------------------------------------------------------
include(MYMED_ROOT . '/system/common/init.php');
include("classes/test-utils.php");
add_path(__DIR__ . "/classes");

// ---------------------------------------------------------------------
// Setup Assertions
// ---------------------------------------------------------------------

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
function my_assert_handler($file, $line, $code)
{
	echo("Assert failed. line $line: \"$code\"\n");
	exit;
}

// Configuration de la méthode de callback
assert_options(ASSERT_CALLBACK, 'my_assert_handler');

// --------------------------------------------------------------------
// Plain requests Test
// --------------------------------------------------------------------



// ---------------------------------------------------------------------
// Test GenericDataBean
// ---------------------------------------------------------------------

// Login
$request = new Request("AuthenticationRequestHandler", READ);
$request->addArgument("login", $LOGIN);
$request->addArgument("password", hash("sha512", $PASS));
$res = json_decode($request->send());

// Set the token
$_SESSION['accessToken'] = $res->dataObject->accessToken;
$_SESSION['user'] = json_decode($res->data->user);

// Create model object
$obj = new SampleModel(); 
$obj->begin="begin";
$obj->end="end";
$obj->pred1=array("cat1", "cat2", "cat3");
$obj->pred2="TATA";
$obj->data1="TOTO";
$obj->wrapped1 = "Wrapped";

// Save it
$obj->publish();

// Get it back
$obj->publisherID = $_SESSION['user']->id;
$results = $obj->find();
assert('sizeof($results) == 1');
$res = $results[0];

// At this point, the predicates should be set
assertEqual($res->pred1, $obj->pred1);
assertEqual($res->pred2, $obj->pred2);
assertEqual($res->pred3, $obj->pred3);

// Get the details
$res->getDetails();

// Compare input and output
assertObjectsEqual($res, $obj);


?>