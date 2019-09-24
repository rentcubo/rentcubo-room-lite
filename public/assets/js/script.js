$(document).ready(function () {
	var footer_height = $(".footer").outerHeight();
	$('.footer-height').height(footer_height);

	var header_height = $("#navbar").outerHeight();
	$('.header-height').height(header_height);

    var slider_height = $( window ).height();
    $('.flexslider').height(slider_height);
    $('.banner-overlay').height(slider_height);

	$(".close-login").click(function(){
		$('#close-login').click();
	});

	$(".close-forgot").click(function(){
		$('#close-forgot').click();
	});
	
	$(".close-signup").click(function(){
		$('#close-signup').click();
	});

	$(".show").click(function(){
		$('.show').hide();
	});

	$(".hide").click(function(){
		$('.show').show();
	});

    $('.navbar-toggler').click(function(){
        $(".trans-head").toggleClass("bg-white");
    });

    $('.navbar-toggler').click(function(){
        $(".transparent").toggleClass("white-back");
    });

    $(".floating-button").click(function () {
        $("#terms-btn").toggleClass("terms-btn");
        $(".floating-footer").slideToggle();
    });

    $("#more-filters-btn").click(function () {
        $("#more-filters-content").toggleClass("dis-block");
    });

    $("#guest-mobile-btn").click(function () {
        $("#mobile-guest-content").slideDown()
    });

    $("#guest-mobile-closebtn").click(function () {
        $("#mobile-guest-content").slideUp()
    });

    $("#help-link").click(function () {
        $("#help-sec").css({ "display": "block" });
    });

    $("#help-close").click(function () {
        $("#help-sec").css({ "display": "none" });
    });

	var value = 0, x;
    $('body').scroll(function() {
        value = $('body').scrollTop();
        x = parseInt(value);
        if (x >= 500) {
            $("#second").css({
                "display": "block"
            });
        } else {
            $("#second").css({
                "display": "none"
            });
        }
    });

    $('body').scroll(function() {
        // value = $('body').scrollTop();
        // x = parseInt(value);
        if ($('body').scrollTop()) {
            $("#sub-page").css({
                "display": "none"
            });
        } else {
            $("#sub-page").css({
                "display": "block"
            });
        }
    });

    $('.flexslider').flexslider({
        animation: "fade",
        slideshowSpeed: 6000,
        animationSpeed: 1000,
        controlNav: false,
        pauseOnAction: false,
    });

    $(function() {

        $(".increment-btn").append('<div class="inc button"><i class="fas fa-plus"></i></div>');

        $(".decrement-btn").append('<div class="dec button"><i class="fas fa-minus"></i></div>');

        $(".button").on("click", function() {

            var $button = $(this);
            var oldValue = $button.parent().find("input").val();

            if ($button.text() == "<i class='fas fa-plus'></i>") {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                // Don't allow decrementing below zero
                if (oldValue > 0) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = 0;
                }
            }

            $button.parent().find("input").val(newVal);

        });

    });

    $("#map-toggle-btn").click(function () {
        $(".main").addClass("resize-cls");
    });

    // $("#map-toggle-btn").click(function(){
    //     console.log("coming");
    //     $('#wrapper').toggleClass("resize-cls");
    // });

});

