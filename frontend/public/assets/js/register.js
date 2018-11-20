var app = {
    uri: '',
  
    init: function() {
      // je récupère ma base uri
      app.uri = $('.container').data('uri');
      $('form').on('submit', app.handleCheckForm);
    },
  
    handleCheckForm: function(evt) {
      evt.preventDefault();
      app.clearError();
      // je récupère les contenu des formulaire en retirant les espaces
      var data = {
        'lastname': $.trim($($(evt.target).find('.last-name')).val()),
        'firstname': $.trim($($(evt.target).find('.first-name')).val()),
        'email': $.trim($($(evt.target).find('.email')).val()),
        'password': $.trim($($(evt.target).find('.password')).val()),
        'password_confirm': $.trim($($(evt.target).find('.password-confirm')).val())
      };
      // par défaut je considère que des valeurs on été mis dans chaque input
      var notEmpty = true;

      // je vérifie qu'aucun input n'est vide
      for ( var index in data) 
      {
        // si vide j'affiche un message d'erreur
        // et je dis que notEmpty = false;
        if(data[index] == '') 
        {
          var textError = 'Vous ne pouvez pas laisser le champ '+ index +' vide.';
          var error = $('<div>').addClass('mx-auto my-2 border text-light bg-danger rounded p-2 error').html(textError);
          
          // j'ajoute le message au formulaire
          error.appendTo(evt.target);
          notEmpty = false;
        }
        }
        // si notEmpty = true, alors aucun input n'était vide
        if (notEmpty) 
        {
            // si les deux mot de passe correspondent bien
            if(data['password'] === data['password_confirm']) 
            {
                // je lance la requête vers le back
                app.dataRequest(data);
            }
            // sinon j'affiche un message
            else 
            {
                var textError = 'Les mots de passe doivent être identiques';
                var error = $('<div>').addClass('mx-auto my-2 border text-light bg-danger rounded p-2 error').html(textError);
        
                // j'ajoute le message au formulaire
                error.appendTo(evt.target);
            }
        }
    },
    dataRequest: function(dataValue) {
      var jqxhr = $.ajax({
        url: 'http://localhost/S07/S07-atelier-oquiz/backend/public/signup', 
        method: 'POST',
        dataType: 'json',
        data: {
          lastname:  dataValue['lastname'],
          firstname:  dataValue['firstname'],
          email: dataValue['email'],
          password: dataValue['password'],
          password_confirm:  dataValue['password_confirm']
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
          app.displayError(response.msg);
        }
      });
      // Je déclare la méthode fail, celle-ci sera executée si la réponse est insatisfaisante
      jqxhr.fail(function () {
        alert('Requête échouée');
      });
    },
  
    displaySuccess: function() {
      // je cache le formulaires
      $('form').addClass('d-none');
      // j'ajoute un message de redirection vers la page mon compte
      var container = $('.container');
      var div = $('<div>').addClass('row mx-auto col-11 my-3 border bg-light rounded py-2');
      var url = 'http://localhost'+ app.uri +'/mon-compte';
      var a = $('<a>').html('Mon compte.').attr('href', url).addClass('alert-link');
      var text = 'Vous êtes à présent connecté.'
      text += '<br/>Vous pouvez désormais acceder à votre page : ';
      var p = $('<p>').html(text);
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
      $(liAccount).appendTo($('ul.nav-pills'));
      $(liDisconnect).appendTo($('ul.nav-pills'));
    },
  
    displayError: function(msg) {
    // j'affiche le message d'erreur en dessous du bouton Connexion
    var form = $('form');
    var error = $('<div>').addClass('mx-auto my-2 border text-light bg-danger rounded p-2 error').html(msg);
    
    error.appendTo(form);
    },

    clearError: function() {
        // s'il y a bien des messages d'erreur
        if(typeof $('.error') !== 'undefined')
        {
          $('.error').remove();
        }
      },
  };
  $(app.init);