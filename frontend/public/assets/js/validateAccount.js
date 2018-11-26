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
        console.log('verify');

        var id = $('.validate').data('id');
        var token = $('.validate').data('token');

        console.log(id);
        console.log(token);

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
            $('p').html(response.msg);
          }
        });
        // Je déclare la méthode fail, celle-ci sera executée si la réponse est insatisfaisante
        jqxhr.fail(function () {
          alert('Requête échouée');
        });
      },

    displaySuccess: function() {
        // je cible ma div validate
        var container = $('.validate');
        // création d'une div qui contiendra le message
        var div = $('<div>').addClass('row mx-auto col-11 my-3 border bg-light rounded py-2');
        // assemblage de l'url
        var url = 'http://localhost'+ app.uri +'/mon-compte';
        // création du lien de redirection
        var a = $('<a>').html('Mon compte.').attr('href', url).addClass('alert-link');
        // message
        var text = 'Vous êtes bien enregistré. <br/>Vous pouvez désormais acceder à votre page : ';
        var p = $('<p>').html(text);
        // ajout des lien et texte dans la div puis dans la div validate
        a.appendTo(p);
        p.appendTo(div);
        div.appendTo(container);

        // je change les deux derniers liens de ma navbar (connexion et inscription)
        var allLi = $('ul.nav-pills li');
        $(allLi[1]).addClass('d-none');
        $(allLi[2]).addClass('d-none');
        var liAccount = '<li class="nav-item">\
        <a class="nav-link text-blue" href="http://localhost'+ app.uri+'/mon-compte">Mon compte</a>\
        </li>';
        var liDisconnect = '<li class="nav-item">\
        <a class="nav-link text-blue" href="http://localhost'+ app.uri +'/connexion?disconnect=1">Deconnexion</a>\
        </li>';
        // et les ajoutes au dom
        $(liAccount).appendTo($('ul.nav-pills'));
        $(liDisconnect).appendTo($('ul.nav-pills'));
    },
}; 
$(app.init);