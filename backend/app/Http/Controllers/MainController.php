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
        foreach ($quizzesList as $quiz):
            $quizId = $request->input('id', $quiz->id);
            $quizTitle = $request->input('title', $quiz->title);
            $quizDescription = $request->input('description', $quiz->description);
            $quizAppUsersId = $request->input('app_users_id', $quiz->app_users_id);

            $currentQuiz = [
                'id' => $quizId,
                'title' => $quizTitle,
                'description' => $quizDescription,
                'app_users_id' => $quizAppUsersId
            ];

            array_push($arrayQuizzes, $currentQuiz);
        endforeach;
        //dump($arrayQuizzes);

        // $tagsList = Tags::all();
        // dump($tagsList);
        // foreach ($tagsList as $tag):
        //     $name = $request->input('name', $tag->name);
        //     echo $name;
        // endforeach;



        // $quizzesList = Quizzes::where('id', 1)->first();
        // $quizId = $request->input('id', $quizzesList->id);
        // $quizTitle = $request->input('title', $quizzesList->title);
        // $quizDescription = $request->input('description', $quizzesList->description);
        // $quizAppUsersId = $request->input('app_users_id', $quizzesList->app_users_id);

        //$array[];

        // for ($id=1; $id <= 18; $id++) {
        //     $quizzesList = Quizzes::where('id', $id)->first();
        //     $quizId = $request->input('id', $quizzesList->id);
        //     $quizTitle = $request->input('title', $quizzesList->title);
        //     $quizDescription = $request->input('description', $quizzesList->description);
        //     $quizAppUsersId = $request->input('app_users_id', $quizzesList->app_users_id);

        //     array_push();

        //     $array = response()->json([
        //         'id' => $quizId,
        //         'title' => $quizTitle,
        //         'description' => $quizDescription,
        //         'app_users_id' => $quizAppUsersId
    
        //     ]);
        // }
        
        //dump($quizzesList);
        
        // $quizId = $request->input('id', $quizzesList->id);
        // $quizTitle = $request->input('title', $quizzesList->title);
        // $quizDescription = $request->input('description', $quizzesList->description);
        // $quizAppUsersId = $request->input('app_users_id', $quizzesList->app_users_id);

        //dump($quizTitle);

        // $tagsList = Tags::all();
        //dump($tagsList);

        // dump($request);

        // pour récupérer une donnée passée en paramètre, il faut utiliser la méthode input de l'objet Request
        // $order = $request->input('order');

        return response()->json($arrayQuizzes);

        // pour récupérer une donnée passée en paramètre, il faut utiliser la méthode input de l'objet Request
        //$order = $request->input('order');
        //dump($order);

        //ce qui compte pour que la view puisse creer une variable exploitable c'est la clef associative associé a la variable a passer coté controller
        // return view('home', [
        //     'quizzesList' => $quizzesList
        // ]);
    }
}
