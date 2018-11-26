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
        console.log('check form');

        var email = $.trim($($(evt.target).find('.email')).val());

        $('.error').remove();

        if (email === '')
        {
            var div = $('<div>').html('Vous ne pouvez pas laisser le champ email vide').addClass('error bg-danger rounded p-2 mt-2 text-light');
            div.appendTo('form');
        }
        else 
        {
            console.log(email);
            //app.resetPassword(email);
        }
    },

    resetPassword: function(emailUser) {
        var jqxhr = $.ajax({
          url: 'http://localhost'+ app.uriBack +'/resest-password', 
          method: 'POST',
          dataType: 'json',
          data: {
            email: emailUser,
          }
        });
        jqxhr.done(function (response) {
            // si success = true
            if (response.success) 
            {
            // j'affiche un message de succes
            //app.displaySuccess();
            }
            // sinon 
            else 
            {
            // j'affiche le message d'erreur de l'index msg
            //app.displayError(response.msg);
            }
        });
        // Je déclare la méthode fail, celle-ci sera executée si la réponse est insatisfaisante
        jqxhr.fail(function () {
            alert('Requête échouée');
        });
    },
};
$(app.init);
