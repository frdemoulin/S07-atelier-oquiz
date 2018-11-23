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
     * méthode en GET associée au endpoint /signin
     * traite le formulaire de connexion
     * une fois connecté, on passe en session : id, firstname, lastname, roles_id
     *
     * @param Request $request
     * @return json
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

        // trim supprime les espace en debut et fin de chaine pour eviter un espace problematique lors de la saisie du mot de passe ou de l'email
        $email = trim($email);
        $passwordClair = trim($passwordClair);

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
            // echo 'pouet';
            // on cherche en base la présence de l'email donné dans le form de signin
            // count() compte le nombre d'entrées retournées
            $userCount = AppUsers::select('email', 'password')
                                ->where('email', $email)
                                ->count();
            //dd($userCount);

            // on teste les résultats de la requête
            // si $userCount = 0, alors l'email n'est pas présent en bdd
            if ($userCount == 0) {
                
                // si aucun EMAIL trouvé en bdd, on stocke un message d'erreur
                $msg = 'identifiant incorrect';

                // sinon on teste si le hash présent en bdd colle avec le password en clair
                // password_verify($password, $hash) renvoie true si le hash correspond au password
            } elseif ($userCount == 1) {
                
                // on lit en base les infos de l'user dont l'email a été trouvé
                $userInfo = AppUsers::select('app_users.id', 'email', 'password', 'firstname', 'lastname', 'roles_id', 'roles.name')
                ->where('email', $email)
                ->join('roles', 'roles.id', 'app_users.roles_id')
                ->get();
                //dd($userInfo);
                // on récupère le hash du password présent en bdd
                $passwordHashBdd = $userInfo[0]->password;
                //dd($passwordHashBdd);
                
                // on teste le hash
                if(password_verify($passwordClair, $passwordHashBdd)) {
                $success = true;
                
                // on stocke en session les infos de l'user
                $_SESSION['user'] = [
                    'id' => $userInfo[0]->id,
                    'firstname' => $userInfo[0]->firstname,
                    'lastname' => $userInfo[0]->lastname,
                    'role' => [
                        'id' => $userInfo[0]->roles_id,
                        'name' => $userInfo[0]->name
                    ]
                ];

                //dd($_SESSION);
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
     * @return json
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

        // trim des datas du form
        $email = trim($email);
        $passwordClair = trim($passwordClair);
        $passwordClairConfirm = trim($passwordClairConfirm);
        $firstname = trim($firstname);
        $lastname = trim($lastname);

        /*
        **************************
        * CONTROLE INTEGRITE FORM
        **************************
        */

        $msg ='';
        $success = false;
        // Si au moins un champ est vide, on stocke un message d'erreur
        if(empty($email) || empty($passwordClair) || empty($passwordClairConfirm) || empty($firstname) || empty($lastname)) {
            $msg = 'Les champs du formulaire ne peuvent pas être vides';
            //exit($msg);
        } else { // Si les champs sont tous remplis
            //echo 'chp tous remplis';
            // on cherche en base la présence de l'email donné dans le form de signup
            // count() compte le nombre d'entrées retournées
            $userCount = AppUsers::select('email')
            ->where('email', $email)
            ->count();
            //dd($userCount);

            // on teste les résultats de la requête
            // si l'email est déjà utilisé, $userCount = 1 et on entre dans la condition
            if ($userCount > 0) {
                
                // si EMAIL trouvé en bdd, on stocke un message d'erreur
                $msg = 'Adresse email déjà associée à un compte';
                //exit($msg);

                // sinon on teste si le hash présent en bdd colle avec le password en clair
                // password_verify($password, $hash) renvoie true si le hash correspond au password
            } else {
                // $userCount = 0
                // on compare les deux saisies du password
                if(strcmp($passwordClair, $passwordClairConfirm) !== 0) {
                    // si les saisies sont différentes, on stocke un message d'erreur
                    $msg = 'Les saisies des mots de passe doivent être identiques';
                    //exit($msg);
                } else {
                    // les saisies des deux mots de passe sont identiques
                    
                    /*
                    *******************************
                    * ENVOI MAIL VALIDATION COMPTE
                    *******************************
                    */
                    
                    // les champs du signup sont valides
                    // on envoie à l'utilisateur un mail de validation de son compte

                    // on génère un jeton de validation (chaîne aléatoire de 64 caractères)
                    $token = bin2hex(random_bytes(32));
                    
                    // on hache alors le mot de passe
                    $passwordHash = password_hash($passwordClair, PASSWORD_DEFAULT);
                    //dd($passwordHash);

                    // on insère l'user en base avec un status de 0 (valeur par défaut)
                    // son status passera à 1 une fois l'inscription validée
                    // la méthode insertGetId() insère l'user en base et renvoie son id 
                    $newUser = AppUsers::insertGetId([
                        'email' => $email,
                        'password' => $passwordHash,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'token' => $token
                    ]);
                    
                    dd($newUser);

                    $newUserId = $newUser[0]->id;

                    // on génère l'url de réinitialisation du mot de passe
                    $link = 'http://frederic-demoulin.vpnuser.oclock.io/S07/blog/public/reset-password?id='.$newUserId.'&token='.$token;
                    // on envoie le mail de réinitialisation de mot de passe à l'utilisateur
                    // fonction native mail(arg1, arg2, arg3, arg4)
                    // doc : http://php.net/manual/fr/function.mail.php
                    // arg1 : adresse e-mail
                    $to = $user['email'];
                    // arg2 : sujet de l'e-mail
                    $emailSubject = 'Réinitialisation de votre mot de passe';
                    // arg3 : contenu de l'e-mail
                    $emailContent = '<html><head></head><body>Bonjour ' . $user['login'] . ',<br/>Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien ci-dessous :<br/>' . $link . '</body></html>';
                    // arg4 optionnel : headers
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
                    mail($to, $emailSubject, $emailContent, $headers);
                    
                    $success = true;
                    $msg = '';

                    // on ouvre alors une session
                    //$_SESSION['userId'] = $user->id;
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