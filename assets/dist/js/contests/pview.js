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

editor.commands.addCommand({
	name: 'saveFile',
	bindKey: {
		win: 'Ctrl-S',
		mac: 'Command-S',
		sender: 'editor|cli'
	},
	exec: function (env, args, request) {
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
});

function setOutputMsg(msg) {
	$("#outputMsg").html("Result : " + msg);
}

function FormatWithOption (editor) {
//	var editor = ace.edit(codeAreaID);
	var oldformat = editor.getValue() , newformat = "";
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
				editor.getSession().setMode("ace/mode/"+meCode);
				fromSetValue = true;
				editor.getSession().setValue(codeContent);
				fromSetValue = false;
				codeToolHTML = '<ul class="nav nav-tabs">\
			<li><a href="#noti" data-toggle="tab">Notification</a></li>\
			<li class="active"><a href="#console" data-toggle="tab">Execute</a></li>\
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
					//if (data.console) showCompile(data.console, true);
					if (data.console) showCompile(data.console, true, true);
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

function showCompile (data, output, showGrade) {
	freze(1);
	activeTab('console');
	//console.log(data);
	if (data.status == 'error') {
		$('#me-sub-compile').html('<div class="console error"><div class="csl-main"><b>Errors fetched</b> '+data.content+'</div></div>');
	} else {
		console_html = '<div class="console success"><div class="csl-main"><b>Compile success</b> Compile completed. ';
		if (data.time) console_html += '<span class="strong">Time taken:</span> '+data.time;
		console_html += ' </div></div>';
		$('#me-sub-compile').html(console_html);
		if (output == true) {
			if (data.check == true) $('#me-sub-compile').append('<div class="console success"><div class="csl-main"><b>Correct</b> Output accepted</div><pre class="output">'+data.output+'</pre><div class="clearfix"></div></div>');
			else $('#me-sub-compile').append('<div class="console error"><div class="csl-main"><b>Wrong output</b> </div> <pre class="output">'+data.output+'</pre> </div>');
		}
		if (showGrade == true) {
			$('#me-sub-noti').html(loading);
			testCases = '<div class="col-lg-9 no-padding">';
			testCases += '<div class="tc-one thead"><div class="tc-test">Test</div><div class="tc-output">Output</div><div class="tc-time">Time taken</div><div class="tc-result">Result</div><div class="clearfix"></div></div>';
			tests = data.tests;
			grade = 0; correct = 0;
			for (var i in tests) {
				test = tests[i];
				if (test['check'] == true) {
					correct++;
					grade += 100/Object.keys(tests).length;
				}
/*				test['input'] = $.trim(test['input']).replace(/(?:\r\n|\r|\n)/g, '<br />');
				test['soutput'] = $.trim(test['soutput']).replace(/(?:\r\n|\r|\n)/g, '<br />');
				test['input_short'] = (test['input'].length > 100) ? test['input'].substr(0, 100)+'...' : test['input'];
				test['soutput_short'] = (test['soutput'].length > 100) ? test['soutput'].substr(0, 100)+'...' : test['soutput'];
*/				test['output'] = $.trim(test['output']).replace(/(?:\r\n|\r|\n)/g, '<br />');
				test['output_short'] = (test['output'].length > 100) ? test['output'].substr(0, 100)+'...' : test['output'];
				testCases += '<div class="tc-one tc-'+test['checkTxt']+'"><div class="tc-test"><a href="#t'+i+'" id="'+i+'" class="test-details">#'+i+'</a></div><div class="tc-output">'+test['output_short']+'</div><div class="tc-time">'+test['time']+'</div><div class="tc-result submission-scored submission-'+test['checkTxt']+'"><img class="media-object" src="'+IMG+'/'+test['checkTxt']+'.png" width="12" height="12"> '+test['checkTxt']+'</div><div class="clearfix"></div></div>';
//				testCases += '<div class="tc-one tc-'+test['checkTxt']+'"><div class="tc-test"><a href="#t'+i+'" id="'+i+'" class="test-details">#'+i+'</a></div><div class="tc-input">'+test['input_short']+'</div><div class="tc-output">'+test['output_short']+'</div><div class="tc-soutput">'+test['soutput_short']+'</div><div class="tc-time">'+test['time']+'</div><div class="tc-result submission-scored submission-'+test['checkTxt']+'"><img class="media-object" src="'+IMG+'/'+test['checkTxt']+'.png" width="12" height="12"> '+test['checkTxt']+'</div><div class="clearfix"></div></div>';
			}
			testCases += '</div>';
			testCases += '<div class="col-lg-3 no-padding-right">\
				<div class="c-submission-grade">'+grade+'</div>\
			</div><style>.tc-time{width:20%}.tc-output{width:60%}</style>';
			activeTab('noti');
			$('#me-sub-noti').html(testCases);
			testDetails(tests);
		}
	}
	freze(0);
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
		html += '<div class="si-one thead"> <div class="col-lg-12" style="padding:0 5px 0 0!important">Your output</div> <div class="clearfix"></div> </div>';
		html += '<div class="output-board">';
		html +=	 '<div class="col-lg-12" style="padding:0 5px 0 0!important"><pre class="pre_output">'+testData.output+'</pre></div>';
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
function doCompile (formData) {
	freze(1);
	disable(editor);
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
			showCompile(data, true, false);
			enable(editor);
		}
	})
}

function submitCode () {
	$('#code-submit').click(function () {
		$('#me-sub-noti').html(loading);
		$('#me-sub-compile').html(loading);
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
			//console.log(data);
			data = $.parseJSON(data);
			if (data.status == 'error') {
				enable(editor);
				$('#me-sub-noti').html('<div class="alert alert-error alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-warning"></i> Errors fetched!</h4> '+data.submit.content+'</div>');
			} else {
				$('.code-tool .btn').remove();
				updateRankings();
				$('#me-sub-noti').html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-warning"></i> Success!</h4> Your submission has been submitted successfully.</div>');
				//redirect();
			}
			showCompile(data.compile, true, true);
//			showCompile(data.compile, true, false);
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


$(document).ready(function () {
	$('.code-sub-area').hide();
	newFile();
	$('body').append('<div class="alerts alert-info">Ctrl+S to save.</div>');

	disable(editor);
	
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

});
