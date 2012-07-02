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
// Login
// ---------------------------------------------------------------------
$request = new Request("AuthenticationRequestHandler", READ);
$request->addArgument("login", $LOGIN);
$request->addArgument("password", hash("sha512", $PASS));
$res = json_decode($request->send());

// Set the token
$_SESSION['accessToken'] = $res->dataObject->accessToken;
$_SESSION['user'] = json_decode($res->data->user);

// ---------------------------------------------------------------------
// Set/Get back object
// ---------------------------------------------------------------------

// Create model object
$obj = new SampleModel(); 
$obj->begin="begin";
$obj->end="end";
$obj->pred1="TOTO";
$obj->pred2="TATA";
$obj->data1="TOTO";
$obj->wrapped1 = "Wrapped";

// Save it
$obj->publish();

// Get it back
$obj->publisherID = $_SESSION['user']->id;
$results = $obj->find();
assert(sizeof($results) == 1);
$result = $results[0];

// At this point, the predicate should be equal
assert($result->pred1 == $obj->pred1);
assert($result->pred2 == $obj->pred2);
assert($result->pred3 == $obj->pred3);

// Get the details
$result->getDetails();

// Compare input and output
assertObjectsEqual($result, $obj);




?>