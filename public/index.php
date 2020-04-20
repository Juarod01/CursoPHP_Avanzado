<?php
  
  require_once '../vendor/autoload.php';

  session_start();

  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
  $dotenv->load();

  if(getenv('DEBUG') === 'true'){
    //Variales de errores
    ini_set('display_errors', 1);
    ini_set('display_starup_error', 1);
    error_reporting(E_ALL); //Todos los errores.
  }

  use \App\Middlewares\AuthenticationMiddleware;
  use \Franzl\Middleware\Whoops\WhoopsMiddleware;
  use Illuminate\Database\Capsule\Manager as Capsule;
  use Aura\Router\RouterContainer;
  use Laminas\Diactoros\Response;
  use Laminas\Diactoros\ServerRequestFactory;
  use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
  use WoohooLabs\Harmony\Harmony;
  use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
  use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;
  use Monolog\Logger;
  use Monolog\Handler\StreamHandler;

  $log = new Logger('app');
  $log->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::WARNING));

  $container = new DI\Container();

  $capsule = new Capsule;
  $capsule->addConnection([
      'driver'    => 'mysql',
      'host'      => getenv('DB_HOST'),
      'database'  => getenv('DB_NAME'),
      'username'  => getenv('DB_USER'),
      'password'  => getenv('DB_PASS'),
      'charset'   => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix'    => '',
  ]);
  // Make this Capsule instance available globally via static methods... (optional)
  $capsule->setAsGlobal();
  // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
  $capsule->bootEloquent();

  $request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

//$baseRoute = '/PlatziPHP/CursoPHP';
$baseRoute = '';

$map->get('index', $baseRoute.'/', [
//$map->get('index', '/', [
  'App\Controllers\IndexController',
  'indexAction'
]);
//$map->get('addjob', '/add/job', [
  $map->get('addJob', $baseRoute.'/add/job', [
    'App\Controllers\JobsController',
    'getAddJobAction',
    true
  ]);
$map->post('saveJob', $baseRoute.'/add/job', [
  //$map->post('saveJob', 'add/job', [
    'App\Controllers\JobsController',
    'getAddJobAction',
    true
]);
$map->get('indexJob', $baseRoute.'/jobs', [
//$map->get('indexJob', '/add/job', [
  'App\Controllers\JobsController',
  'indexAction',
  true
]);
$map->get('deleteJobs', $baseRoute.'/jobs/delete', [
  //$map->get('addJob', '/add/job', [
    'App\Controllers\JobsController',
    'deleteAction',
    true
]);
//$map->get('addProject', '/add/project', [
$map->get('addProject', $baseRoute.'/add/project', [
  'App\Controllers\ProjectsController',
  'getAddProjectAction',
  true
]);
//$map->post('saveProject', '/add/project', [
$map->post('saveProject', $baseRoute.'/add/project', [
  'App\Controllers\ProjectsController',
  'getAddProjectAction',
  true
]);
//$map->get('addUser', '/add/user', [
$map->get('addUser', $baseRoute.'/add/user', [
  'App\Controllers\UserController',
  'getAddUserAction',
  true
]);
$map->post('saveUser', $baseRoute.'/add/user', [
//$map->post('saveUser', '/add/user', [
  'App\Controllers\UserController',
  'getAddUserAction',
  true
]);
$map->get('loginForm', $baseRoute.'/login', [
//$map->get('loginForm', '/login', [
  'App\Controllers\AuthController',
  'getLogin'
]);
$map->post('auth', $baseRoute.'/auth', [
//$map->post('auth', '/auth', [
  'App\Controllers\AuthController',
  'postLogin'
]);
$map->get('admin', $baseRoute.'/admin', [
//$map->get('admin', '/admin', [
  'App\Controllers\AdminController',
  'getIndex',
  true
]);
$map->get('logout', $baseRoute.'/logout', [
//$map->get('logout', '/logout', [
  'App\Controllers\AuthController',
  'getLogout'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
  echo 'No route';
}else{
  try{
    $harmony = new Harmony($request, new Response());
    $harmony
      ->addMiddleware(new HttpHandlerRunnerMiddleware(new SapiEmitter())); //HttpHandlerRunnerMiddleware en vez de LaminasEmitterMiddleware
      if(getenv('DEBUG')==='true'){
        $harmony->addMiddleware(new \Franzl\Middleware\Whoops\WhoopsMiddleware());
      }
      $harmony->addMiddleware(new \App\Middlewares\AuthenticationMiddleware())
      ->addMiddleware(new Middlewares\AuraRouter($routerContainer))
      ->addMiddleware(new DispatcherMiddleware($container, 'request-handler'))
      ->run();
  }catch(Exception $e){
    $log->warning($e->getMessage());
    $emitter = new SapiEmitter();
    $emitter->emit(new Response\EmptyResponse(400));
  }catch(Error $e){
    $emitter = new SapiEmitter();
    $emitter->emit(new Response\EmptyResponse(400));
  }
}  