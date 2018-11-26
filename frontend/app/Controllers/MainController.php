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
        $this->isAuthorized('alreadyConnect');

        $this->oTemplator->setVar('js', 'signIn');
        $this->show('signIn');
        
    }

    public function register() {
        $this->isAuthorized('alreadyConnect');

        $this->oTemplator->setVar('js', 'register');
        $this->show('register');
        
    }

    public function account() {
        $this->isAuthorized('isDisconnect');
        $this->isAuthorized('notAdmin');

        $this->oTemplator->setVar('js', 'admin');

        $this->show('admin');
        
    }

    public function validateAccount() {
        // si déjà connecté je redirige vers page la home
        $this->isAuthorized('alreadyConnect');

        // si get id ou token manquant je redirige vers "s'inscrire"
        if(empty($_GET['id']) || empty($_GET['token']))
        {
            $this->isAuthorized('isDisconnect');
        }
        // sinon : 
        $this->oTemplator->setVar('js', 'validateAccount');
        $this->oTemplator->setVar('id', $_GET['id']);
        $this->oTemplator->setVar('token', $_GET['token']);
        $this->show('validateAccount');
    }

    public function resetPassword() {
        $this->isAuthorized('alreadyConnect');

        $this->oTemplator->setVar('js', 'resetPass');
        $this->show('resetPass');
    }

    public function error404() {
        $this->show('error404');
    }
}