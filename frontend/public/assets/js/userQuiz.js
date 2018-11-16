appUser = {
  uri: '',

  init: function() {
    appUser.uri = $('.container').data("uri");
    console.log('init user');
  },
};
$(appUser.init);