<?php

// on déclare le namespace
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

//import de mon model pour effectuer des requetes
use App\Quizzes;
use App\Tags;
use App\AppUsers;
use App\QuizzesHasTags;
use App\Questions;

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
        // dump($quizId);
        $quizTitle = $request->input('title', $quizInfo->title);
        // dump($quizTitle);
        $quizDescription = $request->input('description', $quizInfo->description);
        // dump($quizDescription);

        //dump($quizInfo);

        $authorInfo = DB::select('SELECT firstname, lastname
        FROM app_users
        INNER JOIN quizzes
        ON app_users.id = quizzes.app_users_id
        WHERE quizzes.id = '.$id
        );

        //dump($authorInfo);
        $quizAuthorFirstname = $authorInfo[0]->firstname;
        $quizAuthorLastname = $authorInfo[0]->lastname;

        $arrayQuiz = [
            0 => [
            'id' => $quizId,
            'title' => $quizTitle,
            'description' => $quizDescription,
            'firstname' => $quizAuthorFirstname,
            'lastname' => $quizAuthorLastname
            ]
        ];
        //dump($arrayQuiz);

        $questionsInfo = DB::select('SELECT question, anecdote
        FROM questions
        WHERE quizzes_id = '.$id
        );
        
        //dump($questionsInfo);

        foreach ($questionsInfo as $key => $value):

            $quizQuestion = $request->input('question', $value->question);
            $quizAnecdote = $request->input('anecdote', $value->anecdote);

            $levelInfo = DB::select('SELECT name
            FROM level
            INNER JOIN quizzes_has_tags
            ON quizzes_has_tags.tags_id = tags.id
            WHERE quizzes_has_tags.quizzes_id ='.$quizId.'
            ORDER BY quizzes_has_tags.quizzes_id ASC
            ');

            $currentQuestionInfo = [
                //$key => [
                    'question' => $quizQuestion,
                    'anecdote' => $quizAnecdote
                //]
            ];

            array_push($arrayQuiz, $currentQuestionInfo);
        endforeach;

        dump($arrayQuiz);

        

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
