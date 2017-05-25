var codeAreaID = 'me-sub-code-area';
var frozen = false;
function freze (bool) {
	if (bool == false) {
		frozen = false;
		$('.me-files > .me-file').removeClass('disabled');
	} else {
		frozen = true;
		$('.me-files > .me-file').addClass('disabled');
	}
}

function toggle (a) {
	$(a).find('.toggle').each(function () {
		$(this).hide();
		$(this).prev('.toggle-open').prepend('<span class="fa fa-caret-right"></span> ').click(function () {
			$to = $(this);
			if ($(this).next('.toggle').is(':hidden')) {
				$to.children('.fa').removeClass('fa-caret-right').addClass('fa-caret-down');
				$to.next('.toggle').slideDown(200);
			} else {
				$to.children('.fa').removeClass('fa-caret-down').addClass('fa-caret-right');
				$to.next('.toggle').slideUp(200);
			}
		})
	});
}

function _popupSimi (siO) {
//	popup_page('?do=getPlaDetails');
//	console.log(siO);
	$.ajax({
		url: '?do=getPlaDetails',
		type: 'post',
		data: {
			cont1: siO.p1,
			cont2: siO.p2,
			u1: $('#top_navbar .myID').attr('id'),
			u2: siO.uname,
			ext: siO.ext
		},
		success: function (data) {
			html = '<div class="popup-section section-light section-simi-info">';
			html += data;
			html += '<div class="clearfix"></div>';
			html += '</div>';
			popup_html(html);
			toggle('.section-simi-info')
		}
	})
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
function showCmt (cmtOne) {
	line = cmtOne.line;
	html = '<div class="cmt-one"><img class="left img-mini" src="'+cmtOne.author.avatar+'" title="'+cmtOne.author.name+'"/><div class="cmt-time gensmall right">'+cmtOne.created+'</div><div class="cmt-content">'+cmtOne.content+'</div></div>';
	$li = $('.wind li.line.L'+line);
	$li.find('.cmt-list').prepend(html);
	if (!$li.find('.toggle-cmt').length) {
		$li.find('.line-comment').prepend('<div class="toggle-cmt fa fa-comments-o" title="Show comments"></div>');
		$li.find('.toggle-cmt').click(function () {
			$thisLi = $(this).closest('li.line');
			$cmtList = $thisLi.find('.cmt-list');
			if ($cmtList.is(':hidden')) {
				$cmtList.slideDown();
				$thisLi.addClass('cmt-show')
			} else {
				$cmtList.slideUp();
				$thisLi.removeClass('cmt-show')
			}
		})
	}
	$('#show_cmt').on('change', function () {
		if ($(this).is(':checked'))  // show
			$('.cmt-one').closest('.cmt-list').slideDown().closest('li.line').addClass('cmt-show');
		else 
			$('.cmt-one').closest('.cmt-list').slideUp().closest('li.line').removeClass('cmt-show');
	})
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
				$li.append('<form method="post" class="add-comment-line" id="' + id + '"> <div class="comment-line-textarea" id="comment-line-'+te+id+'"><textarea name="comment-line-'+te+id+'" style="height:150px" class="add-comment-line-textarea"></textarea></div> <div class="comment-line-note">Adding comments line <b>'+id+'</b> <a href="#" class="fa fa-times-circle close-add-cmt" title="Close"></a> <div class="add-comment-line-action right"><input type="submit" value="Submit" class="btn-xs"/></div></div> </form>');
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
								showCmt(data);
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
//	console.log(formData);
	$.ajax({
		url: '?do=checkPla',
		type: 'POST',
		data: formData,
		success: function (data) {
//			console.log(data);
			data = $.parseJSON(data);
			showPla(data);
		}
	})
}
function showPla (data) {
//	console.log(data);
	$('#me-sub-plachecker').find('.loading').remove();
	activeTab('plachecker');

	checkLocal = data.checkLocal.similar;
	checkOnline = data.checkOnline.items;
		
	if ((data.checkLocal.status == 'success' && checkLocal.length) || (data.checkOnline.status == 'success' && checkOnline.length)) {
		$('#me-sub-plachecker').append('<div class="console warning console-similar csl-main"><div class="similar-detected csl-main"><b>Plagiarism detected</b>: Similar codes detected.</div></div>');
		if (data.checkLocal.status == 'success' && checkLocal.length) {
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
		if (data.checkLocal.status == 'success') 
			$('#me-sub-plachecker').append('<div class="console success"><div class="csl-main"><b>Check Local</b> No plagiarism detected.</div></div>');
		else if (data.checkLocal.status == 'disabled') 
			$('#me-sub-plachecker').append('<div class="console warning"><div class="csl-main"><b>Check Local</b> '+data.checkLocal.content+'</div></div>');
		else if (data.checkLocal.status == 'error') 
			$('#me-sub-plachecker').append('<div class="console error"><div class="csl-main"><b>Check Local</b> '+data.checkLocal.content+'</div></div>');

		if (data.checkOnline.status == 'error') 
			$('#me-sub-plachecker').append('<div class="console error"><div class="csl-main"><b>Check Online</b> No internet connection</div></div>');
		else if (data.checkOnline.status == 'disabled') 
			$('#me-sub-plachecker').append('<div class="console warning"><div class="csl-main"><b>Check Online</b> This function is currently disabled.</div></div>');
	}
	freze(0);
}


function showCompile (data, output, showAllTests) {
	freze(1);
	activeTab('console');
	$('#me-sub-compile').find('.loading').remove();
	//console.log(data);
	$('#me-sub-noti').html('');
	if (!data) $('#me-sub-compile').html('<div class="alerts alert-error">Oops! Missing data!</div>');
	else if (data.status == 'error') {
		$('#me-sub-compile').html('<div class="console error"><div class="csl-main"><b>Errors fetched</b> '+data.content+'</div></div>');
		freze(0);
	} else {
		$('#me-sub-compile').html('<div class="console success"><div class="csl-main"><b>Compile success</b> Compile completed. <span class="strong">Time taken:</span> '+data.time+'</div></div>');
		if (output == true) {
			if (data.check == true) $('#me-sub-compile').append('<div class="console success"><div class="csl-main"><b>Correct</b> Output accepted</div><pre class="output">'+data.output+'</pre><div class="clearfix"></div></div>');
			else $('#me-sub-compile').append('<div class="console error"><div class="csl-main"><b>Wrong output</b> </div> <pre class="output">'+data.output+'</pre> </div>');
		}
		if (showAllTests == true) {
			$('#me-sub-noti').html(loading);
			testCases = '<div class="tc-one thead"><div class="tc-test">Test</div><div class="tc-input">Input</div><div class="tc-output">Output</div><div class="tc-time">Time</div><div class="tc-result">Result</div><div class="clearfix"></div></div>';
			tests = data.tests;
			for (var i in tests) {
				test = tests[i];
				test['input'] = $.trim(test['input']).replace(/(?:\r\n|\r|\n)/g, '<br />');
				test['output'] = $.trim(test['output']).replace(/(?:\r\n|\r|\n)/g, '<br />');
				test['soutput'] = $.trim(test['soutput']).replace(/(?:\r\n|\r|\n)/g, '<br />');
				test['input_short'] = (test['input'].length > 100) ? test['input'].substr(0, 100)+'...' : test['input'];
				test['output_short'] = (test['output'].length > 100) ? test['output'].substr(0, 100)+'...' : test['output'];
				test['soutput_short'] = (test['soutput'].length > 100) ? test['soutput'].substr(0, 100)+'...' : test['soutput'];
				testCases += '<div class="tc-one tc-'+test['checkTxt']+'"><div class="tc-test"><a href="#t'+i+'" id="'+i+'" class="test-details">#'+i+'</a></div><div class="tc-input">'+test['input_short']+'</div><div class="tc-output">'+test['output_short']+'</div><div class="tc-soutput">'+test['soutput_short']+'</div><div class="tc-time">'+test['time']+'</div><div class="tc-result submission-scored submission-'+test['checkTxt']+'"><img class="media-object" src="'+IMG+'/'+test['checkTxt']+'.png" width="12" height="12"> '+test['checkTxt']+'</div><div class="clearfix"></div></div>';
			}
			activeTab('noti');
			$('#me-sub-noti').html(testCases+'<style>.tc-input,.tc-output{width:35%}.tc-time{width:13%}');
			testDetails(tests);
		}
		freze(0);
	}
}

function testDetails (data) {
	$('.test-details').click(function () {
		i = $(this).attr('id');
		testData = data[i];
		//console.log(testData);
		html = '<div class="popup-section section-light test-info">';
		html += '<div class="console tc-'+testData.checkTxt+'"><div class="csl-main"><b>'+testData.checkTxt+'</b></div></div>';
		html += '<div class="console info">';
		html += 	'<div class="csl-main">Time execution: '+testData.time+'</div>';
		html += '</div>';
		html += '<div class="si-one thead"> Input</div>';
		html += '<div class="input">';
		html += 	'<pre class="pre_input">'+testData.input+'</pre>';
		html += '</div>';
		html += '<div class="si-one thead"> <div class="col-lg-6" style="padding:0 5px 0 0!important">Your output</div> <div class="col-lg-6" style="padding:0 0 0 5px!important">Correct output</div> <div class="clearfix"></div> </div>';
		html += '<div class="output-board">';
		html += 	'<div class="col-lg-6" style="padding:0 5px 0 0!important"><pre class="pre_output">'+testData.output+'</pre></div>';
		html += 	'<div class="col-lg-6" style="padding:0 0 0 5px!important"><pre class="pre_soutput">'+testData.soutput+'</pre></div>';
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

			fid = $this.attr('data-id');
			fmode = $this.attr('data-mode');
			checkPla('fnum='+fid+'&mode='+fmode+'&uid='+$this.attr('data-uid'));
			
			$this.addClass('active');
			sid = $this.attr('id');
			$pSub = $('.p-sub');
			$pScore = $('.p-sub-score');
			$pSub.html(loading);
			$('#me-sub-compile,#me-sub-noti').html(loading);
			$pScore.html('');
			$.ajax({
				url: '?do=getsubmission',
				type: 'POST',
				data: 'sid='+sid,
				success: function (data) {
					data = $.parseJSON(data);
					au = data.author;
					//console.log(data);
					$pSub.attr('id', sid).html('<h4>Select a submission</h4>\
				<div class="p-sub-code" style="display:none">\
					<dl class="codebox contcode hidecode">\
						<dt style="border: none;">Code:</dt>\
						<dd class="code_content"></dd>\
					</dl>\
				</div>');
					$pSub.find('h4').text(au.name+'\'s submission').next('.p-sub-code').show();
					$pSub.find('h4').append('<label class="checkbox right no-margin"><input type="checkbox" value="1" id="show_cmt" name="show_cmt"/> Show comments</label>');
					$('#show_cmt').checkbox();
					$pSubCode = $pSub.find('.code_content');
					$pSubCode.html('<code>'+data.codeContent.replace(/(?:\r\n|\r|\n)/g, '<br />')+'</code>');
					
					//showPla(data.console_pla);
					
					$pScore.html('<div class="me-sub-overview">\
					<div class="left me-sub-score"></div>\
					<div class="me-sub-graph">\
						<h4 class="text-center">Total: <strong class="total_accepted"></strong> tests</h4>\
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
					
					showCompile(data.console, false, true);
/*					cdt = data.compile_details;
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
							Test <a href="#t'+j+'">#'+j+'</a>: '+cTit+' <strong class="right" title="Time taken"></strong></div>\
						</div></li>');
					}
					$compileArea = $('.p-submission-details #me-sub-compile');
*/
					
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
						$this = $(".codebox.contcode dd pre.prettyprint");
						$this.find('ol.linenums').addClass('wind').children('li').each(function (k) {
							codeContent = $(this).html();
							k++;
							$(this).attr({
								class: 'line code L'+k,
								id: k
							}).html('<div class="line-comment"><span class="fa fa-comment"></span></div>'+codeContent);
						})
					}
					$("code").addClass("prettyprint").closest('dd.code_content').prev().attr({
						onclick: "selectCode(this)",
						title: "Select all",
						style: "cursor:pointer"
					});
					lineComment()
				}
			})
		}
	})
}

$(document).ready(function () {

	submissionList();

	$('#pList').DataTable({
		"order": [[2, 'desc'], [3, 'desc']]
	});
});
