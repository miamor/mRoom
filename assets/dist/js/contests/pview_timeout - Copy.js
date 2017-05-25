var codeAreaID = 'me-sub-code-area';
var frozen = false;
function freze (bool) {
	if (bool == false) {
		frozen = false;
		$('.me-files > .me-file').removeClass('disabled');
	} else {
		frozen = true;
		$('.me-files > .me-file').removeClass('active').addClass('disabled');
	}
}


function _popupSimi (siO) {
	html = '<div class="popup-section section-light simi-info">';
	lcls = tcls = '';
	console.log(siO);
	html += '<div class="si-one thead"> <div class="col-lg-6" style="padding:0 5px 0 0!important">Your file</div> <div class="col-lg-6" style="padding:0 0 0 5px!important">'+siO.uname+'\'s file</div> <div class="clearfix"></div> </div>';
	html += '<div class="si-one">';
	html += 	'<div class="col-lg-6" style="padding:0 5px 0 0!important"><pre><code>'+siO.p1+'</code></pre></div>';
	html += 	'<div class="col-lg-6" style="padding:0 0 0 5px!important"><pre><code>'+siO.p2+'</code></pre></div>';
	html += '</div>';
	html += '<div class="clearfix"></div>';
	html += '</div>';
	popup_html(html);
}

function selectCode(a) {
    var y = a.parentNode.getElementsByTagName('PRE')[0];
    if (window.getSelection) {
        var i = window.getSelection();
        if (i.setBaseAndExtent) {
            i.setBaseAndExtent(y, 0, y, y.innerText.length - 1)
        } else {
            if (window.opera && y.innerHTML.substring(y.innerHTML.length - 4) == '<BR>') {
                y.innerHTML = y.innerHTML + ' '
            }
            var r = document.createRange();
            r.selectNodeContents(e);
            i.removeAllRanges();
            i.addRange(r)
        }
    } else if (document.getSelection) {
        var i = document.getSelection();
        var r = document.createRange();
        r.selectNodeContents(e);
        i.removeAllRanges();
        i.addRange(r)
    } else if (document.selection) {
        var r = document.body.createTextRange();
        r.moveToElementText(y);
        r.select()
    }
}
if (text) {} else {
    var text = 'Selecionar todos'
}


function loadComment () {
	$.ajax({
		url: '?do=getCmt',
		type: 'POST',
		data: 'sid='+sid,
		success: function (data) {
            data = $.parseJSON(data);
			for (var i in data) {
				cmtOne = data[i];
				showCmt(cmtOne);
			}
		},
		error: function (xhr) {
			$('.add-comment-line').find('.done-data').html('<div class="alerts alert-error">'+xhr+'. Please contact the administrators for help.</div>')
		}
	});
}
function showCmt (cmtOne, append = true) {
	line = cmtOne.line;
	html = '<div class="cmt-one"><img class="left img-mini" src="'+cmtOne.author.avatar+'" title="'+cmtOne.author.name+'"/><div style="margin-left:6px;font-size:11px;color:#999" class="right">'+cmtOne.created+'</div><div class="cmt-content">'+cmtOne.content+'</div></div>';
	$li = $('.wind li.line.L'+line);
	if (append == true) $li.find('.cmt-list').append(html);
	else $li.find('.cmt-list').prepend(html);
	if (!$li.find('.toggle-cmt').length) {
		$li.find('.line-comment').prepend('<div class="toggle-cmt fa fa-comments-o" title="Show comments"></div>');
		$li.find('.toggle-cmt').click(function () {
			$(this).closest('li.line').find('.cmt-list').toggle();
		})
	}
}

function lineComment () {
	loadComment();
	$('.line-comment').each(function () {
		$li = $(this).closest('li.line');
		$li.append('<div class="cmt-list hide"></div>');
		$(this).children('span').hide().click(function () {
			$li = $(this).closest('li.line');
			id = Number($li.attr('id'));
			te = $(this).closest('.p-sub').attr('id');
			if ($li.children('form.add-comment-line').length <= 0) {
				$li.append('<form method="post" class="add-comment-line" id="' + id + '"> <div class="comment-line-textarea" id="comment-line-'+te+id+'"><textarea name="comment-line-'+te+id+'" style="height:150px"></textarea></div> <div class="comment-line-note">Adding comments line <b>'+id+'</b> <a href="#" class="fa fa-times-circle close-add-cmt" title="Close"></a> <div class="add-comment-line-action right"><input type="submit" value="Submit" class="btn-xs"/></div></div> </form>');
				sce('#comment-line-'+te+id); 
				flatApp();
				$('.close-add-cmt').click(function () {
					$(this).closest('form.add-comment-line').remove();
					$('.tooltip').remove();
					return false
				});
				$('form.add-comment-line').submit(function () {
					sid = $('.p-submission-details .p-sub').attr('id');
					$.ajax({
						url: '?do=addcomment',
						type: 'POST',
						data: 'sid='+sid+'&line='+id+'&'+$("form.add-comment-line").serialize(),
						success: function (data) {
							if (data != -1) { // success
								data = $.parseJSON(data);
								$("#comment-line-"+te+id+" textarea").val('');
								showCmt(data, false);
								$li.find('.cmt-list').show();
							}
						},
						error: function (xhr) {
							$('.add-comment-line').find('.done-data').html('<div class="alerts alert-error">'+xhr+'. Please contact the administrators for help.</div>')
						}
					});
					return false
				})
			}
		})
	})
}


function checkPla (formData) {
	freze(1);
    $('#me-sub-plachecker').html(loading);
	console.log(formData);
	$.ajax({
		url: '?do=checkPla',
		type: 'POST',
        data: formData,
        success: function (data) {
            data = $.parseJSON(data);
            showPla(data);
        }
    })
}
function showPla (data) {
	console.log(data);
    $('#me-sub-plachecker').find('.loading').remove();
	activeTab('plachecker');
		checkLocal = data.checkLocal.similar;
		checkOnline = data.checkOnline.items;
		if (checkLocal.length || (data.checkOnline.status == 'success' && checkOnline && checkOnline.length)) {
			$('#me-sub-plachecker').append('<div class="console warning console-similar csl-main"><div class="similar-detected csl-main"><b>Plagiarism detected</b>: Bro, we found something similar to your code was submitted before. You might wanna recheck the copyright?</div></div>');
			if (checkLocal.length) {
				$('#me-sub-plachecker').find('.console-similar').append('<label class="checkLocal">Check Local</label>');
				for (var i in checkLocal) {
					siL = checkLocal[i];
					$('#me-sub-plachecker').find('.console-similar').append('<div class="simi"><a class="bold" href="../u/'+siL.uname+'">@'+siL.uname+'</a> <b>cpp</b> file [<b>'+siL.file+'</b>] <div class="siper-bar"><span width="'+siL.per+'%"></span></div><span class="siper" title="Plagiarism detected">'+siL.per+'%</span> <a class="simi-detail" id="simi-'+i+'">Details</a></div>');
					siAr = siL.sAr;
					$('.simi-detail#simi-'+i).click(function () {
						_popupSimi(siL)
					})
				}
			}
			if (data.checkOnline.status == 'success' && checkOnline.length) {
				$('#me-sub-plachecker').find('.console-similar').append('<label class="checkOnline">Check Online</label>');
				for (var j in checkOnline) {
					siO = checkOnline[j];
					$('#me-sub-plachecker').find('.console-similar').append('<div class="simi">\
						<div class="simi-title">\
							<a class="simi-link gensmall italic right" href="'+siO.link+'">'+siO.link+'</a>\
							<a class="bold" title="'+siO.link+'" href="'+siO.link+'">'+siO.title+'</a>\
						</div>\
						<div class="simi-html-snippet">'+siO.htmlSnippet+'</div>\
					</div>');
				}
			}
		} else {
			if (!checkLocal.length) {
				$('#me-sub-plachecker').append('<div class="console success console-similar csl-main"><div class="no-similar-detected csl-main">Nothing similar found in system</div></div>');
			}
			if (data.checkOnline.status == 'error') {
				$('#me-sub-plachecker').find('.console-similar').append('<label class="checkOnline">Check Online</label>\
					<div class="text-error italic">No internet connection</div>');
			} else if (data.checkOnline.status == 'disabled') {
				$('#me-sub-plachecker').find('.console-similar').append('<label class="checkOnline">Check Online</label>\
					<div class="text-error italic">This function is currently disabled.</div>');
			} else if (!checkOnline.length) {
				$('#me-sub-plachecker').find('.console-similar').append('<label class="checkOnline">Check Online</label>\
					<div class="console success console-similar csl-main"><div class="no-similar-detected csl-main">Nothing similar found online</div></div>');
			}
		}
	freze(0);
}

function testDetails (data) {
	$('.test-details').click(function () {
		i = $(this).attr('id');
		testData = data[i];
		console.log(testData);
		html = '<div class="popup-section section-light test-info">';
		html += '<div class="si-one tc-'+testData.checkTxt+'">';
		html += 	testData.checkTxt;
		html += '</div>';
		html += '<div class="si-one">';
		html += 	'Time execution: '+testData.time;
		html += '</div>';
		html += '<div class="si-one thead"> <div class="col-lg-6" style="padding:0 5px 0 0!important">Input</div> <div class="col-lg-6" style="padding:0 0 0 5px!important">Output</div> <div class="clearfix"></div> </div>';
		html += '<div class="si-one">';
		html += 	'<div class="col-lg-6" style="padding:0 5px 0 0!important"><pre><code>'+testData.input+'</code></pre></div>';
		html += 	'<div class="col-lg-6" style="padding:0 0 0 5px!important"><pre><code>'+testData.output+'</code></pre></div>';
		html += '</div>';
		html += '<div class="clearfix"></div>';
		html += '</div>';
		popup_html(html);
		return false
	})
}

function submissionList () {
	$('.p-submissions-list .ranker').click(function () {
		if (!$(this).hasClass('active')) {
			$('.p-submissions-list .ranker').removeClass('active');
			$this = $(this);

			fname = $this.children('.col-lg-9').text();
			checkPla('fname='+fname);
					
			$this.addClass('active');
			sid = $this.attr('data-id');
			$pSub = $('.p-sub');
			$pScore = $('.p-sub-score');
			$pSub.html(loading);
			$pScore.html('');
			$.ajax({
				url: '?do=getsubmission',
				type: 'POST',
				data: 'sid='+sid,
				success: function (data) {
					data = $.parseJSON(data);
					au = data.author;
					//console.log(data);
					
					$pSub.attr('id', sid).html('<div class="p-sub-code">\
					<dl class="codebox contcode hidecode">\
						<dt style="border: none;">Code:</dt>\
						<dd class="code_content"></dd>\
					</dl>\
				</div>');
					$pSubCode = $pSub.find('.code_content');
					$pSubCode.html('<code>'+data.codeContent.replace(/(?:\r\n|\r|\n)/g, '<br />')+'</code>');
					
					$pScore.html('<div class="me-sub-overview">\
					<div class="left me-sub-score"></div>\
					<div class="me-sub-graph">\
						<h4 class="text-center">Total: <strong class="total_accepted"></strong></h4>\
						<div class="progress progress-sm prog-ac"></div>\
					</div>\
					<div class="clearfix"></div>\
				</div>\
				<ul class="list-group me-sub-test-list">\
				</ul>');
				
					if (data.score >= 80) stt = 'success';
					else if (data.score >= 50) stt = 'warning';
					else stt = '';
					
					$pScore.find('.me-sub-score').html('<span class="text-'+stt+'">'+data.score+'</span>');
					$pScore.find('.me-sub-score').html('<span class="text-'+stt+'">'+data.score+'</span>');
					data.AC = Number(data.AC);
					var per = Math.round(data.AC/data.tests * 100, 2);
					$pScore.find('.total_accepted').html(data.AC+'/'+data.tests);
					$pScore.find('.prog-ac').html('<div class="progress-bar progress-bar-'+stt+' progress-bar-striped active" role="progressbar" aria-valuenow="'+data.AC+'" aria-valuemin="0" aria-valuemax="'+data.tests+'" style="width: '+per+'%"></div>');
					
					cdt = data.compile_details;
					$pScore.find('.me-sub-test-list').html('');
					for (var i = 0; i < cdt.length; i++) {
						j = i+1;
						if (cdt[i] == 'AC') {
							cTit = '<span class="submission-scored submission-AC">Accepted</span>';
							cTxt = 'Correct!';
						} else if (cdt[i] == 'WA') {
							cTit = '<span class="submission-scored submission-WA">Wrong Answer</span>';
							cTxt = 'Wrong Answer: Unmatching output!';
						} else if (cdt[i] == 'RTE') {
							cTit = '<span class="submission-scored submission-RTE">Runtime Error</span>';
							cTxt = 'Runtime error: Process returned exit code 1!';
						}
						$pScore.find('.me-sub-test-list').append('<li class="list-group-item media me-sub-test">\
						<div class="media-left left"><img class="media-object" src="'+ASSETS+'/dist/img/'+cdt[i]+'.png" width="12" height="12"></div>\
						<div class="media-body">\
							Test <a class="test-details" id="'+j+'" href="#t'+j+'" title="Details">#'+j+'</a>: '+cTit+' <strong class="right" title="Time taken"></strong></div>\
						</div></li>');
					}
					testDetails(data.console.tests);
					$compileArea = $('.p-submission-details #me-sub-compile');

					if ($(".codebox.contcode dd").filter(function () {
						var a = $(this).text().indexOf("["),
							b = $(this).text().indexOf("]"),
							c = $(this).text().indexOf("[/"),
							d = $(this).text().indexOf("<"),
							e = $(this).text().indexOf('"'),
							f = $(this).text().indexOf("'"),
							g = $(this).text().indexOf("/");
						return a == -1 || b == -1 || c == -1 || a > b || b > c || d != -1 && d < a || e != -1 && e < a || f != -1 && f < a || g != -1 && g < a
					}).each(function () {
						$(this).wrapInner('<pre class="prettyprint' + ($(this).text().indexOf("<") == -1 && /[\s\S]+{[\s\S]+:[\s\S]+}/.test($(this).text()) ? " lang-css" : "") + ' linenums" />')
					}).length) {
						prettyPrint();
					}
					$("code").addClass("prettyprint");
				}
			})
		}
	})
}


$(document).ready(function () {

	submissionList();

});
