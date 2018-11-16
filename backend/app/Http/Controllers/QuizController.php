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

class QuizController extends Controller
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
    public function quiz(Request $request, $id)
    {

        // array_json =  [
        //     [] => [
        //          'id' => '',
        //          'name' => '',
        //          'description' => ''
        //          'tags' => [
        //                      '0' => '',
        //                      '1' => '',
        //                      ...
        //                    ]
        //          ],      
        //     [] => [
        //          'question' => '',
        //          'anecdote' => '',
        //          'level' => '',
        //          'answer' => '',
        //         ],
        //     [] => [
        //          'question' => '',
        //          'anecdote' => '',
        //          'level' => '',
        //          'answer' => '',
        //         ],
        //         ...
        // ]

        // on déclare le tableau à retourner en json
        $arrayQuiz = [];
        // on sélectionne le quiz d'id passé en paramètre de l'url
        $quizInfo = Quizzes::find($id);
        //$quizInfo = Quizzes::select("SELECT * FROM quizzes");
        //$quizInfo = Quizzes::where('id', '=', $id);
        $quizId = $request->input('id', $quizInfo->id);
        dump($quizId);
        $quizTitle = $request->input('title', $quizInfo->title);
        dump($quizTitle);
        $quizDescription = $request->input('description', $quizInfo->description);
        dump($quizDescription);

        //dump($quizInfo);

        $authorInfo = DB::select('SELECT firstname, lastname
        FROM app_users
        INNER JOIN quizzes
        ON app_users.id = quizzes.app_users_id
        WHERE quizzes.id = '.$id
        );

        $quizAuthorFirstname = $request->input('firstname', $authorInfo->description);

        dump($authorInfo);
        // //dump($quizzesList);

        // foreach ($quizzesList as $key => $quiz):

        //     $quizId = $request->input('id', $quiz->id);
        //     $quizTitle = $request->input('title', $quiz->title);
        //     $quizDescription = $request->input('description', $quiz->description);
        //     $quizAppUsersId = $request->input('app_users_id', $quiz->app_users_id);
        //     $authorFirstname = $authorList[$key]->firstname;
        //     $authorLastname = $authorList[$key]->lastname;

        //     $tagsList = DB::select('SELECT name
        //     FROM tags
        //     INNER JOIN quizzes_has_tags
        //     ON quizzes_has_tags.tags_id = tags.id
        //     WHERE quizzes_has_tags.quizzes_id ='.$quizId.'
        //     ORDER BY quizzes_has_tags.quizzes_id ASC
        //     ');

        //     //dump($tagsList);

        //     $currentQuiz = [
        //         'id' => $quizId,
        //         'title' => $quizTitle,
        //         'description' => $quizDescription,
        //         'firstname' => $authorFirstname,
        //         'lastname' => $authorLastname,
        //         'tags' => $tagsList
        //     ];

        //     array_push($arrayQuizzes, $currentQuiz);
        // endforeach;

        //dump($arrayQuizzes);

        // return response()->json($arrayQuizzes);
        // array_json =  [
        //     [] => [
        //          'question' => '',
        //          'anecdote' => '',
        //          'level' => '',
        //          'answer' => '',
        //         ],
        //     [] => [
        //          'question' => '',
        //          'anecdote' => '',
        //          'level' => '',
        //          'answer' => '',
        //         ],    ]
        // return 'Hello world!'.$id;

        //dump($arrayQuizzes);

        //return response()->json($arrayQuizzes);
    }
}
