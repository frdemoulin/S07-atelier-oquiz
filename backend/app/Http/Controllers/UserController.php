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
     * @param Request $request
     * @param string $id
     * @return json
     */
    public function profile(Request $request, $id)
    {
        
        // on récupère les infos en GET
        //$id = $request->input('id');

        // on lit en base les infos de l'user
        $userInfo = AppUsers::select('firstname', 'lastname')
                                ->where('id', $id)
                                ->get();

        //dd($userInfo);

        //$firstname = $request->input('firstname');
        //$lastname = $request->input('lastname');


        // on redirige vers la page du compte utilisateur
        return response()->json($userInfo);
    }

    /**
     * méthode en GET associée au endpoint /logout
     * traite la déconnexion d'un utilisateur
     *
     * @param Request $request
     * @param string $id
     * @return json
     */
    // public function logout(Request $request)
    // {
    //     // on ouvre la session
    //     session_start();
    //     // on efface les données de session
    //     session_unset();
    //     // on détruit la session
    //     session_destroy();
    //     // on redirige vers le formulaire de connexion
    //     header('Location: connexion.php');
    //     // on stoppe le script courant
    //     exit();
    // }

    // /**
    //  * méthode en POST associée au endpoint /signin
    //  * traite le formulaire de connexion
    //  *
    //  * @param Request $request
    //  * @return 
    //  */
    // public function signinPost(Request $request)
    // {
    //     // on récupère les infos en POST du form de connexion
    //     $email = $request->input('email');
    //     $password = $request->input('password');

    //     // contrôle d'intégrité des données

    //     // on redirige vers la page du compte utilisateur
    //     return redirect()->route('account');
    // }

    // /**
    //  * méthode en POST associée au endpoint /signup
    //  * traite le formulaire d'inscription
    //  *
    //  * @param Request $request
    //  * @return 
    //  */
    // public function signupPost(Request $request)
    // {
    //     // on récupère les infos en POST du form d'inscription
    //     $email = $request->input('email');
    //     $password = $request->input('password');
    //     $firstname = $request->input('firstname');
    //     $lastname = $request->input('lastname');

    //     // contrôle d'intégrité des données

    //     $user = new AppUsers();

    //     // on stocke les infos en bdd
    //     // $user->email = request('email');
    //     $user->email = $email;
    //     $user->password = $password;
    //     $user->firstname = $firstname;
    //     $user->lastname = $lastname;
    //     $user->save();

    //     // on redirige vers la page du compte utilisateur
    //     return redirect()->route('account');
    // }

}