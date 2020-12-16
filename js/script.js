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

function addInput() {
	console.log(document.getElementsByClassName('emailList'));
	console.log(document.getElementsByTagName('li'));
	
}
window.onload = addInput();