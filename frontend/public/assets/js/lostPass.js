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

        var email = $.trim($('.email').val());

        $('.error').remove();

        if (email === '')
        {
            var div = $('<div>').html('Vous ne pouvez pas laisser le champ email vide').addClass('error bg-danger rounded p-2 mt-2 text-light');
            div.appendTo('form');
        }
        else 
        {
            app.sendMail(email);
        }
    },

    sendMail: function(emailUser) {
        console.log('in send mail');
        var jqxhr = $.ajax({
          url: 'http://localhost'+ app.uriBack +'/lost-password', 
          method: 'POST',
          dataType: 'json',
          data: {
            email: emailUser,
            uri: app.uri
          }
        });
        jqxhr.done(function (response) {
            $('.error').remove()
            // si success = true
            if (response.success) 
            {
                var div = $('<div>').html('Un mail de réinitialisation vous a été envoyé').addClass('error bg-info text-light text-center rounded p-2 mt-2');
                div.appendTo($('form'));
            }
            // sinon 
            else 
            {
                var div = $('<div>').html(response.msg).addClass('error bg-danger text-light text-center rounded p-2 mt-2');
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
