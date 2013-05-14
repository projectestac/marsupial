/* IECISA -> MPS ************* ADDED -> jquery code for te viewer */

$(document).ready(function(){
	// start loading the log
	loadlog(true);
});

var interval = '';
var errortimes = 0;

function loadlog (isfirsttime){
    //alert("function loadlog start"); //debug mode
	
	//call log loader
	$.ajax({ 
	    url: 'application/viewer/viewer.inc.php', 
		type: "POST", 
		data:({filter: filter, lasttime: $('#lastid').val()}), 
		dataType: "json",  
		error: function (XMLHttpRequest, textStatus){
		    //alert("error text response: "+textStatus);  //debug mode
			loadlogerror();
		},
		success: function(data){
			//alert(data.response);  //debug mode
			
		    // check if isset error message and take it out
		    if ($('.content-body p')){
			    $('.content-body p').remove();
			}
			// process response
	        if (data.response != "KO"){
			    data = data.response;
				// parse data
				htm = '';
			    for (var i in data){
					response = data[i];
					// print data
					htm += '<tr height="20"><td>'+response.ip+'</td><td>'+response.smarttime+'</td><td>'+response.category+'</td><td class="norightborder">'+response.info+'</td></tr>';
				}
				if (htm != ''){
					//print data
					$('.firsttr').before(htm);
					// coloure rows
					$(".content-body tr:odd").css("background-color", "#ccc"); // filas impares
                    $(".content-body tr:even").css("background-color", "#fff"); // filas pares
					$(".content-body tr:last").css("background-color", "#ccc"); // filas ultima
					//do scroll
					if (isfirsttime){
						v=0;
					} else {
						v=500;
					}
					$('.divtable').scrollTo('.firsttr', v);  //, {easing:'elasout'} 
					// take note of the last printed time
					$('#lastid').val(response.id);
					$('#lasttimediv').html(lasttimetext+response.smarttime);
				}
				
			} else if (data.response == "KO"){
			    loadlogerror();
			}
	    }
	
	});
	
	//program reload
	if (isfirsttime){
	    interval = setInterval(function(){ loadlog(false);}, 5000);
	}
}

function loadlogerror (){
    if (errortimes == 0) {
	    if ($('.content-body p')){
	        $('.content-body p').remove();
	    }
		$('.content-body').append('<p>Error getting log entries. 3 try left...</p>');
		errortimes++;
	} else if (errortimes == 3) {
	    clearInterval(interval);
		errortimes = 0;
	    $('.content-body p').html('<p>Error getting log entries. Imposible to retry please contact with the webmaster or <a href="javascript:loadlog(true);">try again</a>.</p>');
	} else {
	    $('.content-body p').html('<p>Error getting log entries.  '+(3-errortimes)+' try left...</p>');
		errortimes++;
	}
}

/* ************** END */