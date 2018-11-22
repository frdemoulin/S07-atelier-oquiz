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

/*
************************
* Routes MainController
************************
*/

// route en GET associée au endpoint /
// liste tous les quiz
$router->get('/', [
    'as' => 'home', // clef = as => valeur = url
    'uses' => 'MainController@displayHome' // le code qui doit être exécuté et qui se situe dans MainController@laMethode à appeler. C'est Lumen qui se charge de découper MainController@laMethode sur le arobase (# avec AltoRouter)
]);

/*
************************
* Routes QuizController
************************
*/

// route en GET associée au endpoint /quiz/[id]
// affichage du quiz d'id donné
$router->get('/quiz/{id}', [
    'as' => 'quiz',
    'uses' => 'QuizController@quiz'
]);

// route en GET associée au endpoint /tags
// affichage de tous les tags (sujets de quiz)
$router->get('/tags', [
    'as' => 'tags',
    'uses' => 'QuizController@tags'
]);

// route en GET associée au endpoint /tags/[id]/quiz
// liste tous les quiz associés au tag d'id donné
$router->get('/tags/{id}/quiz', [
    'as' => 'list_by_tag',
    'uses' => 'QuizController@listByTag'
]);

/*
************************
* Routes UserController
************************
*/

/**
 * SIGNUP
 */

// route en GET associée au endpoint /signup
// gère le formulaire d'inscription
$router->get('/signup', [
    'as' => 'signup',
    'uses' => 'UserController@signup'
]);

// route en POST associée au endpoint /signup
// traite le formulaire d'inscription
$router->post('/signup', [
    'as' => 'signup_post',
    'uses' => 'UserController@signupPost'
]);

/**
 * SIGNIN
 */

// route en GET associée au endpoint /signin
// gère le formulaire de connexion
$router->get('/signin', [
    'as' => 'signin',
    'uses' => 'UserController@signin'
]);

// // route en POST associée au endpoint /signin
// // traite le formulaire de connexion
// $router->post('/signin', [
//     'as' => 'signin_post',
//     'uses' => 'UserController@signinPost'
// ]);

/**
 * ACCOUNT
 */

// route en GET associée au endpoint /account
// page profil de l’utilisateur connecté
$router->get('/account', [
    'as' => 'account',
    'uses' => 'UserController@profile'
]);

/**
 * LOGOUT
 */

// route en GET associée au endpoint /logout
// page traitant la déconnexion de l’utilisateur
$router->get('/logout', [
    'as' => 'logout',
    'uses' => 'UserController@logout'
]);