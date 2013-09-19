/* https://github.com/davidfig/name-generator */

var highlightColor = "rgb(200, 200, 200)";

function nameLists(type) {
	var lists = "";
	$("." + type).each(function(index) {
		if ($(this).css("background-color") == highlightColor) {
			lists += $(this).attr("name") + ",";
		}				
	});
	if (lists.length) {
		lists = lists.substring(0, lists.length - 1);				
	}
	return lists;
}

function generateName() {
	if ($("#generate").button("option", "disabled")) {
		return;
	}

	var lists = nameLists("givenName");
	if (lists.length) {
		lists = "given=" + lists;
	}
	var family = nameLists("familyName");
	if (family) {
		if (lists.length) {
			lists = lists + "&";
		}
		lists = lists + "family=" + family;
	}
	if (lists.length == 0) {
		$("#results").html("Please include at least one Source.");
		$("#results").effect("highlight", {}, 3000);
	} else {
		$("#generate").button("option", "disabled", true);
		$.get("generate.php?" + lists 
			+ "&randomize=" + ($("#randomize").is(":checked")?"true":"false")
			+ "&cache = " + Math.floor(Math.random()*100),
			function (data) {
				$("#results").html(data);
				$("#generate").button("option", "disabled", false);
				$(".result").click(function() { 
					$(this).selectText(); 
				});
		});
	}
}

function selectRow(e) {
	if ($(this).css("background-color") == highlightColor) {
		$(this).css("background-color", "");
		$(this).find("td:first").html("");
	} else {
		$(this).css("background-color", highlightColor);
		$(this).find("td:first").html("&#x2713");
	}			
}

/* From http://stackoverflow.com/questions/9975707/use-jquery-select-to-select-contents-of-a-div */
jQuery.fn.selectText = function(){
    var doc = document
        , element = this[0]
        , range, selection
    ;
    if (doc.body.createTextRange) {
        range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        selection = window.getSelection();        
        range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
};

$(document).ready(function() {			
	$(window).bind("keypress", function(e){
		if (e.keyCode == 13) {
			generateName();
		}
	});
	$(document).tooltip();
	$("#radio").buttonset();
	$("#generate").button().click(generateName);
	$(".givenName").click(selectRow);	
	$(".familyName").click(selectRow);
	$(".givenName").css("cursor","pointer");
	$(".familyName").css("cursor","pointer");
	$(".default").css("background-color", highlightColor);
	
	generateName();		
});

$(window).load(function() {
	$("#t2col0").width($("#t1col0").width());
	$("#t2col1").width($("#t1col1").width());
	$("#t2col2").width($("#t1col2").width());
	$("#t2col3").width($("#t1col3").width());		
});