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

    /**
     * route en get associée au endpoint /
     * affichage de tous les quiz
     *
     * @param Request $request
     * @return json
     */
    public function displayHome(Request $request)
    {
        $arrayQuizzes = [];
        $quizzesList = Quizzes::all();

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

            $tagsList = DB::select('SELECT tags.id, name
            FROM tags
            INNER JOIN quizzes_has_tags
            ON quizzes_has_tags.tags_id = tags.id
            WHERE quizzes_has_tags.quizzes_id ='.$quizId.'
            ORDER BY quizzes_has_tags.quizzes_id ASC
            ');

            //dump($tagsList);

            $currentQuiz = [
                'id' => $quizId,
                'title' => $quizTitle,
                'description' => $quizDescription,
                'firstname' => $authorFirstname,
                'lastname' => $authorLastname,
                'tags' => $tagsList
            ];

            array_push($arrayQuizzes, $currentQuiz);
        endforeach;

        //dump($arrayQuizzes);

        return response()->json($arrayQuizzes);
    }
}
