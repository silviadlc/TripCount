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
	var form = document.getElementById("newEmailList");
	var newInput = document.createElement("input");
	newInput.setAttribute('id',"inputEmail");         
	newInput.setAttribute('name',"emailsList[]");     
	newInput.setAttribute('type', "email");
	newInput.setAttribute('placeholder',"Introduce correo electrónico");
	newInput.setAttribute('required','');
	form.insertBefore(newInput, form.childNodes[0]);
}

function deleteInput() {
	var form = document.getElementById("newEmailList");
	var newInput = document.createElement("input"); 
	form.removeChild(form.firstChild);
}

function showDetails(id) {
	addClass = "details"+id;
	row = document.getElementsByClassName(addClass);
	if (row[0].style.display != 'table-row') {
	   for (let index = 0; index < row.length; index++) {
		  const detail = row[index];
		  detail.style.display = 'table-row';
	   }
	} else {
	   for (let index = 0; index < row.length; index++) {
		  const detail = row[index];
		  detail.style.display = 'none';
	   }
	}
 } 
