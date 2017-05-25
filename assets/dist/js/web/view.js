var editor = ace.edit('me-sub-code-area');
editor.setTheme("ace/theme/"+ACE_THEME);
var meCode;
var fromSetValue = false;
editor.getSession().setUseWrapMode(true);
editor.getSession().setWrapLimitRange(null, null);

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
			$current = $('.me-web-list .me-file.active');
			$current.text($current.text().replace('*', ''));
			var cDir = $current.attr('data-dir');
			$.post("?do=update", {dir: cDir, content: content});
		}
}

function openFile (dir) {
	$a = $('.me-file[data-dir="'+dir+'"]');
	aN = $a.text();
	if (!$a.next('ul').length) { // if not folder
		meCode = $a.attr('data-type');
		if (aN.match(/\.(jpg|jpeg|png|gif|svg|mp4|wav|mp3)$/)) // if image
		;
		else {
			$('.me-file').removeClass('active');
			$a.addClass('active');
			$.ajax({
				url: '?do=getfile',
				type: 'POST',
				data: 'dir='+dir,
				success: function (data) {
					if (data == -1) 
						mtip('', 'error', '', 'No files found.');
					else {
						if (meCode == 'cpp') meCode = 'c_cpp';
						editor.getSession().setMode("ace/mode/"+meCode);
						fromSetValue = true;
						editor.getSession().setValue(data);
						fromSetValue = false;
					}
				}
			});
		}
	}
}

function toggleFolder ($a) {
	if ($a.next('ul').length) { // if folder
		$a.next('ul').hide();
		$a.prepend('<i class="fa fa-caret-right"></i> ');
		$a.click(function () {
			$a.children('.fa').removeClass('fa-caret-right fa-caret-down');
			if ($a.next('ul').is(':hidden')) $a.children('.fa').addClass('fa-caret-down');
			else $a.children('.fa').addClass('fa-caret-right');
			$a.next('ul').toggle(300);
		})
	}
}

$(document).ready(function () {
	$('.me-file').each(function () {
		toggleFolder($(this));
	}).click(function () {
		openFile($(this).attr('data-dir'));
	}).bind("contextmenu", function (event) {
		dir = $(this).attr('data-dir');
		// Avoid the real one
		event.preventDefault();
		// Show contextmenu
		$(".custom-menu").attr('id', dir).finish().toggle(100).
		// In the right position (the mouse)
		css({
			top: (event.pageY - 52) + "px",
			left: event.pageX + "px"
		});
	});
	$('.me-file.active').click();

	$(".custom-menu li").click(function () {
		dir = $('.custom-menu').attr('id');
		// This is the triggered action name
		switch($(this).attr("data-action")) {
			// A case for each action. Your actions here
			case "preview": 
				openInNewTab(dir);
				break;
			case "delete": 
				alert("delete"); 
				break;
			case "permissions": 
				alert("third"); 
				break;
		}
		// Hide it AFTER the action was triggered
		$(".custom-menu").hide(100);
	});

	editor.getSession().on('change', function () {
		content = editor.getSession().getValue();
		if (!fromSetValue) {
			$current = $('.me-files .me-file.active');
			var txt = $current.text();
			if (txt.indexOf('*') <= -1) $current.text(txt+"*");
		}
	})
}).bind("mousedown", function (e) {
	// If the clicked element is not the menu
	if (!$(e.target).parents(".custom-menu").length > 0) {
		// Hide it
		$(".custom-menu").hide(100);
	}
})
