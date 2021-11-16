$(function () {
	'use strict';

  $('.login-page h1 span').click(function() {
      $(this).addClass('selected').siblings().removeClass('selected');
      $('.login-page form').hide();
      $('.' + $(this).data('class')).fadeIn(100);
  });
    
  // Trigger the Select Boxit
  $("select").selectBoxIt({
  	 autoWidth: false
  });


  //hide placeholder on focus
  $('[placeholder]').focus(function() {
  	$(this).attr('data-text',$(this).attr('placeholder'));
  	$(this).attr('placeholder','')
  }).blur(function() {
  	$(this).attr('placeholder',$(this).attr('data-text'));
  });

  // Add asterisk on required field
  $('input').each(function() {
    	if($(this).attr('required') === 'required'){
         $(this).after('<span class = "asterisk">*</span>');
    	}
  });

   // confirmation message on button
   $('.confirm').click(function() {
   	  return confirm('Are you sure');
   });

   $('.live').keyup(function() {
        $($(this).data('class')).text($(this).val());
   });

});

function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onloadend = function (e) {
        $('#image_preview').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
}