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
     * méthode en GET associée au endpoint /account-validation
     * traite le lien de validation du formulaire d'inscription
     *
     * @param Request $request
     * @return view('accountvalidation')
     */

    /*
     *************************************************************************************
     * ETAPE 1 - Récupération des données de la requête HTTP via un objet de type Request
     *************************************************************************************
     */
    public function accountValidation(Request $req){

        // on initialise les variables à passer à la view accountvalidation 
        $msg = '';
        $success = false;

        /*
        *****************************************
        * ETAPE 2 - Récupération des param d'URL
        *****************************************
        */

        // on passe une valeur vide par défaut en second paramètre si le paramètre attendu n'est pas setté
        $id = $req->input('id', '');
        $token = $req->input('token', '');

        /*
        ***************************************
        * ETAPE 3 - Test du lien de validation
        ***************************************
        */

        // on récupère en base les infos de l'user dont l'id est en GET
        $user = AppUsers::where('id', $id)->first();
        
        //dd($user->token);

        // on teste le lien de validation
        if(strcmp($user->id, $id) != 0 || strcmp($user->token, $token) != 0){
            $msg = 'Le lien de validation de votre compte est invalide. Veuillez en générer un nouveau.';
        }
        
        if($user->status == 1) {
            $msg = 'Votre compte a déjà été activé. Merci de vous connecter';
        } else {
            // on active le compte de l'utilisateur en passant son status à 1 et en mettant son token à null
            $userUpdate = AppUsers::where('id', $id)->update([
                'status' => 1,
                'token' => NULL
            ]);
            
            // on récupère en base les infos de l'user dont l'id est en GET et qui vient d'être mis à jour
            // afin de les passer en session
            // on lit en base les infos de l'user dont l'email a été trouvé
            $userUpdated = AppUsers::select('app_users.id', 'email', 'password', 'firstname', 'lastname', 'roles_id', 'roles.name')
            ->where('app_users.id', $id)
            ->join('roles', 'roles.id', 'app_users.roles_id')
            ->first();

            //dd($userUpdate);

            // le procédure de validation du compte est terminée avec succès, on transmet l'info
            $success = true;
            $msg = '';

            // on stocke en session les infos de l'user
            $_SESSION['user'] = [
                'id' => $userUpdated->id,
                'firstname' => $userUpdated->firstname,
                'lastname' => $userUpdated->lastname,
                'role' => [
                    'id' => $userUpdated->roles_id,
                    'name' => $userUpdated->name
                ]
            ];

            //dd($_SESSION);
        }

        $jsonArray = [
            'success' => $success,
            'msg' => $msg
        ];

        return response()->json($jsonArray);
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
        /*
        ************************************************
        * ETAPE 1 - Récupération des valeurs des inputs
        ************************************************
        */

        // on récupère les infos en POST du form d'inscription
        // on passe une valeur vide par défaut en second paramètre si le paramètre attendu n'est pas setté
        $email = $request->input('email', '');
        //dump($email);
        $passwordClair = $request->input('password', '');
        //dump($passwordClair);
        $passwordClairConfirm = $request->input('password_confirm', '');
        //dump($passwordClairConfirm);
        $firstname = $request->input('firstname', '');
        //dump($firstname);
        $lastname = $request->input('lastname', '');
        //dump($lastname);
        $uri = $request->input('uri', '');
        //dump($uri);

        // on supprime les espaces éventuels en début et fin de chaîne
        $email = trim($email);
        $passwordClair = trim($passwordClair);
        $passwordClairConfirm = trim($passwordClairConfirm);
        $firstname = trim($firstname);
        $lastname = trim($lastname);

        /*
        ************************************************
        * ETAPE 2 - Contrôle d'intégrité des données
        ************************************************
        */

        $msg ='';
        $success = false;
        // Si au moins un champ est vide, on stocke un message d'erreur
        if(empty($email) || empty($passwordClair) || empty($passwordClairConfirm) || empty($firstname) || empty($lastname)) {
            $msg = 'Les champs du formulaire ne peuvent pas être vides';
            //exit($msg);
        } else { // Si les champs sont tous remplis
            // echo 'chp tous remplis';
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

            } else {
                // $userCount = 0, l'email n'a pas été trouvé en base
                // on compare les deux saisies du password
                if(strcmp($passwordClair, $passwordClairConfirm) !== 0) {
                    // si les saisies sont différentes, on stocke un message d'erreur
                    $msg = 'Les saisies des mots de passe doivent être identiques';
                    //exit($msg);
                } else {
                    // les saisies des deux mots de passe sont identiques
                    
                    /*
                    *********************************************
                    * ETAPE 3 - Enregistrement de l'user en base
                    *********************************************
                    */
                    
                    // les champs du signup sont valides
                    // on enregistre alors l'utilisateur en base

                    // on génère un jeton de validation (chaîne aléatoire de 64 caractères)
                    $token = bin2hex(random_bytes(32));
                    
                    // on hache le mot de passe
                    $passwordHash = password_hash($passwordClair, PASSWORD_DEFAULT);
                    //dd($passwordHash);

                    // on insère l'user en base avec un status de 0 (valeur par défaut)
                    // son status passera à 1 une fois l'inscription validée
                    // la méthode insertGetId() insère l'user en base et renvoie son id 
                    $newUserId = AppUsers::insertGetId([
                        'email' => $email,
                        'password' => $passwordHash,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'token' => $token
                    ]);
                    
                    //dd($newUser);

                    //$newUserId = $newUser[0]->id;
                    
                    /*
                    ******************************************************
                    * ETAPE 4 - Envoi d'un e-mail de validation du compte
                    ******************************************************
                    */

                    // on génère l'url de réinitialisation du mot de passe
                    // on récupère l'uri dynamiquement
                    $link = 'http://localhost' . $uri . '/validation?id='.$newUserId.'&token='.$token;
                    //dd($link);
                    // on envoie le mail de validation à l'aide de la fonction mail(arg1, arg2, arg3, arg4)
                    // fonction native mail(arg1, arg2, arg3, arg4)
                    // doc : http://php.net/manual/fr/function.mail.php
                    // arg1 : adresse e-mail
                    $to = $email;
                    // arg2 : sujet de l'e-mail
                    $emailSubject = 'Validation de votre compte oQuiz';
                    // arg3 : contenu de l'e-mail
                    $emailContent = '<html><head></head><body>Bonjour,<br/>Pour valider votre compte sur oQuiz, veuillez cliquer sur le lien ci-dessous :<br/> <a href="'.$link.'">Validation de votre compte</a><br/>Cordialement. L\'équipe d\'administration.</body></html>';
                    // arg4 optionnel : headers
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
                    mail($to, $emailSubject, $emailContent, $headers);
                    
                    $success = true;
                    $msg = '';
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
     * méthode en POST associée au endpoint /lost-password
     * traite le formulaire de demande de nouveau mot de passe
     *
     * @param Request $request
     * @return json
     */
    public function lostPassword(Request $req) {

        $msg = '';
        $success = false;

        // on récupère la valeur de l'input email et l'uri
        $email = $req->input('email', '');
        $uri = $req->input('uri', '');
        //dump($uri);

        // suppression des espaces
        $email = trim($email);

        /**
         * *************************
         * GESTION INTEGRITE DONNEES
         * *************************
         */

        // si l'adresse email est vide
        if(empty($email)){
            $msg = 'Veuillez renseigner une adresse e-mail';

            // si l'adresse email est remplie, on vérifie si son format est valide
            // http://php.net/manual/fr/function.filter-var.php & http://php.net/manual/fr/filter.filters.validate.php
        } elseif(filter_var($email, FILTER_VALIDATE_EMAIL) === false) { 
            $msg = 'L\'adresse e-mail saisie est invalide';
        } else {

            // l'email est valide, on vérifie sa présence en bdd
            $user = AppUsers::where('email', $email)->get()->first();
            //dd($user);
        
            if(is_null($user)){
                // l'email n'a pas été trouvée en base
                $msg = 'L\'adresse e-mail renseignée ne correspond à aucun membre';
            }
        }

        // si aucune erreur, alors on peut envoyer un mail de réinitialisation du mot de passe

        if(empty($msg)){
            $success = true;
            
            // on génère un jeton de réinitialisation du mot de passe (chaîne aléatoire de 64 caractères)
            $token = bin2hex(random_bytes(32));
            
            // on insère le token en base
            $update = AppUsers::where('email', $email)
                            ->update(['token' => $token]);
            
            // on lit les infos du user en base
            $userInfo = AppUsers::select('id', 'email', 'password', 'firstname', 'lastname')
            ->where('email', $email)
            ->get()
            ->first();

            //dd($userInfo);

            $id = $userInfo->id;
            $email = $userInfo->email;
            $firstname = $userInfo->firstname;
            $lastname = $userInfo->lastname;
            //dd($firstname);

            // on génère l'url de réinitialisation du mot de passe
            // on récupère l'uri dynamiquement
            $link = 'http://localhost' . $uri . '/reinitialisation?id='.$id.'&token='.$token;
            // on envoie le mail de réinitialisation de mot de passe à l'utilisateur
            // fonction native mail(arg1, arg2, arg3, arg4)
            // doc : http://php.net/manual/fr/function.mail.php
            // arg1 : adresse e-mail
            $to = $email;
            // arg2 : sujet de l'e-mail
            $emailSubject = 'Réinitialisation de votre mot de passe sur oQuiz';
            // arg3 : contenu de l'e-mail
            $emailContent = '<html><head></head><body>Bonjour ' . $firstname . ' ' . $lastname. ',<br/>Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien ci-dessous :<br/> <a href="'.$link.'">Réinitialisation de votre mot de passe</a><br/>Cordialement. L\'équipe d\'administration (team #FC)</body></html>';
            // arg4 optionnel : headers
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            mail($to, $emailSubject, $emailContent, $headers);
        }
        $jsonArray = [
            'success' => $success,
            'msg' => $msg
        ];

        // on retourne un json
        return response()->json($jsonArray);
    }

    /**
     * méthode en GET associée au endpoint /reset-password
     * traite le formulaire de réinitialisation d'un nouveau mot de passe
     *
     * @param Request $request
     * @return json
     */

    public function resetPassword(Request $req) {
        
        $msg = '';
        $success = false;
        //dd($req);

        // on récupère les valeurs des paramètres d'URL
        $id = $req->input('id', '');
        //dump($id);
        $token = $req->input('token', '');
        //dump($token);
        $password = $req->input('password', '');
        $passwordConfirm = $req->input('password_confirm', '');

        // suppression des espaces
        $password = trim($password);
        $passwordConfirm = trim($passwordConfirm);

        /**
         * *************************
         * GESTION INTEGRITE DONNEES
         * *************************
         */

        // si le password est vide
        if(empty($password)){

            $msg = 'Vous devez saisir un mot de passe';

            // si la retape du password est vide
        } elseif(empty($passwordConfirm)) {
            
            $msg = 'Vous devez saisir à nouveau votre mot de passe';

            // sinon on compare les saisies des deux mots de passe
        } elseif(strcmp($password, $passwordConfirm) !== 0) {
            
            $msg = 'Les deux mots de passe doivent être identiques';
        
        } else {
            // les mots de passe saisis sont valides et coïncident
            $user = AppUsers::where([
                ['id', $id],
                ['token', $token]
                ])->get()->first();
            //dd($user);
            
            //dd(is_null($user));

            if(is_null($user) == true){
                // la correspondance id - token n'a pas été trouvée en base
                $msg = 'Le lien de réinitialisation du mot de passe n\'est pas valide';
            } else {
                // on hache le nouveau mot de passe
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                // on le stocke en bdd
                $update = AppUsers::where('id', $id)
                            ->update(['password' => $passwordHash]);
                
                $success = true;
                $msg = '';                
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