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
      // je compte le nombre de question récupéré - 1 ( le premier index contenant les infos liée au quiz : titre etc...)
      var nbrQuestion = response.length - 1;
      // pour chaque questions
       for ( var index in response) {
         // si c'est le premier index
         if (index === '0') {
          // j'affiche les infos concernant le quizz
          appVisitor.modifyHead(response[index], nbrQuestion);
         }
         else {
           // je construit mon bloc réponse
           var quizQuestion = appVisitor.constructQuestion(response[index]);
           // et je l'ajoute au DOM
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
     // modification du span XX questions
    var span = $('<span>').html(nbrQuestion + ' questions').addClass('badge badge-pill badge-secondary ml-2');
    // modification du h2 : titre du quiz
    var h2 = $('.quiz h2').html(quizInfo.title);
    // ajout du span dans le h2
    span.appendTo(h2);
    // modification du h4 : description
    $('.description h4').html(quizInfo.description);
    // modification du p : nom prénom de l'auteur
    $('.auteur p').html('by ' + quizInfo.firstname + ' ' + quizInfo.lastname + '.');
  },

  constructQuestion: function(questionInfo) {
    // je clone la première div enfant de celle dont la classe est .questions
    var divQuestion = $('.questions div:first-child').clone();
    $(divQuestion).addClass('m-2');
    $(divQuestion).removeClass('d-none');
    // j'attribue une couleur en fonction du level de la question
    var color = appVisitor.giveColor(questionInfo.level);
    // je modifie le "badge" level de la question
    $($(divQuestion).find('span')).html(questionInfo.level).addClass(color);
    // je modifie la question
    $($(divQuestion).find('.title')).html(questionInfo.question);
    // je cible le bloc qui contient toutes les réponses
    var answer = $(divQuestion).find('.question-answer-block');
    var allAnswer = [];
    // je boucle pour créer 4 réponse (dont une correcte)
    for (var i = 0; i <= 3; i++) 
    {
      // je clone le premier li contenu dans le ul du bloc de réponse
      var li = $($(answer).find('ul li:first-child')).clone();
      $(li).removeClass('d-none');
      // mauvaise réponse
      if (i !== 3)
      {
        li.html('lorem ipsum');
      }
      // bonne réponse
      else {
        li.html(questionInfo.answer)
      }
      allAnswer[i] = li ;
    }
    // je mélange les réponse
    var shuffle = appVisitor.shuffleArray(allAnswer);
    // j'ajoute chaque réponse au bloc réponse
    for ( var index in shuffle) {
      shuffle[index].appendTo(answer);
    }
  
    return divQuestion;
  },

  // Permet de mélanger les réponses
  shuffleArray: function(array) {
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
    return array;
  },

  // attribut une couleur en fonction du tag
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