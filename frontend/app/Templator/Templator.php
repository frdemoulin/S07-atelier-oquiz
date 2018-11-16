<?php
namespace oQuiz\Templator;

class Templator {
    private $absolutWay;
    private $var = [];
    private $router;

    public function __construct ($pathToViews, $router) {
        $this->absolutWay = $pathToViews;
        $this->router = $router;
    }

    public function setVar($name, $content) {
        $this->var[$name] = $content;
    }
    public function getVar($indexName) {
        // si l'index existe on l'envoi
        if(isset($this->var[$indexName])) {
            return $this->var[$indexName];
        }
    }    
    // Méthode permettant d'inclure UN template bien préci dont le nom est donné en paramètre
    public function includeOne($template) {
        // verify = chemin complet vers le template demandé
        $verify = $this->absolutWay. '/'.$template.'.tpl.php';
        // si le fichier existe je l'inclu
        if (file_exists($verify)) {
            include $this->absolutWay. '/'.$template.'.tpl.php';
        }
    }
    // cette méthode permet d'afficher TOUT les templates, avec comme contenu principal: le nom du template donné en parametre
    public function display ($template) {
        // verify = chemin complet vers le template demandé
        $verify = $this->absolutWay. '/'.$template.'.tpl.php';
        // si le fichier existe
        if (file_exists($verify)) {
            // je fais tout mes includes.
            include $this->absolutWay. '/header.tpl.php';
            include $verify;
            include $this->absolutWay. '/footer.tpl.php';
        }
        // S'il n'existe pas == affichage page 404
        else {
            header("HTTP/1.0 404 Not Found");
            include $this->absolutWay. '/header.tpl.php';
            include $this->absolutWay. '/error404.tpl.php';
            include $this->absolutWay. '/footer.tpl.php';
        }
    }


}
