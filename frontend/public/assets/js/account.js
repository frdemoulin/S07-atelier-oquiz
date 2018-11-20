var app = {
    uri: '',
  
    init: function() {
        app.uri = $('.container').data('uri');
        app.recoverUserInfo();
    },

    recoverUserInfo: function () {
        var jqxhr = $.ajax({
          url: 'http://localhost/S07/S07-atelier-oquiz/backend/public/account',
          method: 'GET', // La méthode HTTP souhaité pour l'appel Ajax (GET ou POST)
          dataType: 'json', // Le type de données attendu en réponse (text, html, xml, json)
        });
        // Je déclare la méthode done, celle-ci sera executée si la réponse est satisfaisante
        jqxhr.done(function (response) {
            console.log(response);
            if(response.success) 
            {
                $('h2').html(response.firstname +' '+ response.lastname);
            }
            else 
            {
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