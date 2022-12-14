$(function() {
  "use strict";
  // Hide Placeholder On Form Focus
  $('[placeholder]').focus(function(){
    $(this).attr('data-text', $(this).attr('placeholder'));
    $(this).attr('placeholder', '')
  }).blur(function(){
    $(this).attr('placeholder', $(this).attr('data-text'))
  });
  // Add Asterisk On Required Fiesd
  $('input').each(function(){
    if($(this).attr('required') === 'required'){
      $(this).after('<span class="asterisk">*<span>');
    }
  })
  $('.show-pass').click(function(){
    if($('#password').attr('type') == 'password'){
      $('#password').attr('type', 'text');
    } else {
      $('#password').attr('type', 'password');
    }
  });

// Confirmation Message On Button

  $('.confirm').click(function() {

    return confirm('Are You Sure?');

  });
// Category View Option
  $('.cat h3').click(function(){
    $(this).next('.full-view').slideToggle(200);
  });
  $('.option span').click(function(){
    $(this).addClass('active').siblings('span').removeClass('active');
    if($(this).data('view') === 'full') {
      $('.full-view').slideDown(200)
    } else{
      $('.full-view').slideUp(200)
    }
  })
});
