<?php
namespace oQuiz\Controllers;
use oQuiz\Utils\Templator;
use oQuiz\Utils\UserSession;


class MainController extends CoreController
{
    public function home() {
        $this->oTemplator->setVar('js', 'home');
        $this->show('home');
    }

    public function signIn() {
        // si déjà connecté je redirige vers page la home
        $this->isAuthorized('alreadyConnect');

        $this->oTemplator->setVar('js', 'signIn');
        $this->show('signIn');
        
    }

    public function register() {
        // si déjà connecté je redirige vers page la home
        $this->isAuthorized('alreadyConnect');

        $this->oTemplator->setVar('js', 'register');
        $this->show('register');
        
    }

    public function account() {
        // si non connecté je redirige vers page la page de connexion
        $this->isAuthorized('isDisconnect');
        // si non admin je redirige vers page la home
        $this->isAuthorized('notAdmin');

        $this->oTemplator->setVar('js', 'admin');
        $this->show('admin');
        
    }

    public function validateAccount() {
        // si déjà connecté je redirige vers page la home
        $this->isAuthorized('alreadyConnect');
        // si get id ou token manquant je redirige vers la page de connexion
        if(empty($_GET['id']) || empty($_GET['token']))
        {
            header('Location: ' . $this->router->generate('signIn'));
        }
        // sinon : 
        $this->oTemplator->setVar('js', 'validateAccount');
        $this->oTemplator->setVar('id', $_GET['id']);
        $this->oTemplator->setVar('token', $_GET['token']);
        $this->show('validateAccount');
    }

    public function lostPassword() {
        // si déjà connecté je redirige vers page la home
        $this->isAuthorized('alreadyConnect');

        $this->oTemplator->setVar('js', 'lostPass');
        $this->show('lostPass');
    }

    public function resetPassword() {
        // si déjà connecté je redirige vers page la home
        $this->isAuthorized('alreadyConnect');
        // si get id ou token manquant je redirige vers la page de connexion
        if(empty($_GET['id']) || empty($_GET['token']))
        {
            header('Location: ' . $this->router->generate('signIn'));
        }
        // sinon : 
        $this->oTemplator->setVar('id', $_GET['id']);
        $this->oTemplator->setVar('token', $_GET['token']);

        $this->oTemplator->setVar('js', 'resetPass');
        $this->show('resetPass');
    }

    public function error404() {
        $this->show('error404');
    }
}