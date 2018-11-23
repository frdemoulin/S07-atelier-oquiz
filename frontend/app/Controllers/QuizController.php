<?php
namespace oQuiz\Controllers;
use oQuiz\Utils\Templator;
use oQuiz\Utils\UserSession;


class QuizController extends CoreController
{
    public function quiz($params) {

        $this->oTemplator->setVar('quizId',$params['id']);

        if(UserSession::isConnected()) 
        {
            $this->oTemplator->setVar('js','userQuiz');
            $this->show('userQuiz');
        }
        else 
        {
            $this->oTemplator->setVar('js','visitorQuiz');
            $this->show('visitorQuiz');

        }
    }

    public function byTag($params) {
        $this->oTemplator->setVar('tagId',$params['id']);
        $this->oTemplator->setVar('js','tags');
        $this->show('quizByTag');
    }
}