<?php
namespace oQuiz\Controllers;
use oQuiz\Templator\Templator;


class MainController extends CoreController
{
    public function home() {
        $this->oTemplator->setVar('js', 'home');
        $this->show('home');
    }
    public function error404() {
        $this->show('error404');
    }
}