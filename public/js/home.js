// List of sentences
var sentences = [ 
	"easier.", 
	"smarter.", 
	"greener.", 
];

let currentSentence = 0;
let currentSentenceLetter = 0;
let interval;

// Element that holds the text
let keywordContainer = document.querySelector("#keyword");

// Cursor element 
let cursor = document.querySelector("#cursor");

// Implements typing effect
function typeEffect() { 
	// Get substring with 1 characater added
	var text =  sentences[currentSentence].substring(0, currentSentenceLetter + 1);
	keywordContainer.innerHTML = text;
	currentSentenceLetter++;

	// If full sentence has been displayed then start to delete the sentence after some time
	if(text === sentences[currentSentence]) {
		// Hide the cursor
		cursor.style.display = 'none';

		clearInterval(interval);
		setTimeout(function() {
			interval = setInterval(deleteEffect, 50);
		}, 2000);
	}
}

// Implements deleting effect
function deleteEffect() {
	// Get substring with 1 characater deleted
	var text =  sentences[currentSentence].substring(0, currentSentenceLetter - 1);
	keywordContainer.innerHTML = text;
	currentSentenceLetter--;

	// If sentence has been deleted then start to display the next sentence
	if(text === '') {
		clearInterval(interval);

		// If current sentence was last then display the first one, else move to the next
        currentSentence = (currentSentence + 1) % sentences.length		
        currentSentenceLetter = 0;

		// Start to display the next sentence after some time
		setTimeout(function() {
			cursor.style.display = 'inline-block';
			interval = setInterval(typeEffect, 100);
		}, 200);
	}
}

// Start the typing effect on load
interval = setInterval(typeEffect, 100);


const logoContainer = document.getElementById('logo-container');

window.addEventListener("scroll", () => {
    var y = window.scrollY;
    if (y >= 350){
        logoContainer.classList.add('remove');
        return;
    }
    else{
        logoContainer.classList.remove('remove');
    }
});