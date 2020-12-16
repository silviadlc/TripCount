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
	let form = document.getElementById("form");
	let input = document.createElement("input");         
	input.type = 'email';
	input.placeholder = "Introduce correo electrónico";
	let newline = document.createElement('br');
	form.appendChild(newline);
	form.appendChild(input);
}
window.onload = addInput()
