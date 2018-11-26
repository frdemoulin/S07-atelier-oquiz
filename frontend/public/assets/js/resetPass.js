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

        var password = $.trim($('.password').val());
        var passConfirm = $.trim($('.password-confirm').val());
        $('.error').remove();

        var id = $('.reset-pass').data('id');
        var token = $('.reset-pass').data('token');

        if (id === '' || token === '')
        {
            var div = $('<div>').html('Une erreur est survenue').addClass('error bg-danger rounded p-2 mt-2 text-light');
            div.appendTo('form');
        }
        else if (password === '' || passConfirm === '')
        {
            var div = $('<div>').html('Vous ne pouvez pas laisser de champs vide').addClass('error bg-danger rounded p-2 mt-2 text-light');
            div.appendTo('form');
        }
        else if (password !== passConfirm)
        {
            var div = $('<div>').html('Les mots de passe doivent êtres identiques').addClass('error bg-danger rounded p-2 mt-2 text-light');
            $('.password').val('');
            $('.password-confirm').val('');
            div.appendTo('form');
        }
        else{
            console.log('resetPassword');
            app.resetPassword(password, passConfirm, id, token);
        }
    },

    resetPassword: function(passwordUser, passConfirmUser, idUser, tokenUser) {
        var jqxhr = $.ajax({
            url: 'http://localhost'+ app.uriBack +'/reset-password', 
            method: 'POST',
            dataType: 'json',
            data: {
              id: idUser,
              token: tokenUser,
              password: passwordUser,
              password_confirm: passConfirmUser
            }
          });
          jqxhr.done(function (response) {
            console.log(response);
              // si success = true
              if (response.success) 
              {
                $('form').addClass('d-none');
                var text = 'Votre mot de passe a bien été modifié. <br/>Vous pouvez désormais vous connecter afin de jouer aux quizz ! Amusez vous bien.';
                var div = $('<div>').html(text).addClass('row mx-auto col-11 my-3 border bg-light rounded py-2');
                div.appendTo($('.container'));
              }
              // sinon 
              else 
              {
                  var div = $('<div>').html(response.msg).addClass('bg-info text-light text-center rounded p-2 mt-2');
                  div.appendTo($('form'));
              }
          });
          // Je déclare la méthode fail, celle-ci sera executée si la réponse est insatisfaisante
          jqxhr.fail(function () {
              alert('Requête échouée');
          });
    },
};
$(app.init);