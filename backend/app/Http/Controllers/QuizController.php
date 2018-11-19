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

// import de la classe Collection (méthode collect())
// https://laravel.com/docs/5.1/collections#available-methods
// use Illuminate\Support\Collection;

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
     * route en get associée au endpoint /quiz/[id]
     * affichage du quiz d'id donné
     *
     * @param Request $request
     * @param string $id
     * @return json
     */
    public function quiz(Request $request, $id)
    {
        // format retour json attendu
        /*
        [
            [] => [
                'id' => '',
                'name' => '',
                'description' => ''
                'tags' => [
                            '0' => '',
                            '1' => '',
                            ...
                           ]
                 ],      
            [] => [
                'id_question' => '',
                'question' => '',
                'anecdote' => '',
                'level' => '',
                'answer' => '',
                ],
            [] => [
                'id_question' => '',
                'question' => '',
                'anecdote' => '',
                'level' => '',
                'answer' => '',
                ],
                ...
        ]
        */

        // format retour json attendu
        /*
        [
            [] => [
                'id' => '',
                'name' => '',
                'description' => ''
                'tags' => [
                            '0' => '',
                            '1' => '',
                            ...
                           ]
                 ],      
            [] => [
                'question' => '',
                'anecdote' => '',
                'level' => '',
                'answer' => '',
                ],
            [] => [
                'question' => '',
                'anecdote' => '',
                'level' => '',
                'answer' => '',
                ],
                ...
        ]
        */

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
                    'id_question' => $questionId,
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
     * liste tous les tags
     *
     * @return json
     */
    public function tags()
    {
        // format retour json attendu
        /*
        [
            'id' => 'name',
             ...
        ]
        */
        
        /*
         *****************************************
         * méthode à la mano avec foreach / START
         *****************************************

        // on déclare le tableau à retourner en json
        // il contiendra tous les tags présents dans la table tags
        $tagsAllQuizzes = [];
<<<<<<< HEAD
=======
        
        /*
         ********************************
         * méthode à la mano avec foreach
         ********************************

        // on déclare le tableau à retourner en json
        // il contiendra tous les tags présents dans la table tags
        $tagsAllQuizzes = [];
>>>>>>> eb4f4af94788df3ed1f4a6c9ea583de0db119bd7
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
        
        return response()->json($tagsAllQuizzes); 
<<<<<<< HEAD
        
        *****************************************
         * END / méthode à la mano avec foreach
         *****************************************
        */

        // $tagsAllQuizzes est le tableau à retourner en json
        // il contiendra tous les tags présents dans la table tags
=======
        */

>>>>>>> eb4f4af94788df3ed1f4a6c9ea583de0db119bd7
        // on sélectionne les champs id et name dans la table tags
        // la méthode pluck() de Lumen renvoie un tableau associatif
        // 'id' => 'name'
        $tagsAllQuizzes = Tags::pluck('name', 'id');
<<<<<<< HEAD
=======
        //dd($tagsInfo);
>>>>>>> eb4f4af94788df3ed1f4a6c9ea583de0db119bd7

        //dd($tagsAllQuizzes);

        return response()->json($tagsAllQuizzes); 
        
    }

    /**
     * méthode associée au endpoint /tags/[id]/quiz
     * liste tous les quiz associés au tag d'id donné
     *
     * @param Request $request
     * @param string $id
     * @return json
     */
    public function listByTag(Request $request, $id)
    {
        // format retour json attendu
        /*
        [
            [] => [
                'id' => '',
                'title' => '',
                'description' => '',
                'firstname' => '',
                'lastname' => ''
                ],
            [] => [
                'id' => '',
                'title' => '',
                'description' => '',
                'firstname' => '',
                'lastname' => ''
                ],
                ...
        ]
        */
        
        // on déclare le tableau à retourner en json
        // il contiendra les infos des quiz associés au tag d'id donné
        $quizzesByTagId = [];

        // on sélectionne les champs souhaités dans la table quizzes
        // via une jointure interne sur les tables app_users et quizzes_has_tags
        $quizzesInfo = Quizzes::select('quizzes.id', 'title', 'description', 'app_users.firstname', 'app_users.lastname')
        ->join('quizzes_has_tags', 'quizzes.id', '=', 'quizzes_has_tags.quizzes_id')
        ->join('app_users', 'quizzes.app_users_id', '=', 'app_users.id')
        ->where('tags_id', '=', $id)
        ->get();

        //dd($quizzesInfo);

<<<<<<< HEAD
        // on pushe les infos des quiz dans le tableau associatif $quizzesByTagId à retourner en json
=======
        // on pushe les infos des quiz dans le tableau associatif $quizzesByTagId
>>>>>>> eb4f4af94788df3ed1f4a6c9ea583de0db119bd7
        foreach ($quizzesInfo as $currentQuiz) {
            array_push($quizzesByTagId, [
                'id' => $currentQuiz->id,
                'title' => $currentQuiz->title,
                'description' => $currentQuiz->description,
                'firstname' => $currentQuiz->firstname,
                'lastname' => $currentQuiz->lastname
            ]);
        }

        //dd($quizzesByTagId);

        return response()->json($quizzesByTagId); 
        
    }
}
