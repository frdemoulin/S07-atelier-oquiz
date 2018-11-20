var app = {
  uri: '',

  init: function() {
      app.uri = $('.container').data("uri");
      app.recoverQuizList();
  },

  recoverQuizList: function () {
    var jqxhr = $.ajax({
      url: 'http://localhost/S07/S07-atelier-oquiz/backend/public/', 
      method: 'GET', // La méthode HTTP souhaité pour l'appel Ajax (GET ou POST)
      dataType: 'json', // Le type de données attendu en réponse (text, html, xml, json)
    });
    // Je déclare la méthode done, celle-ci sera executée si la réponse est satisfaisante
    jqxhr.done(function (response) {
      for ( var index in response) {
        var divQuizz = app.constructQuiz(response[index]);
        $(divQuizz).appendTo('.lists');
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
    $(h5).html('');
    for ( var index in quiz.tags)
    {
      var urlTag = app.uri + '/quiz-by-tag/' + quiz.tags[index].id;
      var btn = $('<a>').html(quiz.tags[index].name).addClass('text-light btn mr-2 px-2 py-1').attr('href', urlTag);
      var color = app.giveColor(quiz.tags[index].id);
      btn.addClass(color);
      btn.appendTo(h5);
    }
    // contenu du h6
    var h6 = $(list).find('h6');
    $(h6).html(quiz.description);
    // contenu du p
    var p = $(list).find('p');
    $(p).html('by ' + quiz.firstname + ' ' + quiz.lastname);

    return list;
  },

  giveColor: function (id) {
    if(id === 1) {
      return 'btn-primary';
    }
    else if (id === 2) {
      return 'btn-secondary';
    }
    else if (id === 3) {
      return 'btn-danger';
    }
    else if (id === 4) {
      return 'btn-success';
    }
    else if (id === 5) {
      return 'btn-info';
    }
    else if (id === 6) {
      return 'btn-dark';
    }
    else if (id === 7) {
      return 'btn-success';
    }
    else if (id === 8) {
      return 'btn-danger';
    }
    else if (id === 9) {
      return 'btn-secondary';
    }
    else {
      return 'btn-dark';
    }
  }
};
$(app.init);