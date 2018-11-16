<?php 
use \oQuiz\Application as App;
// Inclusion autoload de Composer
 require __DIR__.'/../vendor/autoload.php';

// Instance de Application
$application = new App();

$application->run();
