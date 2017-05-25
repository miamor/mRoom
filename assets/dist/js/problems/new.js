/*
filedrag.js - HTML5 File Drag & Drop demonstration
Featured on SitePoint.com
Developed by Craig Buckler (@craigbuckler) of OptimalWorks.net
*/
(function() {

	// getElementById
	function $id(id) {
		return document.getElementById(id);
	}


	// output information
	function Output(msg) {
		var m = $id("messages");
		m.innerHTML = '<div class="one-mes">'+msg+'</div>' + m.innerHTML;
	}


	// file drag hover
	function FileDragHover(e) {
		e.stopPropagation();
		e.preventDefault();
		e.target.className = (e.type == "dragover" ? "hover" : "");
	}


	// file selection
	function FileSelectHandler(e) {

		// cancel event and hover styling
		FileDragHover(e);

		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;

		// process all File objects
		for (var i = 0, f; f = files[i]; i++) {
			UploadFile(f);
		}

	}


	// output file information
	function ParseFile(file) {

		out = outp = "<p>" +
			" Name: <strong>" + file.name + " </strong><br/>" + 
			" Type: <strong>" + file.type + " </strong><br/>" + 
			" Size: <strong>" + file.size + " </strong> bytes" + 
			" </p>";

		// display an image
		if (file.type.indexOf("image") == 0) {
			var reader = new FileReader();
			reader.onload = function(e) {
				out = "<p><strong>" + file.name + ":</strong><br />" +
					'<img src="' + e.target.result + '" /></p>' +
					outp;
				Output(out);
			}
			reader.readAsDataURL(file);
		}

		// display text
		else if (file.type.indexOf("text") == 0) {
			var reader = new FileReader();
			reader.onload = function(e) {
				out = "<p><strong>" + file.name + ":</strong></p><pre>" +
					e.target.result.replace(/</g, "&lt;").replace(/>/g, "&gt;") +
					"</pre>" + 
					outp;
				Output(out);
			}
			reader.readAsText(file);
		}
		
		else Output(out);
	}


	// upload JPEG files
	function UploadFile(file) {

		// following line is not necessary: prevents running on SitePoint servers
		if (location.host.indexOf("sitepointstatic") >= 0) return

		accepted = [".zip",
		"application/octet-stream",
		"application/zip",
		"application/x-zip",
		"application/x-zip-compressed"];
		
		console.log($.inArray(file.type, accepted));
		
		var xhr = new XMLHttpRequest();
		if (xhr.upload && $.inArray(file.type, accepted) > -1 && file.size <= $id("MAX_FILE_SIZE").value) {

			ParseFile(file);

			// create progress bar
			var o = $id("progress");
			var progress = o.appendChild(document.createElement("div"));
			prtext = progress.appendChild(document.createElement("span"));
			prtext.appendChild(document.createTextNode("uploaded " + file.name));


			// progress bar
			xhr.upload.addEventListener("progress", function(e) {
				var pc = parseInt(100 - (e.loaded / e.total * 100));
				progress.style.backgroundPosition = pc + "% 0";
			}, false);

			// file received/failed
			xhr.onreadystatechange = function(e) {
					console.log(xhr.status+'~~~~~');
				if (xhr.readyState == 4) {
					progress.className = (xhr.status == 200 ? "progress-bar-green" : "progress-bar-red");
				}
			};

			// remove form
			if (xhr.status == 0) $('.upload').remove();

			// start upload
			console.log($("#upload").attr('action')+'~~~~');
			xhr.open("POST", $("#upload").attr('action'), true);
			xhr.setRequestHeader("X_FILENAME", file.name);
			xhr.send(file);
			console.log($('#fileselect').val());

		} else mtip('#messages', 'error', 'Error', 'only <b>.zip</b>, <b>.tar</b>, <b>.gz</b> file is acceptable.')

	}


	// initialize
	function Init() {

		var fileselect = $id("fileselect"),
			filedrag = $id("filedrag"),
			submitbutton = $id("submitbutton");

		// file select
		fileselect.addEventListener("change", FileSelectHandler, false);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {

			// file drop
			filedrag.addEventListener("dragover", FileDragHover, false);
			filedrag.addEventListener("dragleave", FileDragHover, false);
			filedrag.addEventListener("drop", FileSelectHandler, false);
			filedrag.style.display = "block";

			// remove submit button
			submitbutton.style.display = "none";
		}

	}

	// call initialization file
	if (window.File && window.FileList && window.FileReader) {
		Init();
	}


})();


$(function(){

    $('#browse').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $('#fileselect').click();
    });

})

