!function ($) {

//oculta topbar do safari mobile
window.top.scrollTo(0, 1);

//fade-nos links do menu quando focar a busca
$('.search-query').focus(function() {
	$('.navbar .nav > li > a').animate({
		opacity: 0.25,
	}, 250);
});
$('.search-query').blur(function() {
	$('.navbar .nav > li > a').animate({
		opacity: 1,
	}, 250);
});



}(window.jQuery)