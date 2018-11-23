function modalFunct(path){
	var modal = document.getElementById(path + '_modal');
	modal.style.display = "block";
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
		}
	}
}

function closeModal(path){
	var modal = document.getElementById(path + '_modal');
	modal.style.display = "none";
}

function moveCursor(path){
	document.getElementById(path + '_text_modal').focus();
}
