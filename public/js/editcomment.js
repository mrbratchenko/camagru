function editComment(path, com){
	var  data = 'path='+path+'&com='+com;
	var request = new XMLHttpRequest();
	request.open('POST', '/gallery/commentphoto/', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	request.onreadystatechange = function() {
	if(request.readyState == 4 && request.status == 200) {
			document.getElementById('frame').innerHTML = request.responseText;
		}
	}
	request.send('path='+path+'&com='+com);
}
