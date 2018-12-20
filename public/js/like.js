function likeFunct(path){
	var request = new XMLHttpRequest();
	var heart = document.getElementById(path + '_like').childNodes[1];
	var likes = document.getElementById(path + '_like').childNodes[7];
	if (!likes){
		likes = document.getElementById(path + '_like').childNodes[5];
	}
	var heart_mod = document.getElementById(path + '_like_modal').childNodes[1];
	var likes_mod = document.getElementById(path + '_like_modal').childNodes[7];
	if (!likes_mod){
		likes_mod = document.getElementById(path + '_like_modal').childNodes[5];
	}
	request.open('POST', '/gallery/like/', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	request.onreadystatechange = function() {
	if(request.readyState == 4 && request.status == 200) {
		
			var responseArr = request.responseText.split("+");
			var word = responseArr[0] == 1 ? ' like' : ' likes';
			likes.innerHTML = responseArr[0] + word;
			heart.className = responseArr[1];
			likes_mod.innerHTML = responseArr[0] + word;
			heart_mod.className = responseArr[1];
		}
	}
	var res = request.send('path='+path);
}
