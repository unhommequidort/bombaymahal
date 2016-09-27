$(document).foundation();

$(document).ready(function() {
    var $window = $(window);

    function checkWidth() {
        var windowsize = $window.width();
        if (windowsize > 440) {
            //if the window is greater than 440px wide then turn on parallax
            (function(){

            var parallax = document.querySelectorAll(".parallax"),
            speed = 0.5;

            window.onscroll = function(){
                [].slice.call(parallax).forEach(function(el,i){

                    var windowYOffset = window.pageYOffset,
                    elBackgrounPos = "50% " + (windowYOffset * speed) + "px";

                    el.style.backgroundPosition = elBackgrounPos;

                });
            };

            })();
        }
    }
    // Execute on load
    checkWidth();
    // Bind event listener
    $(window).resize(checkWidth);
});

// Fade out mobile menu on click
$(document).ready(function() {
	var $window = $(window);
    $('.menu-right li').click(function() { 
    	var windowsize = $window.width();
    	if (windowsize < 640) {
    		$('.top-bar').fadeOut(); 
    	}
        
    });
});

$('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') || location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
           if (target.length) {
             $('html,body').animate({
                 scrollTop: target.offset().top
            }, 1000);
            return false;
        }
    }
});