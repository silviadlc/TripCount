function closeAlert(elementAlert) {
	elementAlert.parentElement.remove();
}

function selectRedirectOrderInput(valueOrder) {
	switch(valueOrder) {
		case '1':
			window.location.href = "?order=1";
			break;
		case '2':
			window.location.href = "?order=2";
			break;
	}
}
