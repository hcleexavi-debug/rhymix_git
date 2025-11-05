// <![CDATA[
jQuery(function($){
	$(document).ready(function(){
		var hd = $('#container').find('>.rl-header');

		hd.find('.bt-menu').click(function(){
			$('#sidebar').fadeIn(300);
			$('#sidebar .inner').addClass('active');
			var offset = $(document).scrollTop();
			$("body").css('position','fixed').css('top',offset);
		});
		$('#sidebar').find('.click-dummy').click(function(){
			$('#sidebar .inner').removeClass('active');
			$('#sidebar').fadeOut();
			var offset = $("body").offset().top;
			$("html,body").css("position","static").scrollTop(-offset);
		});
	});
});
// ]]>
