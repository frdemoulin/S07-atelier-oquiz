<?php

// déclaration du namespace
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

// import des models pour effectuer des requêtes
use App\Answers;
use App\AppUsers;
use App\Levels;
use App\Questions;
use App\Quizzes;
use App\QuizzesHasTags;
use App\Tags;

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

// méthodes rangées par ordre alphabétique, eh oui !

    /**
     * méthode en GET associée au endpoint /tags/[id]/quiz
     * liste tous les quiz associés au tag d'id donné
     *
     * @param string $id
     * @return json
     */
    public function listByTag($id)
    {
        // format retour json attendu
        /*
        [
            [] => 'name'
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
        
        // on sélectionne le champ name dans la table tags
        // la méthode value renvoie la valeur d'un champ, ô surprise
        $tagName = Tags::where('id', '=', $id)
                        ->value('name');

        $quizzesByTagId = [
            '0' => $tagName
        ];

        // on sélectionne les champs souhaités dans la table quizzes
        // via une jointure interne sur les tables app_users et quizzes_has_tags
        $quizzesInfo = Quizzes::select('quizzes.id', 'title', 'description', 'app_users.firstname', 'app_users.lastname')
                               ->join('quizzes_has_tags', 'quizzes.id', '=', 'quizzes_has_tags.quizzes_id')
                               ->join('app_users', 'quizzes.app_users_id', '=', 'app_users.id')
                               ->where('tags_id', '=', $id)
                               ->get();

        //dd($quizzesInfo);
        
        // on pushe les infos des quiz dans le tableau associatif $quizzesByTagId à retourner en json
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

    /**
     * route en GET associée au endpoint /quiz/[id]
     * affichage du quiz d'id donné
     *
     * @param string $id
     * @return json
     */
    public function quiz($id)
    {
        // format retour json attendu
        /*
        [
            [] => [
                'id' => '',
                'title' => '',
                'description' => '',
                'firstname' => '',
                'lastname' => '',
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
                'badAnswer' => [
                                '0'=> '',
                                '1' => '',
                                '2' => '',
                ],
           ],
        ]
        */

        // on déclare le tableau à retourner en json
        // il contiendra les infos d'un quiz d'id donné
        $arrayQuiz = [];

        /*
        *******************
        * GESTION QUIZ
        *******************
        */

        // on sélectionne le quiz d'id passé en paramètre de l'url
        $quizInfo = Quizzes::find($id);
        //dd($quizInfo);
        $quizId = $id;
        //dump($quizId);
        $quizTitle = $quizInfo['title'];
        //dump($quizTitle);
        $quizDescription = $quizInfo['description'];
        // dd($quizDescription);
        $quizAppUsersId = $quizInfo['app_users_id'];
        //dd($quizAppUsersId);

        //dd($quizInfo);

        /*
        *******************
        * GESTION AUTHOR
        *******************
        */

        $authorInfo = AppUsers::select('firstname', 'lastname')
                                ->where('id', $quizAppUsersId)
                                ->get();
        
        //dd($authorInfo);
        $quizAuthorFirstname = $authorInfo[0]->firstname;
        //dump($quizAuthorFirstname);
        $quizAuthorLastname = $authorInfo[0]->lastname;
        //dump($quizAuthorLastname);

        /*
        *******************
        * GESTION TAGS
        *******************
        */

        $tagsList = Tags::select('tags.id', 'name')
                         ->join('quizzes_has_tags', 'quizzes_has_tags.tags_id', '=', 'tags.id')
                         ->where('quizzes_has_tags.quizzes_id', '=', $quizId)
                         ->orderBy('quizzes_has_tags.quizzes_id', 'asc')
                         ->get();

        // $tagsList = DB::select('SELECT tags.id, name
        //     FROM tags
        //     INNER JOIN quizzes_has_tags
        //     ON quizzes_has_tags.tags_id = tags.id
        //     WHERE quizzes_has_tags.quizzes_id ='.$quizId.'
        //     ORDER BY quizzes_has_tags.quizzes_id ASC
        //     ');

        //dd($tagsList);

        $tagsArray = [];
        foreach($tagsList as $key => $currentTag) {
            array_push($tagsArray, $currentTag->name);
        }

        //dd($tagsArray);

        $arrayQuiz = [
            0 => [
            'id' => $quizId,
            'title' => $quizTitle,
            'description' => $quizDescription,
            'firstname' => $quizAuthorFirstname,
            'lastname' => $quizAuthorLastname,
            'tags' => $tagsArray
            ]
        ];
        //dd($arrayQuiz);

        /*
        ********************
        * GESTION QUESTIONS
        ********************
        */

        $questionsInfo = Questions::select('id', 'question', 'anecdote', 'levels_id', 'answers_id')
                         ->where('quizzes_id', '=', $quizId)
                         ->get();

        //dd($questionsInfo);

        foreach ($questionsInfo as $key => $value):

            $questionId = $value->id;
            $questionQuestion = $value->question;
            $quizAnecdote = $value->anecdote;
            $quizLevelId = $value->levels_id;
            $questionAnswerId = $value->answers_id;

            /*
            ********************
            * GESTION LEVEL
            ********************
            */

            // table levels
            $levelName = Levels::where('id', '=', $quizLevelId)
                                ->value('name');

            //dd($levelName);

            /*
            ********************
            * GESTION ANSWER
            ********************
            */

            // table answers
            $answerDescription = Answers::join('questions', 'questions.answers_id', '=', 'answers.id')
                                        ->where('answers.questions_id', '=', $questionId)
                                        ->value('description');
            
            //dd($answerDescription);

            /*
            ********************
            * GESTION BAD ANSWER
            ********************
            */

            // table answers
            $badAnswersInfo = Answers::select('description')
                                ->where('questions_id', '=', $questionId)
                                ->where('id', '<>', $questionAnswerId)
                                ->get();
            
            //dd($badAnswersInfo);

            $badAnswers = [];
            foreach ($badAnswersInfo as $currentBadAnswer) {
                
                array_push($badAnswers, $currentBadAnswer->description);
            }
            //dd($badAnswers);
            
            $currentQuestionInfo = [
                //$key => [
                    'question' => $questionQuestion,
                    'anecdote' => $quizAnecdote,
                    'level' => $levelName,
                    'answer' => $answerDescription,
                    'badAnswer' => $badAnswers
                //]
            ];

            array_push($arrayQuiz, $currentQuestionInfo);
        endforeach;

        //dd($arrayQuiz);

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
            'id' => 'name',
             ...
        ]
        */
        
        // /*
        //  *****************************************
        //  * méthode à la mano avec foreach / START
        //  *****************************************
        //  */

        // // on déclare le tableau à retourner en json
        // // il contiendra tous les tags présents dans la table tags
        // $tagsAllQuizzes = [];
        //  // on sélectionne les champs id et name dans la table tags
        // $tagsInfo = Tags::select('id', 'name')->get();
        // //dd($tagsInfo);
        
        // // on pushe les infos des tags dans le tableau associatif $tagsAllQuizzes
        // // 'id' => 'name'
        // foreach ($tagsInfo as $currentTag) {
        //     $currentTagId = $currentTag->id;
        //     $currentTagName = $currentTag->name;
        //     $tagsAllQuizzes[$currentTagId] = $currentTagName;
        // }
        
        // return response()->json($tagsAllQuizzes); 
        
        // /*
        // *****************************************
        //  * END / méthode à la mano avec foreach
        //  *****************************************
        // */

        // $tagsAllQuizzes est le tableau à retourner en json
        // il contiendra tous les tags présents dans la table tags
        // on sélectionne les champs id et name dans la table tags
        // la méthode pluck() de Lumen renvoie un tableau associatif
        // 'id' => 'name'
        $tagsAllQuizzes = Tags::pluck('name', 'id');

        //dd($tagsAllQuizzes);

        return response()->json($tagsAllQuizzes);
        
    }

}
