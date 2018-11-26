var app = {
    uri: '',
    uriBack: '',
  
    init: function() {
      // je récupère ma base uri
      app.uri = $('.container').data('uri');
      app.uriBack = $('.container').data('back');
      $('form').on('submit', app.handleCheckForm);
    },
  
    handleCheckForm: function(evt) {
      evt.preventDefault();
      app.clearError();
      // je récupère les contenu du formulaire en retirant les espaces
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
        else 
        {
          var textError = 'Vous ne pouvez pas laisser de champ vide.';
          var emptyError = $('<div>').addClass('mx-auto my-2 border text-light bg-danger rounded p-2 error').html(textError);
          emptyError.appendTo(evt.target);
        }
    },
    dataRequest: function(dataValue) {
      var jqxhr = $.ajax({
        url: 'http://localhost'+ app.uriBack +'/signup', 
        method: 'POST',
        dataType: 'json',
        data: {
          lastname:  dataValue['lastname'],
          firstname:  dataValue['firstname'],
          email: dataValue['email'],
          password: dataValue['password'],
          password_confirm:  dataValue['password_confirm'],
          uri: app.uri
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
          // j'affiche le message d'erreur en dessous du bouton Connexion
      var form = $('form');
      var confirm = $('<div>').addClass('mx-auto my-2 border text-light bg-info rounded p-2 error').html('Vous avez reçu un mail pour confirmer votre compte');
      
      confirm.appendTo(form);
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