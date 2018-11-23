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

    public function error404() {
        $this->show('error404');
    }
}