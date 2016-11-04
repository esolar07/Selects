<?

require __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('America/New_York');


//use Monolog\Logger;
//use Monolog\Handler\StreamHandler;
//$log = new Logger('name');
//$log->pushHandler(new StreamHandler('app.log', Logger::WARNING));
//$log->addWarning('Foo');


// instantiating slim class
$app = new \Slim\Slim(array(
	// setting view class to twig
	'view' => new \Slim\Views\Twig()
));

$view = $app->view();

$view->parserOptions = array(
    'debug' => true
);

$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);

// routing
$app->get("/", function() use($app){
	$app->render("home.twig");
})->name('Home');

$app->get("/examples", function() use($app){
	$app->render("examples.twig");
})->name('Examples');
/*
$app->get("/contact", function() use($app){
	$app->render( "contact.twig");
})->name('Contact');
*/

// contact form mailer 
$app->post("/contact", function() use($app){
	// var_dump($app->request->post());
	$name = $app->request->post('name');
	$email = $app->request->post('email');
	$msg = $app->request->post('message');
	
	// checks to see input is not empty
	if(!empty($name) && !empty($email) && !empty($msg)){
		$cleanName = filter_var($name, FILTER_SANITIZE_STRING);
		$cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
		$cleanMsg = filter_var($msg, FILTER_SANITIZE_STRING);		
	} else {
		$app->redirect('/contact');
	}
	$transport = Swift_SendmailTransport::newIntance('/usr/sbin/sendmail -bs');
	$mailer = \Swift_Mailer::newIntance($transport);
	
	$message = \Swift_Message::newIntance();
	$message -> setSubject("Email form Selects site");
	$message -> setFrom(array(
		$cleanEmail => $cleanName
	));
	$message -> setTo(array("esolar07@gmail.com"));
	$message -> setBody($cleanMsg);
	
	$result = $mailer -> send($message);
	
	if( $result > 0){
		// sends message if successful and redirects back to home
		$app->redirect('/');
	} else {
		// sends message to user the message failed and redirects back to contact
		$app->redirect('/contact');
		
	}
});

$app->run();



?>

