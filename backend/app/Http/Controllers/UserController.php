<?php

// on déclare le namespace
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

//import de mon model pour effectuer des requetes
use App\AppUsers;
// use App\Quizzes;
// use App\QuizzesHasTags;
// use App\Tags;

/*
 Pour utiliser / recuperer l'objet Request, on doit obligatoirement importer cette classe Lumen
*/

use Illuminate\Http\Request;

class UserController extends Controller
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
     * méthode en GET associée au endpoint /account/[id]
     * page profil de l’utilisateur connecté
     * renvoie les info du user d'id donné
     *
     * @param string $id
     * @return json
     */
    // public function profile($id)
    // {

    //     // on lit en base les infos de l'user
    //     $userInfo = AppUsers::select('firstname', 'lastname')
    //                             ->where('id', $id)
    //                             ->get();

    //     //dd($userInfo);

    //     // on redirige vers la page du compte utilisateur
    //     return response()->json($userInfo);
    // }

    public function profile()
    {   
        // array réponse à encoder en json
        $userResponse = [];
        $success = false;
        $msg = '';

        // // on lit en base les infos de l'user
        // $userInfo = AppUsers::select('firstname', 'lastname')
        // ->where('id', 1)
        // ->get();
        
        // $firstname = $userInfo[0]->firstname;
        // //dump($firstname);
        // $lastname = $userInfo[0]->lastname;
        // //dd($lastname);
        // //dd($userInfo);

        session_start();
        if (isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
            $success = true;
            $id = $_SESSION['userId'];

            // on lit en base les infos de l'user
            $userInfo = AppUsers::select('firstname', 'lastname')
            ->where('id', $id)
            ->get();
            
            $firstname = $userInfo[0]->firstname;
            //dump($firstname);
            $lastname = $userInfo[0]->lastname;

            //dd($userInfo);

            $userResponse = [
                'success' => $success,
                'firstname' => $firstname,
                'lastname' => $lastname
            ];

        return response()->json($userResponse);

        } else {
            $msg = 'id user non défini en session';

            $userResponse = [
                'success' => $success,
                'msg' => $msg
            ];

            return response()->json($userResponse);
        }
    }

    /**
     * méthode en GET associée au endpoint /signin
     * traite le formulaire de connexion
     *
     * @param Request $request
     * @return 
     */
    public function signin(Request $request)
    {
        // format retour json attendu
        /*
        [
            'success' => true ou false,
            msg => 'vide ou pas si erreur à afficher'
            ];
        ]
        */

        //dd($request);
        // on récupère les infos du form de connexion
        $email = $request->input('email');
        $passwordClair = $request->input('password');
        //dd($email);
        //dd($passwordClair);

        /*
        **************************
        * CONTROLE INTEGRITE FORM
        **************************
        */
        $msg ='identifiant et mot de passe incorrects';
        $success = false;
        // Si EMAIL ou PASSWORD est vide	
        if(empty($email) || empty($passwordClair)) {
            $msg = 'Les champs email et mot de passe ne peuvent pas être vides';
        } else { // Si EMAIL et PASSWORD sont remplis
            //echo 'pouet';
            // on lit en base les infos de l'user dont l'email a été donné
            $userCount = AppUsers::select('email', 'password')
            ->where('email', $email)
            ->count();
            //dd($userCount);

            // on teste les résultats de la requête
            if ($userCount == 0) {
                
                // si aucun EMAIL trouvé en bdd, on stocke un message d'erreur
                $msg = 'identifiant incorrect';

                // sinon on teste si le hash présent en bdd colle avec le password en clair
                // password_verify($password, $hash) renvoie true si le hash correspond au password
            } elseif ($userCount == 1) {
                
                // on lit en base les infos de l'user dont l'email a été trouvé
                $userInfo = AppUsers::select('id', 'email', 'password')
                ->where('email', $email)
                ->get();
                // on récupère le hash du password présent en bdd
                $passwordHashBdd = $userInfo[0]->password;
                //dd($passwordHashBdd);
                
                // on teste le hash
                if(password_verify($passwordClair, $passwordHashBdd)) {
                $success = true;
                session_start();
                $_SESSION['userId'] = $userInfo[0]->id;
                //dd($_SESSION['userId']);
                $msg = '';
                //dd($success);
                } else {
                    $msg = 'mot de passe incorrect';
                }
            }
        }

        $jsonArray = [
            'success' => $success,
            'msg' => $msg
        ];

        // on retourne un json
        return response()->json($jsonArray);
    }

    /**
     * méthode en POST associée au endpoint /signup
     * traite le formulaire d'inscription
     *
     * @param Request $request
     * @return 
     */
    public function signupPost(Request $request)
    {
        // on récupère les infos en POST du form d'inscription
        $email = $request->input('email');
        //dump($email);
        $passwordClair = $request->input('password');
        //dump($passwordClair);
        $passwordClairConfirm = $request->input('password_confirm');
        //dump($passwordClairConfirm);
        $firstname = $request->input('firstname');
        //dump($firstname);
        $lastname = $request->input('lastname');
        //dump($lastname);

        /*
        **************************
        * CONTROLE INTEGRITE FORM
        **************************
        */

        $msg ='';
        $success = false;
        // Si au moins un champ est vide	
        if(empty($email) || empty($passwordClair) || empty($firstname) || empty($lastname)) {
            $msg = 'Les champs du formulaire ne peuvent pas être vides';
            //exit($msg);
        } else { // Si les champs sont tous remplis
            //echo 'chp tous remplis';
            // on lit en base les infos de l'user dont l'email a été donné
            $userCount = AppUsers::select('email')
            ->where('email', $email)
            ->count();
            //dd($userCount);

            // on teste les résultats de la requête
            // si l'email est déjà utilisé
            if ($userCount > 0) {
                
                // si EMAIL trouvé en bdd, on stocke un message d'erreur
                $msg = 'Adresse email déjà associée à un compte';
                //exit($msg);

                // sinon on teste si le hash présent en bdd colle avec le password en clair
                // password_verify($password, $hash) renvoie true si le hash correspond au password
            } else {
                // on compare les deux saisies du password
                if(strcmp($passwordClair, $passwordClairConfirm) !== 0) {
                    // si les saisies sont différentes, on stocke un message d'erreur
                    $msg = 'Les saisies des mots de passe doivent être identiques';
                    //exit($msg);
                } else {
                    // les saisies des deux mots de passe sont identiques
                    // on hache le mot de passe
                    $passwordHash = password_hash($passwordClair, PASSWORD_DEFAULT);
                    //dd($passwordHash);
                    /*
                    **************************
                    * AJOUT DU USER EN BASE
                    **************************
                    */

                    $user = new AppUsers();

                    // on stocke les infos en bdd
                    // $user->email = request('email');
                    $user->email = $email;
                    $user->password = $passwordHash;
                    $user->firstname = $firstname;
                    $user->lastname = $lastname;
                    $user->status = 1;
                    //dd($user);
                    $user->save();
                    
                    $success = true;
                    $msg = '';

                    session_start();
                    $_SESSION['userId'] = $user->id;
                    //exit($success);
                }
            }
        }

        $jsonArray = [
            'success' => $success,
            'msg' => $msg
        ];

        // on retourne un json
        return response()->json($jsonArray);
    }

}