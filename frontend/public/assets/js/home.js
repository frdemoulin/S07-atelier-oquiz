var appHome = {
  uri: '',

  init: function() {
      appHome.uri = $('.container').data("uri");
      appHome.recoverQuizList();
  },

  recoverQuizList: function () {
    var jqxhr = $.ajax({
      url: 'http://localhost/S07/S07-atelier-oquiz/backend/public/', 
      method: 'GET', // La méthode HTTP souhaité pour l'appel Ajax (GET ou POST)
      dataType: 'json', // Le type de données attendu en réponse (text, html, xml, json)
    });
    // Je déclare la méthode done, celle-ci sera executée si la réponse est satisfaisante
    jqxhr.done(function (response) {
      console.log(response);
      for ( var index in response) {
        var divQuizz = appHome.constructQuiz(response[index]);
        $(divQuizz).appendTo('.lists');
      }
    });
    // Je déclare la méthode fail, celle-ci sera executée si la réponse est insatisfaisante
    jqxhr.fail(function () {
      alert('Requête échouée');
    });
  },

  constructQuiz: function(quiz) {
    console.log(quiz);
    // je construit le l'url de redirection
    var url = appHome.uri + '/quiz/' + quiz.id;
    // je clone la liste
    var list = $('.list').clone();
    $(list).removeClass('d-none');
    // modification contenu du lien
    var a = $(list).find('a');
    $(a).html(quiz.title).attr('href', url);
    // contenu du h5
    var h5 = $(list).find('h5');
    var allTags = '';
    for ( var index in quiz.tags)
    {
      allTags += quiz.tags[index] + ' ';
    }
    $(h5).html(allTags);
    // contenu du h6
    var h6 = $(list).find('h6');
    $(h6).html(quiz.description);
    // contenu du p
    var p = $(list).find('p');
    $(p).html(quiz.firstame + ' ' + quiz.lastname);

    return list;
  }
};
$(appHome.init);