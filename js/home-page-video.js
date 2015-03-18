$(function(){
	'use strict';
	$('#topBanner .playActionLink').click(function(e) {
		new MediaElementPlayer('#videoPlayer',{
			success: function(mediaElement, domObject) {
				mediaElement.addEventListener('canplay', function(e) {
					mediaElement.play();
				});
			}
		});
		$('#topBanner').addClass('expanded');
		e.preventDefault();
	});
});
