<?php
namespace oQuiz\Controllers;
use oQuiz\Templator\Templator;

// classe parente des controller
abstract class CoreController 
{
    // contient l'instance du templator
    protected $oTemplator;

    public function __construct($router)
    {
        session_start();
        //$_SESSION['userId'] = 21;
        // j'envoi le router + le chemin absolu vers views en instanciant templator
        $this->oTemplator = new Templator(__DIR__.'/../views', $router);
        // si l'utilisateur à cliqué sur le bouton de déconnexion
        if(!empty($_GET['disconnect'])) {
            // on détruit la session
            session_unset();
        }
    }
    // transmet les variables essentiel à templator 
    // appel templator pour assembler le template voulu
    protected function show($viewName)
    {
        $this->oTemplator->display($viewName);
    }
}