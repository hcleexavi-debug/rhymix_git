function switchMode(mode){
	if(mode == 'light'){
		$.cookie('rx_color_scheme', 'dark', {expires: 7, path: '/'});
		location.reload();
	}else{
		$.cookie('rx_color_scheme', 'light', {expires: 7, path: '/'});
		location.reload();
	}
}
function searchPop(){
	var s = jQuery('#container').find('>.rl-search');
	s.show().find('.rl-search-ip').focus();
	s.find('form').addClass('active');
}
function closeSearch(){
	var s = jQuery('#container').find('>.rl-search');
	s.find('form').removeClass('active');
	s.fadeOut(300);
}