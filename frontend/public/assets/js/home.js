var appHome = {
  uri: '',

  init: function() {
      console.log('init');
      appHome.uri = $('.container').data("uri");
      appHome.recoverQuizList();
  },

  recoverQuizList: function () {
    var jqxhr = $.ajax({
      //url hebergeur : 
      // url: 'https://neyress.yo.fr/oblog-Api/Backend/all-category', 
      url: 'http://frederic-demoulin.vpnuser.oclock.io/s07/S07-atelier-oquiz/backend/public/', 
      method: 'GET', // La méthode HTTP souhaité pour l'appel Ajax (GET ou POST)
      dataType: 'json', // Le type de données attendu en réponse (text, html, xml, json)
    });
    // Je déclare la méthode done, celle-ci sera executée si la réponse est satisfaisante
    jqxhr.done(function (response) {
      console.log(reponse);
      // j'affiche la liste de mes catégorie
      // appHome.displayListQuiz(response.allQuiz);
    });
    // Je déclare la méthode fail, celle-ci sera executée si la réponse est insatisfaisante
    jqxhr.fail(function () {
      alert('Requête échouée');
    });
  },
  displayListQuiz: function(allQuiz) {

  }
};
$(appHome.init);