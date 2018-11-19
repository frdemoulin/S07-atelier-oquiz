<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
 Pour chaque table de ma BDD = 1 model. 
 Je dois creer mes model à la racine du dossier app. 
 Il existe deja un model dédiés aux utilisateurs qui peux nous servir d'exemple
*/

Class Levels extends Model {

    /*
     Pour que je sache a quelle table ma class Model doit faire reference je peux lui indiquer
     explicitement avec la propriété $table
    */

    // By convention, the "snake case", plural name of the class will be used as the table name unless another name is explicitly specified
    protected $table = 'levels';

    /*
     Par defaut, à l'insertion en DB Lumen / Eloquent va essayer d'enregistrer dans une table qui est censé contenir 2 champs supplementaire
     updated_at & created_at (ce qui est une bonne pratique).

     Cependant, dans certains cas et dans notre exemple nous n'avons pas ces deux colonnes. je vais donc explicitement les desactiver avec  public $timestamps = false; 
    */
    public $timestamps = false;
}
