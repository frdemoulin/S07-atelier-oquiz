<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// route en get associée au endpoint /
// affichage de tous les quiz
$router->get('/', [
    'as' => 'home', // clef = as => valeur = url
    'uses' => 'MainController@displayHome' // le code qui doit être exécuté et qui se situe dans MainController@laMethode à appeler. C'est Lumen qui se charge de découper MainController@laMethode sur le arobase (# avec AltoRouter)
]);

// route en get associée au endpoint /quiz/[id]
// affichage du quiz d'id donné
$router->get('/quiz/{id}', [
    'as' => 'quiz',
    'uses' => 'QuizController@quiz'
]);

// route en get associée au endpoint /tags
// affichage de tous les tags (sujets de quiz)
$router->get('/tags', [
    'as' => 'tags',
    'uses' => 'QuizController@tags'
]);