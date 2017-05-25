$(document).ready(function () {
	$.getJSON(MAIN_URL+"?do=topusers", function (data) {
		var items = [];
		$.each(data, function (key, val) {
			items.push('<li class="one-user" id="' + key + '"><div class="col-lg-10 no-padding"><a class="left" data-online="'+val['online']+'" href="'+val['link']+'"><img class="img-circle" src="'+ val['avatar'] +'"/></a><a href="'+val['link']+'">'+val['name']+'</a></div><div class="col-lg-2 no-padding" style="text-align:right">'+val['score']+'</div><div class="clearfix"></div></li>');
		});
		$("<ol/>", {
			"class": "topusers",
			html: items.join("")
		}).appendTo("#topusers");
		icons('topusers');
	});

	$.getJSON(MAIN_URL+"?do=topteams", function (data) {
		var items = [];
		$.each(data, function (key, val) {
			items.push('<li class="one-user" id="' + key + '"><div class="col-lg-10 no-padding"><a href="'+val['link']+'">'+val['title']+'</a></div><div class="col-lg-2 no-padding" style="text-align:right">'+val['score']+'</div><div class="clearfix"></div></li>');
		});
		$("<ol/>", {
			"class": "topusers top-teams",
			html: items.join("")
		}).appendTo("#topteams");
	});

	$.getJSON(MAIN_URL+"?do=categories", function (data) {
		var items = [];
		$.each(data, function (key, val) {
			items.push('<a class="problems-tag" title="'+val['num']+' topics" href="'+val['title']+'">'+val['title']+' <span class="tag-num">'+val['num']+'</span></a>');
		});
		$("<div/>", {
			"class": "categories",
			html: items.join("")
		}).appendTo("#cats");
	});

	$('#pList').DataTable()
})
