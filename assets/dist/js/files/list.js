$(document).ready(function () {
	$('.one-file').each(function () {
		$f = $(this);
		var fS = Number($f.find('.file-size span').text());
		var fSt = '<span class="num">' + fS + '</span> bytes';
		if (fS > 1000000000) fSt = '<span class="num">' + Math.round(fS/1000000000*10)/10 + '</span> GB';
		else if (fS > 1000000) fSt = '<span class="num">' + Math.round(fS/1000000*10)/10 + '</span> MB';
		else if (fS > 1000) fSt = '<span class="num">' + Math.round(fS/1000*10)/10 + '</span> KB';
		$f.find('.file-size').html(fSt);
		
		$f.children('.file-name').children('a').not('[data-type="dir"]').click(function () {
			fType = $(this).attr('data-type');
			fURL = $(this).attr('href');
			if (fURL.indexOf("?dir=") == -1) { // not directory
				popup_page('?v=preview&dir='+fURL);
			}
			return false
		})
	})
})
