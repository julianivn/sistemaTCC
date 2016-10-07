;(function(){
  'use strict';

  $('.btn-hamburguer').click(function() {
      console.log('click');
      $(this).toggleClass('active');
      return false;
  });

  $('.menu-show').on('click', function(e) {
      e.preventDefault();
      $('.navbar-toggle-js').toggleClass('navbar-visible');
  });
})();
