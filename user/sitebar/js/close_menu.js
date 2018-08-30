$(document).ready(function(){
	$(".fa-times").click(function(){
		$(".sidebar_menu").addClass("hide_menu");
		$(".toggle_menu").addClass("opacity_one");
		    document.getElementById("mySidenav").style.width = "210px";
    document.getElementById("orders").style.marginLeft = "210px";
	});

	$(".toggle_menu").click(function(){
		$(".sidebar_menu").removeClass("hide_menu");
		$(".toggle_menu").removeClass("opacity_one");
		    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("orders").style.marginLeft = "0";
	});
});