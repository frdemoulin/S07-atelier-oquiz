var app = {
    uri: '',
    uriBack: '',
  
    init: function() {
      // je récupère ma base uri
      app.uri = $('.container').data('uri');
      app.uriBack = $('.container').data('back');
      app.recoverData();
    },
    recoverData: function() {

        var id = $('.validate').data('id');
        var token = $('.validate').data('token');

        if (id !== '' && token !== '')
        {
            app.verifyAccount(id, token)
        }
    },
    verifyAccount: function(idUser, tokenUser) {
        var jqxhr = $.ajax({
          url: 'http://localhost'+ app.uriBack +'/account-validation', 
          method: 'GET',
          dataType: 'json',
          data: {
            id:  idUser,
            token: tokenUser,
          }
        });
        // Je déclare la méthode done, celle-ci sera executée si la réponse est satisfaisante
        jqxhr.done(function (response) {
          // si success = true
          if (response.success) 
          {
            // j'affiche un message de succes
            app.displaySuccess();
          }
          // sinon 
          else 
          {
            // j'affiche le message d'erreur de l'index msg
            $('.msg').html(response.msg).removeClass('bg-light').addClass('bg-warning text-center');
          }
        });
        // Je déclare la méthode fail, celle-ci sera executée si la réponse est insatisfaisante
        jqxhr.fail(function () {
          alert('Requête échouée');
        });
      },

    displaySuccess: function() {
        $('.msg').html('Vous êtes bien enregistré. <br/>Vous pouvez désormais jouer aux quizz ! Amusez vous bien.');

        // je cache les deux derniers liens de ma navbar (connexion et inscription)
        var allLi = $('ul.nav-pills li');
        $(allLi[1]).addClass('d-none');
        $(allLi[2]).addClass('d-none');
        // créer le lien déconnexion
        var liDisconnect = '<li class="nav-item">\
        <a class="nav-link text-blue" href="http://localhost'+ app.uri +'/connexion?disconnect=1">Deconnexion</a>\
        </li>';
        // puis l'ajoute au dom
        $(liDisconnect).appendTo($('ul.nav-pills'));
    },
}; 
$(app.init);