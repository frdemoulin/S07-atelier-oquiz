<?php

// on déclare le namespace
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

//import de mon model pour effectuer des requetes
use App\Quizzes;
use App\Tags;
use App\AppUsers;
use App\QuizzesHasTags;

/*
 Pour utiliser / recuperer l'objet Request, on doit obligatoirement importer cette classe Lumen
*/

use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // méthode associée à la route /
    public function displayHome(Request $request)
    {
        //la methode select ne fait que lire des données , en revanche il existe d'autre methode pour effectuer d'autres actions du type insert, delete etc...
        //$videoGameList = DB::select("SELECT * FROM videogame"); 
        
        /*
         Grace au model que j'ai créé à la racine du dossier app, je peux desormais m'epargner une requete direct et assez commune : retourner tout les objet de tel ou tel table.

         Pour que cela fonctionne je dois appeler en amont de mon controller mon model sur lequel je souhaite retourner un ou plusieurs elements et effectuer un appel (dans le cas je je veux retourner tout les element) un ::al() qui effectuera le meme genre de requete fait precedemment avec DB::select
        */
        // https://laravel.com/docs/5.7/queries
        // version détaillée : $videoGameList = DB::select("SELECT * FROM videogame");
        // Quizzes correspond au nom de la classe
        //$quizzesList = Quizzes::where('id', 1)->first();

        $arrayQuizzes = [];
        
        $quizzesList = Quizzes::all();

        // $sqlAuthor = '
        //     SELECT firstname, lastname
        //     FROM app_users
        //     INNER JOIN quizzes
        //     ON app_users.id = quizzes.app_users_id 
        //     WHERE quizzes.app_users_id = :id
        // ';

        $authorList = DB::select("SELECT firstname, lastname
        FROM app_users
        INNER JOIN quizzes
        ON app_users.id = quizzes.app_users_id
        ORDER BY quizzes.id ASC
        ");

        //dump($authorList);
        //dump($quizzesList);

        foreach ($quizzesList as $key => $quiz):

            $quizId = $request->input('id', $quiz->id);
            $quizTitle = $request->input('title', $quiz->title);
            $quizDescription = $request->input('description', $quiz->description);
            $quizAppUsersId = $request->input('app_users_id', $quiz->app_users_id);
            $authorFirstname = $authorList[$key]->firstname;
            $authorLastname = $authorList[$key]->lastname;

            $currentQuiz = [
                'id' => $quizId,
                'title' => $quizTitle,
                'description' => $quizDescription,
                'firstname' => $authorFirstname,
                'lastname' => $authorLastname
            ];

            array_push($arrayQuizzes, $currentQuiz);
        endforeach;

        //dump($arrayQuizzes);

        return response()->json($arrayQuizzes);
    }
}
