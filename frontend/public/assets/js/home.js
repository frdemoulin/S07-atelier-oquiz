var app = {
  uri: '',
  uriBack: '',

  init: function() {
      app.uri = $('.container').data('uri');
      app.uriBack = $('.container').data('back');
      app.recoverQuizList();
      app.recoverTagList();
  },

  recoverQuizList: function () {
    var jqxhr = $.ajax({
      url: 'http://localhost'+ app.uriBack +'/',
      method: 'GET', // La méthode HTTP souhaité pour l'appel Ajax (GET ou POST)
      dataType: 'json', // Le type de données attendu en réponse (text, html, xml, json)
    });
    // Je déclare la méthode done, celle-ci sera executée si la réponse est satisfaisante
    jqxhr.done(function (response) {
      for ( var index in response) {
        // je construit mes div quizz
        var divQuizz = app.constructQuiz(response[index]);
        // je les ajoutes dans la liste
        $(divQuizz).appendTo('.lists');
      }
    });
    // Je déclare la méthode fail, celle-ci sera executée si la réponse est insatisfaisante
    jqxhr.fail(function () {
      alert('Requête échouée');
    });
  },

  recoverTagList: function() {
    var jqxhr = $.ajax({
      url: 'http://localhost'+ app.uriBack +'/tags', 
      method: 'GET', // La méthode HTTP souhaité pour l'appel Ajax (GET ou POST)
      dataType: 'json', // Le type de données attendu en réponse (text, html, xml, json)
    });
    // Je déclare la méthode done, celle-ci sera executée si la réponse est satisfaisante
    jqxhr.done(function (response) {
      for ( var index in response) {
        // je construit mes li de sujet
        var tag = app.constructTag(index, response[index]);
        // je les ajoutes au ul
        $(tag).appendTo('ul.category-aside');
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
    // pour chaque sujet 
    for ( var index in quiz.tags)
    {
      // je construit son url
      var urlTag = app.uri + '/quiz-by-tag/' + quiz.tags[index].id;
      // je créer le lien
      var btn = $('<a>').html(quiz.tags[index].name).addClass('text-light btn mr-2 mb-2 px-2 py-1').attr('href', urlTag);
      // lui attribut une couleur
      var color = app.giveColor(quiz.tags[index].id);
      btn.addClass(color);
      // et l'ajoute au h5
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

  constructTag: function(id, name) {
    // je construit l'url du sujet
    var urlTag = app.uri + '/quiz-by-tag/' + id;
    // créer un li et un lien
    var li = $('<li>').addClass('list-group-item');
    var a = $('<a>').attr('href', urlTag).html(name);
    // ajoute le lien dans le li
    a.appendTo(li);

    return li;
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