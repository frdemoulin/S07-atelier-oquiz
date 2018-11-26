<?php
namespace oQuiz\Controllers;
use oQuiz\Utils\Templator;
use oQuiz\Utils\UserSession;

// classe parente des controller
abstract class CoreController 
{
    // contient l'instance du templator
    protected $oTemplator;
    protected $router;

    public function __construct($router)
    {
        // j'envoi le router + le chemin absolu vers views en instanciant templator
        $this->oTemplator = new Templator(__DIR__.'/../views', $router);
        $this->router = $router;
        // si l'utilisateur est connecté
        if(UserSession::isConnected()) 
        {
            $this->oTemplator->setVar('user', UserSession::getUser());
        }
        // si l'utilisateur à cliqué sur le bouton de déconnexion
        if(!empty($_GET['disconnect'])) {
            // on détruit la session
            session_unset();
            header('Location: ' . $this->router->generate('signIn'));

        }
    }
    // transmet les variables essentiel à templator 
    // appel templator pour assembler le template voulu
    protected function show($viewName)
    {
        $this->oTemplator->display($viewName);
    }

    public function isAuthorized($type) {

        if ($type === 'alreadyConnect')
        {
            if(UserSession::isConnected()) 
            {
                header('Location: ' . $this->router->generate('home'));
            }
        }
        if ($type === 'isDisconnect')
        {
            if(!UserSession::isConnected()) 
            {
                header('Location: ' . $this->router->generate('signIn'));
            }
        }
        if ($type === 'notAdmin')
        {
            if(!UserSession::isAdmin())
            {
                header('Location: ' . $this->router->generate('home'));
            }
        }


    }
}