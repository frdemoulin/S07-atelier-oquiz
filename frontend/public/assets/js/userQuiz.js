appUser = {
  uri: '',
  idQuiz: '',
  idAnswer:1,

  init: function() {
    // je récupère ma base uri
    appUser.uri = $('.container').data("uri");
    // et l'id du quiz cliqué
    appUser.idQuiz =  $('.quiz').data("id");
    // puis je lance ma requête ajax 
    appUser.recoverQuiz();
  },

  recoverQuiz: function () {
    var jqxhr = $.ajax({
      url: 'http://localhost/S07/S07-atelier-oquiz/backend/public/quiz/' + appUser.idQuiz,
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
          appUser.modifyHead(response[index], nbrQuestion);
         }
         // pour tout les autres index
         else {
           // je construit mon bloc réponse
           var quizQuestion = appUser.constructQuestion(response[index]);
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
    // je créer une div qui contiendra chaque élément de la question
    var divContainer = $('<div>').addClass('col-sm-3 border p-0 m-2');
    // j'attribue une couleur en fonction du level
    var color = appUser.giveColor(questionInfo.level);
    // je créer le "badge" level
    var spanBadge = $('<span>').addClass('badge float-right mt-2 mr-2').html(questionInfo.level);
    spanBadge.addClass(color);
    // je créer la div qui contiendra la question + formulaire des réponses
    var divQuestion = $('<div>').addClass('p-3 background-grey').html(questionInfo.question);
    // bloc qui contient les réponses
    var divAnswerBlock = $('<div>').addClass('p-3 question-answer-block');
    var allAnswer = [];
    var answerIndex = 0;
    // je boucle sur mon tableau de mauvaise réponse que je stock dans une div
    for (var index in questionInfo.badAnswer) 
    {
      // le nom doit être unique pour que chaque input ai un label associé
      var nameInput = 'answer' + appUser.idAnswer;
      appUser.idAnswer ++;
      var divFormCheck = $('<div>').addClass('form-check');
      var input = $('<input>').addClass('form-check-input').attr({type:'radio', name:nameInput, id:nameInput, value:0});
      var label = $('<label>').addClass('form-check-label').html(questionInfo.badAnswer[index]).attr({for:nameInput});
      // ajout des réponse à la div form check
      input.appendTo(divFormCheck);
      label.appendTo(divFormCheck);
      // ajout de la div form check au tableau des réponses
      allAnswer[answerIndex] = divFormCheck;
      answerIndex++;
    }
    // bonne réponse
    var nameInput = 'answer' + appUser.idAnswer;
    var divFormCheck = $('<div>').addClass('form-check');
    var input = $('<input>').addClass('form-check-input').attr({type:'radio', name:nameInput, id:nameInput, value:1});
    var label = $('<label>').addClass('form-check-label').html(questionInfo.answer).attr({for:nameInput});
    // ajout des réponse à la div form check
    input.appendTo(divFormCheck);
    label.appendTo(divFormCheck);
    // ajout de la div form check au tableau des réponses
    allAnswer[answerIndex] = divFormCheck;

    // on mélange les réponses
    var shuffle = appUser.shuffleArray(allAnswer);
    // avant de les ajouter à la div qui contient toutes les réponses
    for (var index in shuffle) 
    {
      shuffle[index].appendTo(divAnswerBlock);
    }
    // puis on ajoute le tout à la div container
    spanBadge.appendTo(divContainer);
    divQuestion.appendTo(divContainer);
    divAnswerBlock.appendTo(divContainer);

    return divContainer;
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

  // attribut une couleur en fonction du level
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

$(appUser.init);