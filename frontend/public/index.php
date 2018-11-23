<?php 
use \oQuiz\Application as App;
// Inclusion autoload de Composer
 require __DIR__.'/../vendor/autoload.php';

// lancement de la session
 session_start();

// Instance de Application
$application = new App();

$application->run();
