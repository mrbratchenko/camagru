function addComment(path, com){

	var btns = document.getElementsByClassName('com-button');
	for (var i = 0; i < btns.length; i++) {
		 btns[i].disabled = true;
	}
	var btns_mod = document.getElementsByClassName('com-button-mod');
	for (var j = 0; j < btns_mod.length; j++) {
		btns_mod[j].disabled = true;
	}
	var request = new XMLHttpRequest();
	
	document.getElementById(path + '_text').value = null;
	document.getElementById(path + '_text_modal').value = null;
	var comments = document.getElementById(path + '_comments');
	var comments_mod = document.getElementById(path + '_comments_modal');

	request.open('POST', '/gallery/addComment/', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	
	request.onreadystatechange = function() { 
	if(request.readyState == 4 && request.status == 200) {
			var responseArr = JSON.parse(request.responseText);
			var new_comment = document.createElement('div');
			var text =  '<b>' + responseArr['user'] + '</b> ' + responseArr['comment'] + '<br>';
			var text_short =  '<b>' + responseArr['user'] + '</b> ' + responseArr['comment'].slice(0, 26);
			if (text_short.length > 26){
				text_short += '...<br>';
			}
			else{
				text_short += '<br>';
			}
			text = text.replace(/\r|\n|\r\n/g, '<br>');
			comments.appendChild(new_comment);
			
			new_comment.id = responseArr['id'];

			var previous = new_comment.previousElementSibling;
			if (previous){
				previous.style.display = 'none';
			}
			new_comment_mod = new_comment.cloneNode(true);
			new_comment.innerHTML = text_short;
			new_comment_mod.innerHTML = text;
			new_comment_mod.id = 'mod_' + responseArr['id'];
			comments_mod.appendChild(new_comment_mod);

		}
	}
	request.send('path=' + path + '&com=' + com);
}


function deleteComment(id){

	var if_mod = null;
	if (id.substring(0, 4) == 'mod_'){
		id = id.substring(4);
		if_mod = 1;
	}
	var request = new XMLHttpRequest();
	var comm_to_del = document.getElementById(id);
	var comm_to_show = comm_to_del.previousElementSibling;
	var comm_to_del_mod = document.getElementById('mod_' + id);
	request.open('POST', '/gallery/deleteComment/', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	request.onreadystatechange = function() {
	if(request.readyState == 4 && request.status == 200) {
			comm_to_del.style.display = 'none';
			comm_to_del_mod.style.display = 'none';
			if (comm_to_show){
				comm_to_show.style.display = '';
			}
		}
	}
	request.send('id='+id);
}

var texts = document.getElementsByClassName('text-comment');

for (i = 0; i < texts.length; i++) {
	texts[i].addEventListener('input', function () {
	if (this.value && !emojis(this.value)){
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
	if (this.value && !emojis(this.value)){
		this.nextElementSibling.disabled = false;
	}
	else{
		this.nextElementSibling.disabled = true;
	}
});
}

function enter(key, path, com){
	if (key == 'Enter' && com !=''){
		addComment(path,com);
		document.getElementById(path + '_text').blur();
		document.getElementById(path + '_text_modal').blur();
	}
	else if (key == 'Enter' && com ==''){
		document.getElementById(path + '_text').blur();
		document.getElementById(path + '_text_modal').blur();
	}
}

function emojis (string) {
	var rgx = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|[\ud83c[\ude01\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|[\ud83c[\ude32\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|[\ud83c[\ude50\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;
	var res = string.replace(rgx, '');
	if (res == ''){
		return true;
	}
	else{
		return false;
	}
}