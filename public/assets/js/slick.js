$(document).on('ready', function() {
	$(".category").slick({
        arrows:true,
        infinite:false,
        slidesToShow:4,
        slidesToScroll:1,
        responsive: 
        [	
        	{
      			breakpoint:1200,
			    settings: {
			        arrows: true,
			        slidesToShow:3
			    }
    		},
    		{
      			breakpoint:992,
			    settings: {
			        arrows:true,
			        slidesToShow:2
			    }
    		},
   	 		{
			    breakpoint:768,
			    settings: {
			        arrows: true,
			        slidesToShow:3
			    }
    		},
    		{
			    breakpoint:576,
			    settings: {
			        arrows: false,
			        slidesToShow:3
			    }
    		}
  		]
    });

    $(".regular").slick({
        arrows:true,
        infinite:false,
        slidesToShow:3,
        slidesToScroll:1,
        responsive: 
        [	
        	{
      			breakpoint:1200,
			    settings: {
			        arrows: true,
			        slidesToShow:3
			    }
    		},
    		{
      			breakpoint:992,
			    settings: {
			        arrows: true,
			        slidesToShow:3
			    }
    		},
   	 		{
			    breakpoint:768,
			    settings: {
			        arrows: true,
			        slidesToShow:2
			    }
    		},
    		{
			    breakpoint:576,
			    settings: {
			        arrows: false,
			        slidesToShow:2
			    }
    		}
  		]
    });

    $(".home-slider").slick({
        arrows:true,
        infinite:false,
        slidesToShow:1,
        slidesToScroll:1,
        dots:true
    });

    $(".arrangements").slick({
        arrows:true,
        infinite:false,
        slidesToShow:3,
        slidesToScroll:1,
        responsive: 
        [   
            {
                breakpoint:1200,
                settings: {
                    arrows: false,
                    slidesToShow:3
                }
            },
            {
                breakpoint:992,
                settings: {
                    arrows: false,
                    slidesToShow:2
                }
            },
            {
                breakpoint:768,
                settings: {
                    arrows: false,
                    slidesToShow:3
                }
            },
            {
                breakpoint:576,
                settings: {
                    arrows: false,
                    slidesToShow:2
                }
            }
        ]
    });
    $(".similar-listings").slick({
        arrows:true,
        infinite:false,
        slidesToShow:3,
        slidesToScroll:1,
        responsive: 
        [   
            {
                breakpoint:1200,
                settings: {
                    arrows: true,
                    slidesToShow:3
                }
            },
            {
                breakpoint:992,
                settings: {
                    arrows:true,
                    slidesToShow:2
                }
            },
            {
                breakpoint:768,
                settings: {
                    arrows: true,
                    slidesToShow:2
                }
            },
            {
                breakpoint:576,
                settings: {
                    arrows: false,
                    slidesToShow:2
                }
            }
        ]
    });
});