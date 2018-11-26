<?php
namespace oQuiz;

class Application 
{
    private $router;

    public function __construct() 
    {
        // Lancer AltoRouter
        $this->router = new \AltoRouter();
        // Récuperation de BASE_URI
        $baseUrl = isset($_SERVER['BASE_URI']) ? trim($_SERVER['BASE_URI']) : '';
        // Définition de la BASE_URI pour AltoRouter
        $this->router->setBasePath($baseUrl);
        // Appel de la fonction pour générer les routes
        $this->defineRoutes();
    }

    public function run()
    {
        // Recherche d'une correspondance avec l'url appelé
        $match = $this->router->match();

        if ($match)
        {
            list($controllerName, $methodName) = explode('#', $match['target']);
            $params = $match['params'];
        }
        else 
        {
            $controllerName = 'MainController';
            $methodName = 'error404';
            $params = array();
        }

        // redefinition du nom du controller avec le namespace
        // on parle alors de FQCN (= Fully Qualified Class Name)
        $controllerName = '\oQuiz\Controllers\\'.$controllerName;
        // on instancie le controller
        $myController = new $controllerName($this->router);
        // on appelle la méthode
        $myController->$methodName($params);
    }

    private function defineRoutes()
    {
        // MainController
        $this->router->map('GET', '/', 'MainController#home', 'home');
        $this->router->map('GET', '/connexion', 'MainController#signIn', 'signIn');
        $this->router->map('GET', '/inscription', 'MainController#register', 'register');
        $this->router->map('GET', '/validation', 'MainController#validateAccount', 'validate');
        $this->router->map('GET', '/mot-de-passe-oublie', 'MainController#resetPassword', 'resetPassword');
        $this->router->map('GET', '/mon-compte', 'MainController#account', 'account');
        // QuizController
        $this->router->map('GET', '/quiz/[i:id]', 'QuizController#quiz', 'quiz');
        $this->router->map('GET', '/quiz-by-tag/[i:id]', 'QuizController#byTag', 'byTag');
        
    }
}