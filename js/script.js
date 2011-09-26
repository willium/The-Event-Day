//## IS INTEGER ##//
function is_int(value){ 
	if((parseFloat(value) == parseInt(value)) && !isNaN(value))
 	 	return true;
	else 
  		return false;
}

//## LONG TEXT SCALE DOWN ##//
var fitText = function() {
	$(".text-fit").each(function() {		
		var $this = $(this);
		var boxWidth = $(this).css("width");
		var fontSize = parseInt($(this).css("fontSize"), 10);
		var $line;
		var lineWidth;
	
		$this.html(function(i,v) {
			return $("<span>").html(v).css("whiteSpace", "nowrap");
		});
		
		$line = $this.children("span").first();
		lineWidth = $line.css("width");
		
		while ( lineWidth > boxWidth ) {
			fontSize -= 1;
			$line.css("fontSize", fontSize + "pt");
			lineWidth = $line.css("width");
		}
		if (is_int($this.attr('rel'))) {
			max = $this.attr('rel');
			if(fontSize > max) fontSize = max;
		}
		
		$line.css("fontSize", fontSize + "pt");
	});
}

$(function(){
	//## SEARCH ##//
    $('input#search').quicksearch('.block', {
	    'noResults': '#no_results'
    });

	//## PLACEHOLDER ##//
	$('input, textarea').placeholder();

	//## Custom Scripts ##//
	$('#logout').attr("href", $("#logout").attr("href")+"?return="+location.href);

	$("#table .item").each(function(index) {
		
	});
});