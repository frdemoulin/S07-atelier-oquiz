var app = {
    uri: '',
  
    init: function() {
        // je récupère l'uri
        app.uri = $('.container').data('uri');
        app.recoverUserInfo();
    },

    recoverUserInfo: function () {
        var jqxhr = $.ajax({
          url: 'http://localhost/S07/S07-atelier-oquiz/backend/public/account',
          method: 'GET', 
          dataType: 'json',
        });
        // Je déclare la méthode done, celle-ci sera executée si la réponse est satisfaisante
        jqxhr.done(function (response) {
            if(response.success) 
            {
                // j'ajoute le nom + prénom du user dans le h2
                $('h2').html(response.firstname +' '+ response.lastname);
            }
            else 
            {
                // j'ajoute le message d'erreur dans le h2
                $('h2').html(response.msg);
            }
        });
        // Je déclare la méthode fail, celle-ci sera executée si la réponse est insatisfaisante
        jqxhr.fail(function () {
          alert('Requête échouée');
        });
      },
    
};
$(app.init);