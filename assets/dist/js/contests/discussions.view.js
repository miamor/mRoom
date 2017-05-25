var table = $('table#treplies').DataTable({
	"ajax": '?do=getreplies',
    "order": [[1, 'asc']],
	"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
		$(nRow).attr("id", aData[2]);
		return nRow;
	},
	"aoColumns": [
		{ "sClass": "col-lg-1 postprofile centered no-padding", "sValign": "top" },
		{ "sClass": "col-lg-11 postmain no-padding", "sValign": "top" }
	],
    "initComplete": function (settings, json) {
//		console.log(json);
		if (json.data.length > 0) {
			$('.t_replies').show();
	//		icons('t_replies');
			code('#treplies')
	//		table.page('last').draw('page');
			var p = window.location.href.split('#')[1];
			if (p) {
				console.log(p);
				table.row(Number(p)-1).scrollTo(false);
				goToByScroll('p'+p);
			}
			setInterval(function () {
				table.ajax.reload(function (json) {
	//				icons('t_replies');
					code('#treplies');
				}, false);
			}, 100000);
		} else $('.t_replies').hide();
    }
});

function selectCode(a) {
    var y = a.parentNode.getElementsByClassName('code_content')[0];
    if (window.getSelection) {
        var i = window.getSelection();
        if(i.setBaseAndExtent) {
            i.setBaseAndExtent(y, 0, y, y.innerText.length - 1)
        } else {
            if(window.opera && y.innerHTML.substring(y.innerHTML.length - 4) == '<BR>') {
                y.innerHTML = y.innerHTML + ' '
            }
            var r = document.createRange();
            r.selectNodeContents(e);
            i.removeAllRanges();
            i.addRange(r)
        }
    } else if(document.getSelection) {
        var i = document.getSelection();
        var r = document.createRange();
        r.selectNodeContents(e);
        i.removeAllRanges();
        i.addRange(r)
    } else if(document.selection) {
        var r = document.body.createTextRange();
        r.moveToElementText(y);
        r.select()
    }
}
if(text) {} else {
    var text = 'Selecionar todos'
}

function code (a) {
	if (!a) $a = $('body').find('*:not("pre")');
	else $a = $(a).find('*:not("pre")');

	$a.find('code').find('p:not([class])').contents().unwrap();

	$a.children('code').each(function () {
		var text = $(this).text().replace(/<br\s?\/?>$/, "");
		$(this).text(text);
		if (text.search(/<br\s?\/?>/) !== -1 || text.length > 50) $(this).wrap('<dl class="codebox contcode hidecode"></dl>').before('<dt onclick="selectCode(this)" title="Select all" style="cursor:pointer" style="border: none;">Code:</dt>').wrap('<dd class="code_content"></dd>');
		else $(this).wrap('<div class="code_content one_line" style="margin-right:3px!important"/>');
		//console.log(text.length);
	})
	
	$a.find('.code_content').children('code').each(function () {
		var text = $(this).text();
		$(this).text(text.replace(/<br\/>/gm, ""));
		$(this).wrap('<pre class="prettyprint' + ($(this).text().indexOf("<") == -1 && /[\s\S]+{[\s\S]+:[\s\S]+}/.test($(this).text()) ? " lang-css" : "") + ' linenums" />');
	});
	prettyPrint();

/*	$a.find('.post-content').each(function () {
		var lines = $(this).html().split(/\<br\>|\<br\/\>|\<br\>\<br\/\>/);
		$(this).html('<div>' + lines.join("</div><div>") + '</div>');
	})
	
/*		codes = $("code").not(".min-code");
		for (var i = 0; i < codes.length; i++) {
			codesAr = codes[i].innerHTML.split(/\<br\s?\/?\>/);
			codes[i].innerHTML = '';
			for (k = 0; k < codesAr.length; k++) 
				codes[i].innerHTML += '<li class="line code L' + k + '" id="' + k + '"><code>' + codesAr[k] + '</code></li>';
			codes[i].innerHTML = '<ol class="wind">' + codes[i].innerHTML + '</ol>';
		}
*/
}

function img (a) {
	$a = $(a);
	$a.find('img').each(function () {
		if ($(this).width() >= 100 && $(this).height() >= 100) {
			img = $(this).attr('src');
			$(this).wrap('<div class="bimg"/>');
//			$(this).replaceWith('<div class="blog-img" style="background-image:url(\''+img+'\')"></div>')
		}
	})
}

$(document).ready(function () {
	$('blockquote').wrapInner('<div class="blockquote_content"/>');
	code('#tfirst');
	img('#tfirst');
});
