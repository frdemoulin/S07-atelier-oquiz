app = {
  uri: '',
  idQuiz: '',
  idAnswer:0,
  questionCpt: 1,

  init: function() {
    // je récupère ma base uri
    app.uri = $('.container').data('uri');
    // et l'id du quiz cliqué
    app.idQuiz =  $('.quiz').data('id');
    // puis je lance ma requête ajax 
    app.recoverQuiz();
    $('form').on('submit', app.correction);
  },

  recoverQuiz: function () {
    var jqxhr = $.ajax({
      url: 'http://localhost/S07/S07-atelier-oquiz/backend/public/quiz/' + app.idQuiz,
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
          app.modifyHead(response[index], nbrQuestion);
         }
         // pour tout les autres index
         else {
           // je construit mon bloc réponse
           var quizQuestion = app.constructQuestion(response[index]);
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
    // préparation des variable utile ( dans le for )
    var allAnswer = [];
    var answerIndex = 0;
    var questionNbr = 'question' + app.questionCpt;
    app.questionCpt++;

    // je créer une div qui contiendra chaque élément de la question
    var divContainer = $('<div>').addClass('col-sm-3 border p-0 m-2');
    // j'attribue une couleur en fonction du level
    var color = app.giveColor(questionInfo.level);
    // je créer le "badge" level
    var spanBadge = $('<span>').addClass('badge float-right mt-2 mr-2').html(questionInfo.level);
    spanBadge.addClass(color);
    // je créer la div qui contiendra la question + formulaire des réponses
    var divQuestion = $('<div>').addClass('p-3 background-grey').html(questionInfo.question);
    // bloc qui contient les réponses
    var divAnswerBlock = $('<div>').addClass('p-3 question-answer-block');
    
    // je boucle sur mon tableau de mauvaise réponse que je stock dans une div
    for (var index in questionInfo.badAnswer) 
    {
      // l'id doit être unique pour que chaque input ai un label associé
      app.idAnswer ++;
      var nameInput = 'answer' + app.idAnswer;
      var divFormCheck = $('<div>').addClass('form-check');
      var input = $('<input>').addClass('form-check-input').attr({type:'radio', name:questionNbr, id:nameInput, value:0});
      var label = $('<label>').addClass('form-check-label').html(questionInfo.badAnswer[index]).attr({for:nameInput});
      // ajout des mauvaise réponse à la div form check
      input.appendTo(divFormCheck);
      label.appendTo(divFormCheck);
      // ajout de la div form check au tableau qui contiendra TOUTE les réponses
      allAnswer[answerIndex] = divFormCheck;
      answerIndex++;
    }
    
    // bonne réponse
    app.idAnswer ++;
    var nameInput = 'answer' + app.idAnswer;
    var divFormCheck = $('<div>').addClass('form-check');
    var input = $('<input>').addClass('form-check-input').attr({type:'radio', name:questionNbr, id:nameInput, value:1});
    var label = $('<label>').addClass('form-check-label').html(questionInfo.answer).attr({for:nameInput});
    // ajout de la bonne réponse à la div form check
    input.appendTo(divFormCheck);
    label.appendTo(divFormCheck);
    // ajout de la div form check au tableau qui contiendra TOUTE les réponses
    allAnswer[answerIndex] = divFormCheck;

    // on mélange les réponses pour que la bonne ne soit pas toujours en dernière position
    var shuffle = app.shuffleArray(allAnswer);
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

  // Permet de mélanger un tableau
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
  },
  
  correction: function(evt) {
    evt.preventDefault();
    // je récupère l'ensemble des inputs et des labels
    var allInput = $('.form-check-input');
    var allLabel = $('.form-check-label');
    var score = 0;

    // je boucle sur tout les inputs
    for (var index in allInput) {
      // j'élimine les 4 premiers inputs qui sont les exemples de l'intégrations
      if (index > 3) 
      {
        // je désactive les input pour "verrouiller" les réponses
        $(allInput[index]).attr('disabled', true);
        // je récupère la value de chaque input
        var answer = $(allInput[index]).val();
        // si c'est la bonne réponse j'affiche son label en vert
        if (answer === '1')
        {
          $(allLabel[index]).addClass('text-success');
        }
        // je regarde si l'input à été coché par l'user
        var checkInput = $(allInput[index]).prop('checked');
        // s'il est coché
        if (checkInput) 
        {
          // si la réponse est correcte j'incrémente le score
          if(answer === '1') 
          {
            score++;
          }
          else 
          {
            // sinon je met le texte en rouge
            $(allLabel[index]).addClass('text-danger');
          }
        }
      }
    }
    // pour finir j'affiche le score
    var nbrQuestion = app.questionCpt - 1;
    $('span.badge-pill').html('Score : '+ score +'/'+ nbrQuestion);
  }
};

$(app.init);