;
$.sceditor.command
	.set("html5video", {
		exec: function(caller){ this.insert("[html5video]", "[/html5video]", false); },
		txtExec: ["[html5video]", "[/html5video]"],
		tooltip: "HTML5Video"
	})
    .set("html5audio", {
		exec: function(caller){ this.insert("[html5audio]", "[/html5audio]", false); },
		txtExec: ["[html5audio]", "[/html5audio]"],
		tooltip: "HTML5Audio"
	}
);
