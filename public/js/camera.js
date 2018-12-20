var checkbox = document.getElementById("switch");
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var clones = [];

function video(){
	var video = document.getElementById('video');
	var load = document.getElementById('load');
	var div = document.getElementById('movepics');
	if(checkbox.checked==true){
		while (div.firstChild) {
			div.removeChild(div.firstChild);
		}
		video.style.visibility = 'visible';
		load.style.visibility = 'hidden';
		document.getElementById("switch").disabled = true;
		document.getElementById("snap").disabled = true;
		document.getElementById("save").disabled = true;
		document.getElementById("getval").value = "";
		while (div.firstChild) {
			div.removeChild(div.firstChild);
		}
	if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
		navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
				try {
				  video.srcObject = stream;
				} catch (error) {
				  video.src = window.URL.createObjectURL(stream);
				}
				video.play();
				video.onloadeddata = function(){
					document.getElementById("switch").disabled = false;
				}
			});
		}
	}
	else {
		while (div.firstChild) {
			div.removeChild(div.firstChild);
		}
		video.pause();
		video.srcObject.getTracks()[0].stop();
		video.style.visibility = 'hidden';
		load.style.visibility = 'visible';
		document.getElementById("snap").disabled = true;
		document.getElementById("save").disabled = true;
		context.clearRect(0, 0, canvas.width, canvas.height);

	}
}

function clone(elem){
	
	if (checkbox.checked==true || document.getElementById("getval").files[0]){
		document.getElementById("snap").disabled = false;
	var buttonPlus = document.getElementById('enlarge');
	var buttonMinus = document.getElementById('shrink');
	var del = document.getElementById('delete');
	var clone = elem.cloneNode(true);
	var div = document.getElementById("movepics");
	
	div.appendChild(clone);
	clone.style.zIndex = 99;
	clone.style.position = 'absolute';
	clone.style.onmouseover = "";
	clone.onclick='';
	clone.className = 'clone';
	clones.push(clone);

	del.onclick = function(){
		if (div.hasChildNodes()){
			div.removeChild(div.lastChild);
			if (!div.hasChildNodes()){
				document.getElementById("snap").disabled = true;
				document.getElementById("save").disabled = true;
			}
		}
	}

	buttonMinus.onclick = function(elem){
		if (clone.offsetWidth < 50)
			return ;
		clone.style.width = clone.offsetWidth - 10 + "px";
		clone.style.height = "auto";
	}

	buttonPlus.onclick = function(elem){
		if (clone.offsetWidth > 300)
			return ;
		clone.style.width = clone.offsetWidth + 10 + "px";
		clone.style.height =  "auto";
	}

	clone.onmousedown = function(elem) {
		var pos = getPos(clone);
		var bound = getPosDiv();
		var shiftX = elem.pageX - pos.left;
		var shiftY = elem.pageY - pos.top;
		var wid = clone.clientWidth;
		var height = clone.clientHeight;
		move(elem);
		function move(elem) {
			var position = getPos(clone);
			if (position.left + wid > bound.right){
				clone.style.left = bound.right - wid;
				document.onmousemove = null;
				return;
			}
			else if (position.left < bound.left){
				clone.style.left = bound.left;
				document.onmousemove = null;
				return;
			}
			else{
				clone.style.left = elem.pageX - shiftX + 'px';
			}

			if (position.top < bound.top){
				clone.style.top = bound.top ;
				document.onmousemove = null;
				return;
			}
			else if (position.top + height > bound.bottom){
				clone.style.top = bound.bottom - height;
				document.onmousemove = null;
				return;
			}
			else{
				clone.style.top = elem.pageY - shiftY + 'px';
			}
		}
		document.onmousemove = function(elem) {
			move(elem);
		}
		clone.onmouseup = function() {
			document.onmousemove = null;
			clone.onmouseup = null;
		}
	}

	clone.ondragstart = function() {
		return false;
	}

	function getPos(elem) {
		var pos = elem.getBoundingClientRect();
		return {
			top: pos.top + pageYOffset,
			left: pos.left + pageXOffset
		};
	}

	function getPosDiv() {
		var video = document.getElementById('video');
		var pos = video.getBoundingClientRect();
		return {
			top: pos.top + pageYOffset,
			left: pos.left + pageXOffset,
			right: pos.right + pageXOffset,
			bottom: pos.bottom + pageYOffset
		};
	}
}
}


document.getElementById('getval').addEventListener('change', readURL, true);
function readURL(){
	var div = document.getElementById("movepics");
	document.getElementById("load").style.backgroundImage = '';
	while (div.firstChild) {
			div.removeChild(div.firstChild);
		}
		document.getElementById("snap").disabled = true;
		document.getElementById("save").disabled = true;	
	context.clearRect(0, 0, canvas.width, canvas.height);
	var file = document.getElementById("getval").files[0];
	var reader = new FileReader();
	if(file){
		reader.readAsDataURL(file);
	}
	reader.onloadend = function(){
			if(checkbox.checked==true){
			var load = document.getElementById('load');
			var video = document.getElementById('video');
			video.pause();
			video.srcObject.getTracks()[0].stop();
			video.style.visibility = 'hidden';
			load.style.visibility = 'visible';
		}
		document.getElementById("snap").disabled = true;
		document.getElementById("save").disabled = true;
		var video = document.getElementById('video');
		var load = document.getElementById('load');
		checkbox.checked = false;
		video.style.visibility = 'hidden';
		load.style.visibility = 'visible';
		context.clearRect(0, 0, canvas.width, canvas.height);
		
		while (div.firstChild) {
			div.removeChild(div.firstChild);
		}
		var load = document.getElementById('load');
		load.style.backgroundImage = "url(" + reader.result + ")";
		document.getElementById("snap").addEventListener("click", function() 
		{
			if(checkbox.checked == false){
				context.clearRect(0, 0, canvas.width, canvas.height);
				var image = new Image();
				image.src = reader.result;
				image.onload = function(){
					// var width = load.backgroundSize;
					var viewportOffset = load.getBoundingClientRect();
					var top = viewportOffset.top;
					var left = viewportOffset.left;

					var imageWidth = image.naturalWidth; 
					var imageHeight = image.naturalHeight; 

					var canvasWidth = 640; 
					var canvasHeight = 480;

					var imgRatio = imageHeight / imageWidth;
					var canvasRatio = canvasHeight / canvasWidth;

					if (canvasRatio > imgRatio) {
						finalHeight = canvasHeight;
						scale = finalHeight / imageHeight;
						finalWidth = Math.round(imageWidth * scale , 0);
					} else {
						finalWidth = canvasWidth;
						scale = finalWidth / imageWidth;
						finalHeight = Math.round(imageHeight * scale , 0);
					}
					var difW = (640 - finalWidth) / 2;
					var difH = (480 - finalHeight) / 2;
					context.clearRect(0, 0, canvas.width, canvas.height);		
					context.drawImage(image, difW, difH, finalWidth, finalHeight);
					for (var i = 0; i < clones.length; i++){
						pos = clones[i].getBoundingClientRect();
						context.drawImage(clones[i], pos.left - left, pos.top - top, 
							clones[i].clientWidth, clones[i].clientHeight)
					}
				}
			}
		});
	}
}

document.getElementById("snap").addEventListener("click", function() 
{
	var video = document.getElementById('video');
	var viewportOffset = video.getBoundingClientRect();
	var top = viewportOffset.top;
	var left = viewportOffset.left;
	context.drawImage(video, 0,0,canvas.width,canvas.height);
	for (var i = 0; i < clones.length; i++){
		pos = clones[i].getBoundingClientRect();
		context.drawImage(clones[i], pos.left - left, pos.top - top, 
			clones[i].clientWidth, clones[i].clientHeight)
	}
	document.getElementById("save").disabled = false;
});
