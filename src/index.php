<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;

$config = require(__DIR__ . '/config.php');

$db = new PDO($config['db']['url'], $config['db']['user'], $config['db']['password']);

$app = new Silex\Application();


$app->register(new Silex\Provider\TwigServiceProvider(), [
  'twig.path' => __DIR__ . '/views'
]);

$app->get('/init', function() use ($db, $config) {
  $db->query("DROP TABLE IF EXISTS users");

  $res = $db->query("CREATE TABLE users(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(256),
    email VARCHAR(64),
    hidden TINYINT(1),
    card VARCHAR(32),
    password VARCHAR(32)
  )");

  $stmt = $db->prepare('INSERT INTO users (name, email, password, hidden, card) VALUES (:name, :email, :password, :hidden, :card)');

  foreach($config['users'] as $user) {
    $res = $stmt->execute($user);
  }

  return 'done';
});

$app->get('/inj', function(Request $req) use($app, $db) {
  $limit = $req->query->get('limit');
  if(!$limit) {
    $limit = 10;
  }

  $offset = $req->query->get('offset');
  if(!$offset) {
    $offset = 0;
  }

  $users = $db
    ->query('SELECT `id`, `name`, `email` FROM users WHERE hidden=0 limit ' . $limit . ' offset ' . $offset);

  if(!$users) {
    echo 'SQL_ERROR<br>';
    var_dump($db->errorInfo());
    die();
  }

  $users = $users->fetchAll(PDO::FETCH_ASSOC);

  return $app['twig']->render('list.twig', ['users' => $users, 'fields' => array_keys($users[0])]);
});

// No injection because we cast user passed offset to int
$app->get('/noinjtc', function(Request $req) use($app, $db) {
  $limit = $req->query->get('limit');
  if(!$limit) {
    $limit = 10;
  }

  $offset = $req->query->get('offset');
  if(!$offset) {
    $offset = 0;
  }

  $users = $db
    ->query('SELECT `id`, `name`, `email` FROM users WHERE hidden=0 limit ' . intval($limit) . ' offset ' . intval($offset));
  
  if(!$users) {
    echo 'SQL_ERROR<br>';
    var_dump($db->errorInfo());
    die();
  }

  $users = $users->fetchAll(PDO::FETCH_ASSOC);

  return $app['twig']->render('list.twig', ['users' => $users, 'fields' => array_keys($users[0])]);
});

// No injection because we use prepared statement
$app->get('/noinjpre', function(Request $req) use($app, $db) {
  $limit = $req->query->get('limit');
  if(!$limit) {
    $limit = 10;
  }

  $offset = $req->query->get('offset');
  if(!$offset) {
    $offset = 0;
  }

  $stmt = $db->prepare('SELECT `id`, `name`, `email` FROM users WHERE hidden=0 LIMIT :limit OFFSET :offset');
  $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  
  if(!$stmt->execute()) {
    echo 'SQL_ERROR<br>';
    $stmt->debugDumpParams();
    var_dump($stmt->errorInfo());
    die();
  }

  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return $app['twig']->render('list.twig', ['users' => $users, 'fields' => array_keys($users[0])]);
});

$app->run();