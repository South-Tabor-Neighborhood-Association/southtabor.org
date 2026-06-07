//var $ = jQuery.noConflict();
jQuery(document).ready(function($){	

	$('#social-media a').hover(function(){  
			$(this).fadeTo(150,1);
		}  
		, function(){  
			$(this).fadeTo(150,.7);
		}  
		
	);
	
	//Scroll to top
	$(window).scroll(function() {
		if(jQuery(this).scrollTop() != 0) {
			jQuery('#toTop').fadeIn();	
		} else {
			jQuery('#toTop').fadeOut();
		}
	});
	
	$('#toTop').click(function() {
		jQuery('body,html').animate({scrollTop:0},800);
	});
	
	//Top Menu
	$('ul.topNav').superfish({
		animation: {opacity:'show'},
		autoArrows:  true,
		dropShadows: true, 
		speed: 200,
		delay: 10,
		animation:   {opacity:'show',height:'show'}  // fade-in and slide-down animatio

	});
	
	//Main Menu
	$('ul.sf-menu').superfish({ 
		delay:       100,								// 0.1 second delay on mouseout 
		animation:   {opacity:'show',height:'show'},	// fade-in and slide-down animation 
		dropShadows: false								// disable drop shadows 
	});
	
	$(function(){
		$('.sf-menu ul a').hover(function(){  
				$(this).animate({paddingLeft: '20px'}, 'fast');
			}  
			, function(){  
			
				$(this).animate({paddingLeft: '15px'}, 'fast');
			}  
			
		);
	});
	
	
	
	//Tooltips
	tooltip($);
	
	$(".toggle-content").hide(); 
	$("h5.toggle").toggle(function(){
		$(this).addClass("active");
		}, function () {
		$(this).removeClass("active");
	});

	$("h5.toggle").click(function(){
		$(this).next(".toggle-content").slideToggle();
	});
	

});
