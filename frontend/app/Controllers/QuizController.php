<?php
namespace oQuiz\Controllers;
use oQuiz\Templator\Templator;


class QuizController extends CoreController
{
    public function quiz($params) {

        $this->oTemplator->setVar('quizId',$params['id']);

        if(empty($this->userId)) 
        {
            dump('visitor');
            $this->show('visitorQuiz');
        }
        else 
        {
            dump('user');
            $this->show('userQuiz');
        }
    }
}