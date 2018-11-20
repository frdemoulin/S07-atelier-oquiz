<?php
namespace oQuiz\Controllers;
use oQuiz\Templator\Templator;


class MainController extends CoreController
{
    public function home() {
        $this->oTemplator->setVar('js', 'home');
        $this->show('home');
    }

    public function signIn() {
        if(!empty($_SESSION['userId'])) 
        {
            $this->show('account');
        }
        else 
        {
            $this->oTemplator->setVar('js', 'signIn');
            $this->show('signIn');
        }
    }

    public function register() {
        if(!empty($_SESSION['userId'])) 
        {
            $this->show('account');
        }
        else 
        {
            $this->oTemplator->setVar('js', 'register');
            $this->show('register');
        }
    }

    public function account() {
        if(!empty($_SESSION['userId'])) 
        {
            $this->show('account');
        }
        else 
        {
            $this->oTemplator->setVar('js', 'signIn');
            $this->show('signIn');
        }
    }

    public function error404() {
        $this->show('error404');
    }
}