function setSiteState(state) {
	var setState = '/index/set/state/' + state + '?dt=' + getDT();
	jQuery.ajax({
		url: setState,
		//async: false,
		type: 'POST',
		success: function() {
			displayPage();
		}		
	});
}
/**
 * Unique date function
 * 
 * @return integer
 */
function getDT() {
	return new Date().getTime();
}

function generateUrl(user) {
	url = '/site/view/owner/' + user + '/?p=' + getDT();
	return url;
}

/*
function displayPage() {
	jQuery.ajax({
		url: url,
		type: 'GET',
		//async: false,
		success: function(data) {
			jQuery("#remote-site").contents().find('html').html(data);
		},
		error: function(request, status, error) {
			jQuery("#remote-site").contents().find('html').html(request.responseText);
			//console.log(request.responseText);
		}
	});
}
*/
