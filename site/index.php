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
	$app->render("examples.twig");
})->name('Examples');

$app->get("/contact", function() use($app){
	$app->render( "contact.twig");
})->name('Contact');

$app->run();



?>

