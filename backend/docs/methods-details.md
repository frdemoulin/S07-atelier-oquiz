# Méthodes phares du framework Lumen

## 1. Routes

[Doc routing](https://lumen.laravel.com/docs/5.4/routing)

Les routes sont définies dans le fichier `routes/web.php`. Il y a possibilité d'utiliser une `Closure` (fonction anonyme) comme en Javascript mais mauvaise pratique (dixit Claire)

**Définition d'une route :** instance de l'objet `$router` (association url + controller + method associés à un nom de route)

**Syntaxe type de définition d'une route :**

```
$router->methodeHttp('/endpoint', [
    'as' => 'nomDeLaRoute',
    'uses' => 'Controller@method'
]);
```

* `methodeHttp` désigne la méthode HTTP associée à la route (GET, POST, PUT, DELETE, etc)

* La clé `'as'` est associée au nom de la route

* La clé `'uses'` est associée à une string comportant le contrôleur et la méthode associés à la route (c'est Lumen qui se charge de découper `Controller@method` sur le arobase, analogue du # avec AltoRouter)

## 2. Views

[Doc views](https://lumen.laravel.com/docs/5.1/views)

On utilise la fonction `view(param1, param2)` :
- `param1` : nom du fichier de template/view
- `param2` (optionnel) : tableau associatif des données à fournir à la view

**Syntaxe sans transmission de données à la view :** `view('nomDeLaView');`

### Avec transmission de données

La fonction `view` prend un second paramètre optionnel qui est un tableau associatif des données à transmettre à la view : `['cle' => 'valeur']`

La variable `$cle` sera alors disponible dans la view et aura comme valeur `"valeur"`

**Syntaxe type avec transmission de données à la view :**

```
view('nomDeLaView', [
            'clé1' => 'valeur',
            'clé2' => $variable
    ]);
```
Typiquement, on appelle la fonction `view` en return de la méthode associée au contrôleur gérant la route attachée à la view :

```
return view('nomDeLaView', ['clé' => 'valeur']);
```

## 3. Controllers

[Doc controllers](https://lumen.laravel.com/docs/5.4/controllers)

* On place le contrôleur dans le namespace `App\Http\Controllers`
* Il hérite de `Controller`, le Controller de base
* Qui hérite lui-même de `Laravel\Lumen\Routing\Controller` le Controller central de Lumen

/!\ Lumen ne fournit pas de contrôleur gérant les views (bye bye Templator !)

### MainController

* On crée un `MainController` dans le dossier `app\Http\Controllers`
* On le place donc dans le namespace `App\Http\Controllers;`
* `Maincontroller` est une classe fille de `CoreController` : `class MainController extends Controller`
* On y code les méthodes associées aux routes
  
## 4. Request

[Doc Request](https://lumen.laravel.com/docs/5.4/requests)

* `Request` est un objet spécial de Lumen représentant la requête HTTP effectuée au serveur web
* Il contient les données envoyées en GET ou en POST via la route en question
* En effet, si on type hint une variable `$request` avec la classe `Request`, le framework sera capable de remplir cet objet avec les données HTTP envoyées
* De ce fait, `Request` permet d'éviter de tester si on est en `$_GET` ou en `$_POST` : il acheminera les datas au bon endroit dans son objet
* Pour utiliser / récupérer l'objet `Request`, on doit obligatoirement importer cette classe Lumen (dans le `MainController` par exemple) : `use Illuminate\Http\Request;`

/!\ De nombreuses infos sont dispo dans l'objet `$request`, on veillera à le dumper sans retenue !

* La méthode `input()` de l'objet `$request` permet de récupérer les données passées en GET ou en POST. Ces données se trouvent à la clé `query` de l'objet `$request`

Exemple : dans l'URL en GET si l'on passe le paramètre ?chiffre=valeur, alors on retrouve dans $request à la clé query : 'chiffre' => valeur.

**Syntaxe type de récupération de données HTTP :** `$request->input('nomDuParametre')` 

```
// exemple de méthode associée au controller défini dans le routing
    public function showToto(Request $request)
    {
        //dump($request);
        // on récupère la data chiffre passée en GET ?chiffre=...
        $chiffre = $request->input('chiffre');
        // l'index input se voit dans le champ query du dump

        return view('mavuetoto', [
            'name' => 'SuperNom',
            'chiffre' => $chiffre
        ]);
    }
```

## 5. Response

* [Doc Response](https://lumen.laravel.com/docs/5.4/responses)
* L'objet `response` permet de construire la réponse HTTP :
    - 404 Not Found
    - 403 Forbidden
    - 200 OK
    - `Content-type: json` + JSON
    - redirection

La fonction `response()->json()` est équivalente au `json_encode`. L'objet `response` peut avoir plusieurs utilités

### 5.1 JSON

[Doc JSON](https://lumen.laravel.com/docs/5.7/responses#other-response-types)

On peut retourner du json mais rien n'empêche de renvoyer un autre format, un autre status code (401 par ex) ou encore modifier les headers.

On peut retourner des données au format json sans pour autant afficher la viex :

```
return response()->json([
        'name' => 'Pastis'
    ]);
```
### 5.2 Redirection

[Doc redirects](https://lumen.laravel.com/docs/5.7/responses#redirects)

On utilise l'objet `redirect` pour effectuer une redirection. Dans le cas d'une méthode POST associée à une route, on ne fait pas appel à la méthode `view()` dans le return car le but d'une page en POST n'est pas d'afficher une vue mais d'enregistrer des résultats en bdd. La pratique courante est de rediriger dans le return vers la methode qui permettra l'affichage des données. De ce fait, on va effectuer une redirection mais en plus sur le nom de la route concernée. Cela permmetra si on change l'url dans les routes de ne pas être impacté. Ce nom de route est present dans la definition d'une route au parametre `'as'`.
Une fois trouvée, il arrive automatiquement à détecter qu'elle est l'url associée sur laquelle rediriger.

```
return redirect()->route('admin');
```

## 6. Errors & Logging

- [Doc](https://lumen.laravel.com/docs/5.4/errors)
- le + important : `APP_DEBUG=true` dans le fichier `.env`
- raccourcis pour les erreurs HTTP 404 & 403
    - https://lumen.laravel.com/docs/5.4/errors#http-exceptions
- logguer facilement des erreurs, notice, infos, etc.
    - https://lumen.laravel.com/docs/5.4/errors#logging
    - `Log::error($error);`
    - `Log::notice($error);`
    - `Log::info($error);`

## 7. Database

[Doc database](https://lumen.laravel.com/docs/5.7/database)

## 8. Models

Fichier Model.php dispo dans `vendor\illuminate\...\Model.php`

## 9. Artisan

Méthodes prévues pour maintenance et autre du projet (utilse en prod !)

Pour lancer le script PHP artisan : `php artisan`

Exemple : vider le cache d'application

`php artisan cache:clear` (avec `sudo` au besoin si permission denied)

