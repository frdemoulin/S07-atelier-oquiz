# Premier projet avec Lumen

Le sujet de ce petit projet sera le challenge _Videogame_ effectué en saison 4 ([screenshot](docs/original.png)).

Cependant, nous allons le découper en 2 pages distinctes :
- accueil avec la liste des jeux vidéo
- admin avec ajout d'un jeu vidéo

:warning: Il y a 2 dépôts pour ce support :
- l'énoncé
- le dépôt _s07-e01-lumen-videogame_ 
    - généré via _GitHub Classroom_
    - à **ne pas cloner**

## 1 - Installation de Lumen

Bah oui, parce que si on veut utiliser _Lumen_, il vaut mieux l'avoir installé :wink:

- [Documentation installation](https://lumen.laravel.com/docs/5.4/installation)
- Installation via **composer create-project**
    - :warning: création d'un nouveau projet/dossier
    - ouvrir un terminal :computer:
    - se placer dans le répertoire de la saison (comme pour _Git_ avant un "clone")
    - `composer create-project --prefer-dist laravel/lumen S07-E01-lumen-videogame`
    - il va prendre son temps, mais composer va bien créer un dossier et y placer les sources de _Lumen_ et de tous les microservices qui vont avec
    - se déplacer dans le sous-répertoire créé
    - ouvrir son éditeur préféré :heart_eyes:
- Vérification de l'installation
    - ouvrir le sous-dossier `/S07-E01-lumen-videogame/public` dans votre navigateur préféré
    - le texte `Lumen (5.xxxx)` doit s'afficher (xxx car la version peut changer dans le temps)
- Ajout dans le dépôt sur GitHub grâce aux lignes de codes suggérées sur la page GitHub du repo

<details><summary>Lignes de commandes suggérées sur la page GitHub</summary>
    
**…or create a new repository on the command line**
    
```
echo "# S06-E04-atelier-jquery-okanban" >> README.md
git init
git add README.md
git commit -m "first commit"
git remote add origin git@github.com:O-clock-XXXXXX/xx_xxxx.git
git push -u origin master
```
</details>

## 2 - Configuration

Comme tout framework, il a besoin des informations spécifiques à notre projet ou à notre machine. Mais là, on peut aussi lui demander des _Services_ supplémentaires :muscle:

### `.env`

- c'est notre fichier de config
- [Documentation .env](https://lumen.laravel.com/docs/5.4/configuration#environment-configuration)
- renseigner `APP_KEY` afin de crypter certaines données stockées par _Lumen_

### Activer `Facades`

- ouvrir le fichier `bootstrap/app.php`
- décommenter la ligne `$app->withFacades();`
- [Documentation Laravel pour `Facades`](https://laravel.com/docs/5.7/facades)

`Facades` permet d'avoir une interface _statique_ pour les services de l'application.

<details>

**Sans `Facades`**

```php
$results = app('db')->select("SELECT * FROM users");
```

**Avec `Facades`**

```php
$results = DB::select("SELECT * FROM users");
```

</details>

### Activer `Eloquent`

- ouvrir le fichier `bootstrap/app.php`
- décommenter la ligne `$app->withEloquent();`
- [Documentation Laravel pour `Eloquent`](https://laravel.com/docs/5.7/eloquent)
- c'est un ORM
    - simplifie les requêtes à la DB
    - CRUD déjà implémentés
    - Seuls les Models sont à créer (et sans propriétés), Eloquent s'occupe du reste :tada:
    - Team fainéants :sunglasses:

<details>

**Récupérer tous les users**

```php
$usersList = Users::all();
```

**Récupérer le user avec l'id 4**

```php
$user4 = User::find(4);
```

**Modifier et sauvegarder le user #4**

```php
$user4 = User::find(4);
$user4->username = 'perceval';
$user4->save();
```

</details>

## 3 - Page(s)

C'est cool, maintenant, on va créer notre première page.  
Pour cela, on garde les mêmes concepts de **routes**, **Controllers** et de **views**

### 3.1 Routes

- [Documentation routing](https://lumen.laravel.com/docs/5.4/routing)
- les routes sont définies dans le fichier `routes/web.php`
- utilisation d'une `Closure` comme en Javascript
    - `use ($router)` permet "d'importer" la variable dans la fonction
- dans la `Closure`, modifier le `return` pour retourner `"Hello World!"`
    - dans le navigateur, la page d'accueil affiche désormais _Hello World!_
- ajouter une route `/toto-route` et afficher "Bonjour Toto" dans un `<h1>`

Routes :heavy_check_mark: pour l'HTML, c'est :poop:

### 3.2 Views

- [Documentation views](https://lumen.laravel.com/docs/5.1/views)
- on utilise une simple fonction `view`
    - 1er paramètre : le nom du fichier de template/view
    - 2e paramètre : un tableau associatif des données à fournir
- modifier la route "toto" pour utiliser une view
    - fichier de view `mavuetoto`
    - à créer dans `ressources/views`
    - => `ressources/views/mavuetoto.php`
- vérifier dans le navigateur que l'affichage reste le même

#### Avec une donnée

- dans l'appel à la fonction `view`
    - spécifier un second paramètre : `['name' => 'Toto']`
    - la variable `$name` sera alors disponible dans les views et aura comme valeur `"Toto"`
- modifier la view pour utiliser cette variable `$name`
- vérifier dans le navigateur que l'affichage reste le même

Routes :heavy_check_mark:  
Views :heavy_check_mark:  
Il nous manque les `Controllers`

### 3.3 Controllers

- [Documentation controllers](https://lumen.laravel.com/docs/5.4/controllers)
- doit être dans le namespace `App\Http\Controllers`
- donc se situe où ? :smiling_imp:
- il hérite de `Controller`, notre Controller de base
    - qui hérite lui-même de `Laravel\Lumen\Routing\Controller` le Controller central de Lumen

#### MainController

- créer un `MainController`
- ajouter une méthode `totoAction` pour la route "toto"
- modifier la définition de la route pour qu'elle utilise notre méthode de Controller
- tant qu'on y est, on va nommer la route "toto", pour pouvoir générer l'URL de la route + facilement
    - [Documentation named routes](https://lumen.laravel.com/docs/5.4/routing#named-routes)
- ajouter un lien dans le view de la page "toto", vers la page "toto"
    - oui, c'est redondant, mais au moins on va tester la génération d'URL à partir du nom d'une route
    - code source du navigateur pour vérifier

Routes :heavy_check_mark:  
Views :heavy_check_mark:  
Controllers :heavy_check_mark:  

## 4 - Request

- [Documentation Request](https://lumen.laravel.com/docs/5.4/requests)
- objet représentant la requête HTTP effectuée au serveur Web
- :bulb: contient les données envoyées en GET et en POST
- accéder à l'URL `/toto-route?chiffre=42`
    - ajouter l'objet $request de type `Illuminate\Http\Request` en paramètre de la méthode de Controller `todoAction`
    - appeler la méthode `input` pour récupérer la donnée en GET (tester avec `print_r` ou `var_dump`)
    - retirer le debug et passer la donnée à la view pour l'afficher dans la page
- d'autres infos sont disponibles dans l'objet `Request` de Lumen

## 5 - Response

- [Documentation Response](https://lumen.laravel.com/docs/5.4/responses)
- permet de construire la réponse HTTP
    - 404 Not Found
    - 403 Forbidden
    - 200 OK
    - `Content-type: json` + JSON
    - redirection

### 5.1 JSON

- dans la méthode de la page "toto"
- si le chiffre est égal à 51
    - retourner le JSON suivant

<details>

```js
{
    name: 'Pastis',
    type: 'Alcohol',
    origin: 'Marseille'
}
```

</details>

### 5.2 Redirection

- dans la méthode de la page "toto"
- si le chiffre est égal à 5
    - rediriger vers la route "toto"
    - on sera renvoyé vers la même page, mais sans le paramètre d'URL _chiffre_

## 6 - Errors & Logging

- [Documentation](https://lumen.laravel.com/docs/5.4/errors)
- le + important : `APP_DEBUG=true` dans le fichier `.env`
- raccourcis pour les erreurs HTTP 404 & 403
    - https://lumen.laravel.com/docs/5.4/errors#http-exceptions
- logguer facilement des erreurs, notice, infos, etc.
    - https://lumen.laravel.com/docs/5.4/errors#logging
    - `Log::error($error);`
    - `Log::notice($error);`
    - `Log::info($error);`
