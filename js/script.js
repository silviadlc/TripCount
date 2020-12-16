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

function showFormCustom(elementFormById) {
	let elementForm = document.getElementById(elementFormById);
	elementForm.style.display = 'block';
	window.location.href = '#' + elementForm.id;
}
function addInput() {
	var form = document.getElementById("form");
	var newInput = document.createElement("input");    
	newInput.setAttribute('name',"emailsList[]");     
	newInput.setAttribute('type', "email");
	newInput.setAttribute('placeholder',"Introduce correo electrónico");
	newInput.setAttribute('required','');
	var newline = document.createElement("br");
	form.appendChild(newline);
	form.appendChild(newInput);
}
