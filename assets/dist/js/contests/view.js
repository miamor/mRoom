var urls = window.location.href.split('-'),
	cUrl = urls[0]+'-'+urls[1].split('/')[0];
	
function time_left () {
	$.get(cUrl+'?do=time_left', function (time_left) {
//		console.log(time_left);
		$('.c-time-left').html(time_left);
		tAr = time_left.split(':');
		h = tAr[0];
		i = tAr[1];
		s = tAr[2];
		if (h == 0 && i == 0 && s == 0) { // freze and submit all works
			disable(editor);
			freze(1);
			//doSubmitContest(); // freze inside this function
			if (!$('.timeout-alert').length) $('body').append('<div class="alert alert-warning timeout-alert"><b>Time out!</b> Your work is completed. You\'ll be redirecting now...</div>')
			//mtip('', 'warning', 'Time out!', 'Your work is completed. You\'ll be redirecting now...');
			window.location.reload();
		} else if (i == 3)  // give warnings
			if (!$('.timeout-alert').length) $('body').append('<div class="alert alert-warning timeout-alert"><b>Time running out!</b> Please recheck your task and submit before time out.</div>')
			//mtip('', 'warning', 'Time running out!', 'Please recheck your task and submit before time out!');
	})
}

function doSubmitContest () {
	// just give warnings
/*	$('body').append('<div class="submit_test hidden"></div>');
	// submit active tab
	doSubmit();
	// submit other problems
	$('.c-problems-list > li').not('#home, #discussions, .active').each(function () {
		url = $(this).find('a').attr('href');
		pCode = $(this).text();
		$('.submit_test').load(url+' .code-area', function () {
			formData = $('.submit_test .code-area').serialize()+'&placonsole=false';
			$.ajax({
				url: '?do=submit',
				type: 'POST',
				data: formData,
				success: function (data) {
					console.log(data);
					data = $.parseJSON(data);
					if (data.status == 'error') 
						mtip('', 'error', 'Errors fetched!', 'Submitted problem <b>'+data.submit.content+'</b> successfully');
					else {
						mtip('', 'success', '', 'Submitted problem <b>'+pCode+'</b> successfully');
						//redirect();
					}
					showCompile(data.compile, true, formData, false);
				}
			})
		});
	});
*/}

$(document).ready(function () {
//	$.getScript(JS+'/contests/pview.js');
	
	if ($('.c-time-left').text() != '00:00:00') {
		setInterval(time_left, 1000);
		$('.cjoin').click(function () {
			id = $(this).attr('id');
			$.post("?do=join", {t: id}).done(function (data) {
				console.log(data);
			//	location.reload();
			});
			return false;
		})
	}
})