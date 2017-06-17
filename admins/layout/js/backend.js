$(function() {
	'use strict';



	//Dashboard
	$('.note').click(function() {
		$(this).toggleClass('selected').parent().next('.panel-body').fadeToggle('fast');

		if ($(this).hasClass('selected')) {
			$(this).html('<i class="fa fa-minus"></i>');
		} else {
			$(this).html('<i class="fa fa-plus"></i>')
		}
	});

	
	
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

	// show pass when hover eye
	$('.show-pass').hover(function() {
		$('.password').attr('type', 'text');
	}, function() {
		$('.password').attr('type', 'password');
	});

	//confirm when click on delete
	$('.confirm').click(function() {
		return confirm('Are You Sure To Delete This Member');
	});

	//action on class full view
	$('.cat h5').click(function() {
		$(this).next('.cat .full-view').fadeToggle(200);
	});

	$('.categories span').click(function() {
		$(this).addClass('active').siblings('span').removeClass('active');

		if ($(this).data('view') === 'full') {
			$('.cat .full-view').fadeIn(200);
		} else {
			$('.cat .full-view').fadeOut(200);
		}
	});
});