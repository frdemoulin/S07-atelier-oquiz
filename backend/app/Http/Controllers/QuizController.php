<?php

// déclaration du namespace
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

// import des models pour effectuer des requêtes
use App\AppUsers;
use App\Questions;
use App\Quizzes;
use App\QuizzesHasTags;
use App\Tags;

// import de la classe Request (objet Lumen)
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

    /**
     * méthode associée au endpoint /
     *
     * @param Request $request
     * @param string $id
     * @return json
     */
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
        // il contiendra les infos d'un quiz d'id donné
        $arrayQuiz = [];
        // on sélectionne le quiz d'id passé en paramètre de l'url
        $quizInfo = Quizzes::find($id);
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

        $questionsInfo = DB::select('SELECT id, question, anecdote, levels_id
        FROM questions
        WHERE quizzes_id = '.$id
        );
        
        //dump($questionsInfo);

        foreach ($questionsInfo as $key => $value):

            $quizQuestion = $request->input('question', $value->question);
            $questionId = $request->input('id', $value->id);
            $quizAnecdote = $request->input('anecdote', $value->anecdote);
            $quizLevelId = $request->input('levels_id', $value->levels_id);

            // table levels
            $levelInfo = DB::select('SELECT `name`
            FROM `levels`
            WHERE id ='.$quizLevelId
            );

            $levelName = $levelInfo[0]->name;
            //dump($levelInfo);

            // table answers
            $answerInfo = DB::select('SELECT `description`
            FROM `answers`
            WHERE questions_id ='.$questionId
            );
            
            $answerDescription = $answerInfo[0]->description;
            //dump($levelName);

            $currentQuestionInfo = [
                //$key => [
                    'question' => $quizQuestion,
                    'anecdote' => $quizAnecdote,
                    'level' => $levelName,
                    'answer' => $answerDescription
                //]
            ];

            array_push($arrayQuiz, $currentQuestionInfo);
        endforeach;

        //dump($arrayQuiz);

        

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

        return response()->json($arrayQuiz);
    }

    /**
     * méthode associée au endpoint /tags
     *
     * @return json
     */
    public function tags()
    {

        // array_json =  [
        //     'id' => 'name',
        //      ...
        // ]
        
        // on déclare le tableau à retourner en json
        // il contiendra tous les tags présents dans la table tags
        $tagsAllQuizzes = [];
        // on sélectionne les champs id et name dans la table tags
        $tagsInfo = Tags::select('id', 'name')->get();
        //dd($tagsInfo);
        
        // on pushe les infos des tags dans le tableau associatif $tagsAllQuizzes
        // 'id' => 'name'
        foreach ($tagsInfo as $currentTag) {
            $currentTagId = $currentTag->id;
            $currentTagName = $currentTag->name;
            $tagsAllQuizzes[$currentTagId] = $currentTagName;
        }

        //dd($tagsAllQuizzes);

        return response()->json($tagsAllQuizzes); 
        
    }
}
