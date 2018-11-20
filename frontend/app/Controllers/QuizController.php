<?php
namespace oQuiz\Controllers;
use oQuiz\Templator\Templator;


class QuizController extends CoreController
{
    public function quiz($params) {

        $this->oTemplator->setVar('quizId',$params['id']);

        if(empty($_SESSION['userId'])) 
        {
             $this->oTemplator->setVar('js','visitorQuiz');
            $this->show('visitorQuiz');
        }
        else 
        {
            $this->oTemplator->setVar('js','userQuiz');
            $this->show('userQuiz');
        }
    }

    public function byTag($params) {
        $this->oTemplator->setVar('tagId',$params['id']);
        $this->oTemplator->setVar('js','tags');
        $this->show('quizByTag');
    }
}