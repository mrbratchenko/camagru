function deletePhoto(page, path){
	var txt;
	if (!confirm("Are you sure you want to delete this photo?")) {
		return ;
	} else {
	var request = new XMLHttpRequest();
	request.open('POST', '/gallery/deletephoto/', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	request.onreadystatechange = function() {
	if(request.readyState == 4 && request.status == 200) {
			document.getElementById('frame').innerHTML = request.responseText;
			for (i = 0; i < texts.length; i++) {
				texts[i].addEventListener('input', function () {
				if (this.value){
					this.nextElementSibling.disabled = false;
				}
				else{
					this.nextElementSibling.disabled = true;
				}
			});
			}
			var texts_mod = document.getElementsByClassName('text-comment-mod');
			for (j = 0; j < texts_mod.length; j++) {
				texts_mod[j].addEventListener('input', function () {
				if (this.value){
					this.nextElementSibling.disabled = false;
				}
				else{
					this.nextElementSibling.disabled = true;
				}
			});
			}
			var comments = document.getElementsByClassName('comment');
			for (k = 0; k < comments.length; k++) {
				if (comments[k].lastElementChild){
					 comments[k].lastElementChild.style.display = '';
				 }
			}
			document.getElementById('header').style.display = 'none';
			document.getElementById('footer').style.display = 'none';
		}
	}
	request.send('path='+path + '&page='+page);
	}
}
