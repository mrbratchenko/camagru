var indx = 0;
slideshow();

function slideshow() {
	var i;
	var slides = document.getElementsByClassName("slide");
	for (i = 0; i < slides.length; i++) {
		slides[i].style.display = "none"; 
	}
	indx++;
	if (indx > slides.length) {indx = 1} 
	slides[indx-1].style.display = "flex"; 
	setTimeout(slideshow, 4000);
}