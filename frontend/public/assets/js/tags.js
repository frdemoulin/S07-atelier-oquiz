var app = {
    uri: '',
    uriBack: '',
    idTag: '',
  
    init: function() {
        app.uri = $('.container').data('uri');
        app.uriBack = $('.container').data('back');
        app.idTag = $('.tag').data('tag');
        app.recoverQuizList();
    },
  
    recoverQuizList: function () {
      var jqxhr = $.ajax({
        url: 'http://localhost'+ app.uriBack +'/tags/'+ app.idTag + '/quiz', 
        method: 'GET', // La méthode HTTP souhaité pour l'appel Ajax (GET ou POST)
        dataType: 'json', // Le type de données attendu en réponse (text, html, xml, json)
      });
      // Je déclare la méthode done, celle-ci sera executée si la réponse est satisfaisante
      jqxhr.done(function (response) {
        for ( var index in response) {
          if(index === '0') 
          {
            $('.tag h2').html('Liste des quiz du sujet '+ response[index] +'.');
          }
          else {
            var divQuizz = app.constructQuiz(response[index]);
            $(divQuizz).appendTo('.lists');
          }
        }
      });
      // Je déclare la méthode fail, celle-ci sera executée si la réponse est insatisfaisante
      jqxhr.fail(function () {
        alert('Requête échouée');
      });
    },
    constructQuiz: function(quiz) {
        // je construit le l'url de redirection
         var urlQuiz = app.uri + '/quiz/' + quiz.id;
        // je clone la première liste
        var list = $('.lists div:first-child').clone();
        $(list).removeClass('d-none');
        // modification contenu du lien
        var a = $($(list)[0]).find('a');
        $(a).html(quiz.title).attr('href', urlQuiz).addClass('text-dark');
        // contenu du h5
        var h5 = $(list).find('h5');
        $(h5).html(quiz.description);
        // contenu du p
        var p = $(list).find('p');
        $(p).html('by ' + quiz.firstname + ' ' + quiz.lastname);
    
        return list;
      },
};
$(app.init);