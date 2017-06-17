$(function() {
	'use strict';
	
	//hide placeholder when focus on
	$('[placeholder]').focus(function() {
		$(this).attr('data-text', $(this).attr('placeholder'));

		$(this).attr('placeholder', '');
	}).blur(function() {
		$(this).attr('placeholder', $(this).attr('data-text'));
	});

	//add astriex on input required
	$('input').each(function() {
		if ($(this).attr('required') === 'required') {
			$(this).after('<span class="astriex" >*</span>')
		}
	});

	//login | signup form 
	$('.login-form h1 span').click(function() {
		$(this).addClass('selected').siblings().removeClass('selected');

		$('.login-form form').hide();

		$('.' + $(this).data('class')).fadeIn();
	});	

	//key up 
	$('.live').keyup(function() {
		$($(this).data('class')).text($(this).val());
	});
});