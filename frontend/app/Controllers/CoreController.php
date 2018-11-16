<?php
namespace oQuiz\Controllers;
use oQuiz\Templator\Templator;

// classe parente des controller
abstract class CoreController 
{
    // contient l'instance du templator
    protected $oTemplator;
    protected $userId = '';

    public function __construct($router)
    {
        if (!empty($_SESSION['userId'])) 
        {
            $this->userId = $_SESSION['userId'];
        }
        // j'envoi le router + le chemin absolu vers views en instanciant templator
        $this->oTemplator = new Templator(__DIR__.'/../views', $router);
    }
    // transmet les variables essentiel à templator 
    // appel templator pour assembler le template voulu
    protected function show($viewName)
    {
        $this->oTemplator->display($viewName);
    }
}