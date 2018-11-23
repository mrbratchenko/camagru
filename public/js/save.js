function saveImg(page) {
	var canvas = document.getElementById("canvas");
	var context = canvas.getContext("2d");
	context.drawImage(canvas, 0, 0, 0, 0);
	var data = canvas.toDataURL();
	var img = canvas.toDataURL('image/png').replace('data:image/png;base64', '');
	var request = new XMLHttpRequest();
	request.open('POST', '/gallery/saveimg/', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	request.onreadystatechange = function() {
		if(request.readyState == 4 && request.status == 200) {
			document.getElementById('frame').innerHTML = request.responseText;
			var texts = document.getElementsByClassName('text-comment');

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
		}
	}
	request.send('img=' + img + '&page=' + page);
}
