var viewname, editorAce, editorResult, old = '', fileName1 = '', fileName2 = '', converted = '', json = '', editor, isXmlData = true,
paragraphFlag = false, oldData = "", highlightedText = '', popupStatus = 0,canvas,context;
//ace.require("ace/ext/language_tools");
var editor = ace.edit(codeAreaID);
editor.setTheme("ace/theme/"+ACE_THEME);
var meCode;
var frozen = false;
var fromSetValue = false;

var $notiArea = $('#me-sub-noti');
var $compileArea = $('#me-sub-compile');
var $plaArea = $('#me-sub-plachecker');

editor.commands.addCommand({
	name: 'saveFile',
	bindKey: {
		win: 'Ctrl-S',
		mac: 'Command-S',
		sender: 'editor|cli'
	},
	exec: function (env, args, request) {
		saveEditor(editor)
	}
});

function saveEditor (editor) {
		content = editor.getSession().getValue();
		if (!fromSetValue) {
			content = editor.getSession().getValue();
			$current = $('.me-files .me-file.active');
			$current.text($current.text().replace('*', ''));
			id = $current.attr('data-id');
			lang = $current.attr('data-lang');
			$.post("?do=update", {id: id, lang: lang, content: content});
		}
}

function setOutputMsg(msg) {
	$("#outputMsg").html("Result : " + msg);
}

function beautify (oldformat) {
	var newformat = "";
	indent_size = $('#index').val();
	if (!indent_size) indent_size = 0;
	newformat = js_beautify(oldformat,{
		"indent_size": indent_size,
		"indent_char": " ",
		"other": " ",
		"indent_level": 0,
		"indent_with_tabs": false,
		"preserve_newlines": true,
		"max_preserve_newlines": 2,
		"jslint_happy": true,
		"indent_handlebars": true
	});
	return newformat
}

function FormatWithOption (editor) {
//	var editor = ace.edit(codeAreaID);
	var oldformat = editor.getValue() , newformat = "";
	return beautify(oldformat)
}

function freze (bool) {
	if (bool == false) {
		frozen = false;
		$('.me-files > .me-file').removeClass('disabled');
	} else {
		frozen = true;
		$('.me-files > .me-file').addClass('disabled');
	}
}

function openFile (id, lang, uid, reload, stop) {
	$('.code-sub-area').show();
	$meFile = $('.me-files > .me-file[data-id="'+id+'"][data-lang="'+lang+'"]');

	if (frozen == false && (!$meFile.hasClass('active') || reload == true)) {
	$('.me-files > .me-file').removeClass('active');
	freze(1);
	$meFile.removeClass('disabed').addClass('active');
	$('.me-sub-code-alerts').html('');
	$('.me-sub-compile').html(loading);
	activeTab('console');
	$('#me-sub-code-area').hide().before(loading);
	meCode = $meFile.attr('data-mode');
	isSubmit = $meFile.attr('data-submit');
	key = $meFile.attr('data-key');
	$form = $('form.code-area');
	uc = '';
	if (uid) uc = '&uid='+uid;
	$.ajax({
		url: '?do=getfile',
		type: 'POST',
		data: 'fid='+id+'&lang='+lang+uc+'&isSubmit='+isSubmit+'&key='+key,
		success: function (data) {
		//	$('#code-editor-area').show().html(data);
			data = $.parseJSON(data);
			//console.log(data);
			codeContent = data.content;
			$('#me-sub-code-area').show().prev().remove();
			if (data == -1) {
				mtip('', 'error', '', 'No files found.');
				freze(0);
			} else {
				$('#sub-num').val(id);
				$form.attr('id', id);
				if (meCode == 'cpp' || meCode == 'c') mode = 'c_cpp';
				else mode = meCode;
				editor.getSession().setMode("ace/mode/"+mode);
				fromSetValue = true;
				editor.getSession().setValue(codeContent);
				fromSetValue = false;
				codeToolHTML = '<ul class="nav nav-tabs">\
			<li><a href="#noti" data-toggle="tab">Notification</a></li>\
			<li class="active"><a href="#console" data-toggle="tab">Execute</a></li>\
			<li><a href="#plachecker" data-toggle="tab">Plagiarism checking</a></li>\
			<li class="pull-right">';
				if (isSubmit == 0) {
					codeToolHTML += '<div id="code-submit" class="btn btn-success right">Submit</div>\
				<div id="code-compile" class="btn btn-danger right">Compile</div>';
				}
				codeToolHTML += '<div id="mode" class="right">'+lang+'</div>\
			</li>\
		</ul>\
		<div class="tab-content">\
			<div class="tab-pane" id="noti">\
				<div class="me-sub-compile" id="me-sub-noti"></div>\
			</div><!-- #noti -->\
			<div class="tab-pane active" id="console">\
				<div class="me-sub-compile" id="me-sub-compile"></div>\
			</div><!-- #console -->\
			<div class="tab-pane" id="plachecker">\
				<div class="me-sub-compile" id="me-sub-plachecker"></div>\
			</div><!-- #plachecker -->\
		</div><!-- /.tab-content -->';

				$('.code-tool').html(codeToolHTML);
				$('input#mode').val(lang);
				if (isSubmit == 0) {
					enable(editor);
					$('.me-sub-code-alerts').html('');
					if ($form.attr('id') == id) {
						compileCode();
						submitCode();
						freze(0);
					}
				} else {
					disable(editor);
					$('.me-sub-code-alerts').html('<div class="alert alert-warning alert-dismissable">\
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>\
						<h4><i class="icon fa fa-warning"></i> No permissions found!</h4>\
						This file has been submitted. Therefore, you don\'t have permission to edit and compile this file again. <br/>Please create a new file and make a new submission.\
						</div>');

					var code = data;
					newformat = FormatWithOption(editor);
					$('textarea#sub-code').val(code);
					$('textarea#sub-code-formatted').val(newformat);
					formData = $('.code-area').serialize()+'&mode='+meCode+'&all=true';
					//doCompile(formData, true, true)
					// don't use old data of plagiarism checker. checkPla again!
					if (data.console) showCompile(data.console, true, formData, true, true);
					//if (data.console_pla) showPla(data.console_pla);
				}
			}
		}
	})
	}

	$('.tips-reload').remove();
}

function enable (editor) {
	editor.setOptions({
		readOnly: false,
		highlightActiveLine: true,
		highlightGutterLine: true
	});
	editor.renderer.$cursorLayer.element.style.opacity = 1;
	editor.textInput.getElement().disabled = false;
//	editor.commands.commmandKeyBinding = {};
}
function disable (editor) {
	editor.setOptions({
		readOnly: true,
		highlightActiveLine: false,
		highlightGutterLine: false
	});
	editor.renderer.$cursorLayer.element.style.opacity = 0;
	editor.textInput.getElement().disabled = true;
//	editor.commands.commmandKeyBinding = {};
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

function _popupSimi (id, siO) {
//	popup_page('?do=getPlaDetails');
/*	console.log(siO.p1);
	console.log(siO.p2);
*/	$('.simi-detail#simi-'+id).click(function () {
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
			//console.log(data);
			html = '<div class="popup-section section-light section-simi-info">';
			html += data;
			html += '<div class="clearfix"></div>';
			html += '</div>';
			popup_html(html);
			$('.beautify').each(function () {
				$(this).html('<code>'+beautify(decodeEntities($(this).closest('.compare-one').find('pre.code code').html()))+'</code>')
			});
			toggle('.section-simi-info')
		}
	})
	})
}
function decodeEntities(encodedString) {
	var textArea = document.createElement('textarea');
	textArea.innerHTML = encodedString;
	return textArea.value;
}

/*function _popupSimis (siO) {
	html = '<div class="popup-section section-light simi-info">';
	lcls = tcls = '';
	html += '<div class="si-one thead"> <div class="col-lg-6" style="padding:0 0 0 5px!important">Your file</div> <div class="col-lg-6" style="padding:0 5px 0 0!important">'+siO.uname+'\'s file</div> <div class="clearfix"></div> </div>';
	html += '<div class="si-one">';
	html +=	 '<div class="col-lg-6" style="padding:0 0 0 5px!important"><pre><code>'+siO.p1+'</code></pre></div>';
	html +=	 '<div class="col-lg-6" style="padding:0 5px 0 0!important"><pre><code>'+siO.p1+'</code></pre></div>';
	html += '</div>';
	html += '<div class="clearfix"></div>';
	html += '</div>';
	popup_html(html);
}
function simiDetail () {
	$('.simi-detail').click(function () {
		i = $(this).attr('id').split('-')[1];
		lang = $(this).closest('.simi').children('b').text();
		u = $('.pladt').attr('data-for');
		myfnum = $('#me-sub-code').attr('data-id');
		formData = 'mode='+lang+'&u='+u+'&num='+i+'&fnum='+myfnum;
		$.ajax({
			url: '?do=plchecker',
			type: 'post',
			data: formData,
			success: function (data) {
				siO = $.parseJSON(data);
				siAr = siO.sAr;
				_popupSimi(siO);
			}
		});
	})
}
*/

function checkPla (formData) {
	$('#me-sub-plachecker').html(loading);
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

	// check local
	if (data.checkLocal.status == 'success') {
		if (!checkLocal.length) $('#me-sub-plachecker').append('<div class="console success"><div class="csl-main"><b>Check Local</b> No plagiarism detected.</div></div>');
		else {
//			$('#me-sub-plachecker').append('<div class="console warning console-similar csl-main"><div class="similar-detected csl-main"><b>Plagiarism detected</b>: Bro, we found something similar to your code was submitted before. You might wanna recheck the copyright?</div></div>');
			$('#me-sub-plachecker').append('<div class="console warning console-similar similar-local csl-main"><div class="similar-detected csl-main"><b>Check Local</b> Similar codes detected!</div></div>');
//			$('#me-sub-plachecker').find('.console-similar').append('<label class="checkLocal">Check Local</label>');
			for (var i in checkLocal) {
				siL = checkLocal[i];
				$('#me-sub-plachecker').find('.similar-local').append('<div class="simi"><a class="bold" href="../u/'+siL.uname+'">@'+siL.uname+'</a> <b>cpp</b> file [<b>'+siL.file+'</b>] <div class="siper-bar"><span width="'+siL.per+'%"></span></div><span class="siper" title="Plagiarism detected">'+siL.per+'%</span> <a class="simi-detail" id="simi-'+i+'" attr-uid="'+siL.u+'">Details</a></div>');
				siAr = siL.sAr;
				$('.simi-detail#simi-'+i).each(function () {
					_popupSimi(i, siL)
				})
			}
		}
	} else if (data.checkLocal.status == 'disabled') {
		$('#me-sub-plachecker').append('<div class="console warning"><div class="csl-main"><b>Check Local</b> '+data.checkLocal.content+'</div></div>');
		//$('#me-sub-plachecker').find('.console-similar').append('<label class="checkLocal">Check Local</label><div class="text-warning italic">'+data.checkLocal.content+'</div>');
	}
	
	// check online
	if (data.checkOnline.status == 'success') {
		if (!checkOnline.length) $('#me-sub-plachecker').append('<div class="console success"><div class="csl-main"><b>Check Online</b> No plagiarism detected.</div></div>');
		else {
			$('#me-sub-plachecker').append('<div class="console warning console-similar similar-online csl-main"><div class="similar-detected csl-main"><b>Check online</b> Similar codes detected!</div></div>');
			for (var j in checkOnline) {
			siO = checkOnline[j];
				$('#me-sub-plachecker').find('.similar-online').append('<div class="simi">\
					<div class="simi-title">\
						<a class="simi-link gensmall italic right" href="'+siO.link+'">'+siO.link+'</a>\
						<a class="bold" title="'+siO.link+'" href="'+siO.link+'">'+siO.title+'</a>\
					</div>\
					<div class="simi-html-snippet">'+siO.htmlSnippet+'</div>\
				</div>');
			}
		}
	} else if (data.checkOnline.status == 'error') {
		$('#me-sub-plachecker').append('<div class="console error"><div class="csl-main"><b>Check Online</b> No internet connection</div></div>');
	} else if (data.checkOnline.status == 'disabled') {
		$('#me-sub-plachecker').append('<div class="console info"><div class="csl-main"><b>Check Online</b> This function is currently disabled.</div></div>');
	}
	freze(0);
}

function showCompile (data, output, formData, checkPlag, showAllTests) {
	freze(1);
	activeTab('console');
//	console.log(data);
	if (data.status == 'error') {
		$('#me-sub-compile').html('<div class="console error"><div class="csl-main"><b>Errors fetched</b> '+data.content+'</div></div>');
		freze(0);
	} else {
//		$('#me-sub-compile').html('<div class="console success"><div class="csl-main"><b>Compile success</b> Compile completed. <span class="strong">Time taken:</span> '+data.time+'</div></div>');
		console_html = '<div class="console success"><div class="csl-main"><b>Compile success</b> Compile completed. ';
		if (data.time) console_html += '<span class="strong">Time taken:</span> '+data.time;
		console_html += ' </div></div>';
		$('#me-sub-compile').html(console_html);
		if (output == true) {
			if (data.check == true) $('#me-sub-compile').append('<div class="console success"><div class="csl-main"><b>Correct</b> Output accepted</div><pre class="output">'+data.output+'</pre><div class="clearfix"></div></div>');
			else $('#me-sub-compile').append('<div class="console error"><div class="csl-main"><b>Wrong output</b> </div> <pre class="output">'+data.output+'</pre> </div>');
		}
		if (showAllTests == true) {
			$('#me-sub-noti').html(loading);
			testCases = '<div class="tc-one thead"><div class="tc-test">Test</div><div class="tc-input">Input</div><div class="tc-output">Output</div><div class="tc-soutput">Expected</div><div class="tc-time">Time taken</div><div class="tc-result">Result</div><div class="clearfix"></div></div>';
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
			$('#me-sub-noti').html(testCases);
			testDetails(tests);
		}
		if (checkPlag == true) checkPla (formData);
		else freze(0);
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
		html +=	 '<div class="csl-main">Time execution: '+testData.time+'</div>';
		html += '</div>';
		html += '<div class="si-one thead"> Input</div>';
		html += '<div class="input">';
		html +=	 '<pre class="pre_input">'+testData.input+'</pre>';
		html += '</div>';
		html += '<div class="si-one thead"> <div class="col-lg-6" style="padding:0 5px 0 0!important">Your output</div> <div class="col-lg-6" style="padding:0 0 0 5px!important">Correct output</div> <div class="clearfix"></div> </div>';
		html += '<div class="output-board">';
		html +=	 '<div class="col-lg-6" style="padding:0 5px 0 0!important"><pre class="pre_output">'+testData.output+'</pre></div>';
		html +=	 '<div class="col-lg-6" style="padding:0 0 0 5px!important"><pre class="pre_soutput">'+testData.soutput+'</pre></div>';
		html += '</div>';
		html += '<div class="clearfix"></div>';
		html += '</div>';
		popup_html(html);
		return false
	})
}

function compileCode () {
	$('#code-compile').click(function () {
		$('#me-sub-compile').html(loading);
		activeTab('console');
		var code = editor.getValue();
		newformat = FormatWithOption(editor);
		$('textarea#sub-code').val(code);
		$('textarea#sub-code-formatted').val(newformat);
		doCompile()
	})
}
function doCompile (formData, checkPlag, showAllTests) {
	freze(1);
	$current = $('.me-files .me-file.active');
	$current.text($current.text().replace('*', ''));
	if (!formData) formData = $('.code-area').serialize();
	$.ajax({
		url: '?do=compile',
		type: 'POST',
		data: formData,
		success: function (data) {
//			console.log(data);
			data = $.parseJSON(data);
			showCompile(data, true, formData, checkPlag, showAllTests);
		}
	})
}

function submitCode () {
	$('#code-submit').click(function () {
		$('#me-sub-noti, #me-sub-compile, #me-sub-plachecker').html(loading);
		activeTab('console');
		var code = editor.getValue();
		newformat = FormatWithOption(editor);
		$('textarea#sub-code').val(code);
		$('textarea#sub-code-formatted').val(newformat);
		doSubmit();
	})
}
function doSubmit (formData) {
	freze(1);
	$current = $('.me-files .me-file.active');
	$current.attr('data-submit', 1);
	disable(editor);
	if (!formData) formData = $('.code-area').serialize()+'&placonsole=true';
	$.ajax({
		url: '?do=submit',
		type: 'POST',
		data: formData,
		success: function (data) {
//			console.log(data);
			data = $.parseJSON(data);
			if (data.status == 'error') {
				enable(editor);
				$('#me-sub-noti').html('<div class="alert alert-error alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-warning"></i> Errors fetched!</h4> '+data.submit.content+'</div>');
			} else {
				$('.code-tool .btn').remove();
				if (data.compile.status == 'success') $current.addClass('tc-AC');
				else if (data.compile.status == 'error') $current.addClass('tc-WA');
				// update ranking
				updateRankings();
				$('#me-sub-noti').html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-warning"></i> Success!</h4> Your submission has been submitted successfully.</div>');
				//redirect();
			}
			showCompile(data.compile, true, formData, true, true);
		}
	})
}

function updateRankings () {
	$.post(MAIN_URL+"/u?do=updateRankings");
}

function newFile () {
	$('.new-file > li > a').click(function () {
		mode = $(this).attr('id');
		createNewFile(mode);
		return false
	})
}
function createNewFile (mode) {
		$.ajax({
			url: '?do=newfile',
			type: 'POST',
			data: 'lang='+mode,
			success: function (data) {
				$('#sub-num').val(data);
				if (mode == 'c_cpp') ext = 'cpp';
				else ext = mode;
//				$('.me-files > .me-file').removeClass('active');
				$('.me-files').prepend('<a class="list-group-item media me-file" data-id="'+data+'" data-submit="0" data-lang="'+ext+'" data-mode="'+mode+'"><span class="gensmall">['+ext+']</span> '+data+'.'+ext+'<div class="clearfix"></div></a>');
				$('.me-files > .me-file[data-id="'+data+'"][data-lang="'+ext+'"]').click(function () {
					openFile(data, ext, null, false, true);
				}).click();
//				editor.getSession().setValue('');
//				editor.getSession().setMode("ace/mode/"+ext);
			}
		});
}

function settings () {
	$('.problem-settings li a.setting').click(function () {
		var action = $(this).attr('id').split('p_')[1];
		if (action == 'del') {
			mtip('', 'warning', '', 'This feature is currently disabled, since permanently deleting a problem may cause a lot of errors. Please <b>hide</b> this problem for not allowing accessing instead.');
		} else {
			$.post("?do=settings", {action: action}).done(function (data) {
				mtip('', 'success', '', 'Updated successfully.');
				if (action == 'hide') $('#p_hide').html(($('#p_hide').html() == 'Show') ? 'Hide' : 'Show');
			});
		}
		return false
	})
}

$(document).ready(function () {
	$('.code-sub-area').hide();
	settings();

	newFile();
	$('.files-saved').append('<div class="console info"><div class="csl-main"><b>Ctrl+S</b> to save.</div></div>');

	//mtip('', 'info', 'Note', 'If coding area is empty, reloading the page shall help!')
	
	disable(editor);
	
//	submissionList();

/*	if (codeAreaID == 'me-sub-code') {
		disable(editor);
		simiDetail();
	} else {
		compileCode();
		submitCode();
		newFile();
	}
*/
	$('.me-files > .me-file:not(".disabled")').click(function () {
		openFile($(this).attr('data-id'), $(this).attr('data-lang'), null, false, true);
	});
	$('.me-files > .me-file:first').click();

	editor.getSession().on('change', function () {
		content = editor.getSession().getValue();
		if (!fromSetValue) {
			$current = $('.me-files .me-file.active');
			var txt = $current.text();
			if (txt.indexOf('*') <= -1) $current.text(txt+"*");
		}
	})

	// update on change
/*	editor.getSession().on('change', function () {
		content = editor.getSession().getValue();
		if (!fromSetValue) {
			content = editor.getSession().getValue();
			id = $('.me-files .me-file.active').attr('data-id');
			lang = $('.me-files .me-file.active').attr('data-lang');
			$.post("?do=update", {id: id, lang: lang, content: content});
		}
	})
*/
});
