<?php
namespace oQuiz\Controllers;
use oQuiz\Templator\Templator;


class QuizController extends CoreController
{
    public function quiz($params) {

        $this->oTemplator->setVar('quizId',$params['id']);

        if(empty($_SESSION['userId'])) 
        {
            $this->show('visitorQuiz');
        }
        else 
        {
            $this->show('userQuiz');
        }
    }
}