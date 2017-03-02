// What to add in the editor window when the button is clicked, based on which mode the editor is in
$.sceditor.command
	.set("html5video", {
		txtExec: ["[html5video]", "[/html5video]"],
		tooltip: "HTML5Video"
	})
    .set("html5audio", {
		txtExec: ["[html5audio]", "[/html5audio]"],
		tooltip: "HTML5Audio"
	}
);
