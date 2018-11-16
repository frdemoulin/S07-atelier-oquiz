appVisitor = {
  uri: '',
  idQuiz: '',

  init: function() {
    appVisitor.uri = $('.container').data("uri");
    appVisitor.idQuiz =  $('.quiz').data("id");
    appVisitor.recoverQuiz();
  },

  recoverQuiz: function () {
    var jqxhr = $.ajax({
      url: 'http://localhost/S07/S07-atelier-oquiz/backend/public/quiz/' + appVisitor.idQuiz,
      method: 'GET', // La méthode HTTP souhaité pour l'appel Ajax (GET ou POST)
      dataType: 'json', // Le type de données attendu en réponse (text, html, xml, json)
    });
    // Je déclare la méthode done, celle-ci sera executée si la réponse est satisfaisante
    jqxhr.done(function (response) {
      var nbrQuestion = response.length - 1;
       for ( var index in response) {
         if (index === '0') {

           var quizHead = appVisitor.modifyHead(response[index], nbrQuestion);
         }
         else {
           var quizQuestion = appVisitor.constructQuestion(response[index]);
           $(quizQuestion).appendTo('.questions');
         }
       }
    });
    // Je déclare la méthode fail, celle-ci sera executée si la réponse est insatisfaisante
    jqxhr.fail(function () {
      alert('Requête échouée');
    });
  },

  modifyHead: function(quizInfo, nbrQuestion) {
    var span = $('<span>').html(nbrQuestion + ' questions').addClass('badge badge-pill badge-secondary ml-2');

    var h2 = $('.quiz h2').html(quizInfo.title);
    span.appendTo(h2);

    $('.description h4').html(quizInfo.description);

    $('.auteur p').html('by ' + quizInfo.firstname + ' ' + quizInfo.lastname + '.');
  },
  constructQuestion: function(questionInfo) {
    var divQuestion = $('.questions div:first-child').clone();
    $(divQuestion).addClass('m-2');
    $(divQuestion).removeClass('d-none');
    var color = appVisitor.giveColor(questionInfo.level);
    $($(divQuestion).find('span')).html(questionInfo.level).addClass(color);
    $($(divQuestion).find('.title')).html(questionInfo.question);
    var answer = $(divQuestion).find('.question-answer-block');
    var allAnswer = [];
    for (var i = 0; i < 3; i++) 
    {
      var li = $($(answer).find('ul li:first-child')).clone();
      $(li).html('lorem ipsum').removeClass('d-none');
      allAnswer[i] = li ;
    }
    var li = $($(answer).find('ul li:first-child')).clone();
    $(li).html(questionInfo.answer).removeClass('d-none');
    allAnswer[i] = li ;

    var shuffle = appVisitor.shuffleArray(allAnswer);
    for ( var index in shuffle) {
      shuffle[index].appendTo(answer);
    }
  
    return divQuestion;
  },

  shuffleArray: function(array) {
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
    return array;
  },

  giveColor: function (nameLvl) {
    if(nameLvl === 'Débutant') {
      return 'badge-success';
    }
    else if (nameLvl === 'Confirmé') {
      return 'badge-warning';
    }
    else if (nameLvl === 'Expert') {
      return 'badge-danger';
    }
    else {
      return 'badge-dark';
    }
  }
};

$(appVisitor.init);