var Spotify = {
// http://khlo.co.uk/stuff/spotify/
	currentSong: '',
	play: function(songId) {
		if (songId != this.currentSong) {
			if (!document.getElementById('spotifyPlayer')) {
				spotPlayer = document.createElement('div');
				spotPlayer.id = 'spotifyPlayer';
				document.body.appendChild(spotPlayer);
			}			
			document.getElementById('spotifyPlayer').innerHTML = '<iframe src="spotify:track:'+songId+'" height="1" width="1" style="visibility: hidden;"></iframe>';
			this.currentSong = songId;
		}
	}
}

function init(){		
	// ensure that first song in queue will play on startup
	localStorage.setItem("playing","false")
}

function play(){	
	// set song playing flag as true
	localStorage.setItem("playing","true")
	// get URL of song to play
	getURL();		
}

function getURL(){
	// get URL of song to play from request database
	$.ajax(
		{			
			type: "POST",
			url: "play.php",				
			dataType: "text",
			data: {action:"getURL"},
			success: function (song) {		
				// get length of song to play
				getLength($.trim(song));				
			}	
		}); 
}

function getLength(URL){
	// get Length of song to play from request database
	$.ajax(
		{			
			type: "POST",
			url: "play.php",				
			dataType: "text",
			data: {action:"getLength", PHPURL:URL},
			success: function (length) {	
				// reduce length of song by 1 second to allow for processing time between song changes
				length = length - 1000;
				playSong(URL,length);
			}	
		}); 
}

function playSong(URL,length){
	Spotify.play(URL);
	// set timer to execute end of song logic when song ends
	var timer = setTimeout(function(){songEnd(URL);},length);
	localStorage.setItem("URL",URL);
	localStorage.setItem("timer",timer);
}

function songEnd(URL){
	// push song to end of queue
	var currentTime = getCurrentTime() + 100000000000000 ;
	$.ajax(
			{			
				type: "POST",
				url: "play.php",				
				dataType: "text",
				data: {action:"nextSong", PHPURL:URL, PHPtime:currentTime},
				success: function () {	
					// flag that no song is playing
					localStorage.setItem("playing","false");
				}	
			}); 
}

function getCurrentTime(){
	// return current UNIX timestamp in seconds
	return(Math.floor(Date.now()));
}

function getSkipped(){
	var URL = localStorage.getItem("URL");
	// get whether song has met criteria for skipping
	$.ajax(
			{			
				type: "POST",
				url: "play.php",				
				dataType: "text",
				data: {action:"getSkipped", PHPURL:URL},
				success: function (skipped) {	
					// if song has met skipping criteria
					if ($.trim(skipped) == "true"){		
						// disable countdown timer for song end
						var timer = parseInt(localStorage.getItem("timer"));
						clearTimeout(timer);	
						// run logic as if song had ended
						songEnd(localStorage.getItem("URL"));	
						dropSong();
					}
				}	
			}); 
}

function dropSong(){
	var URL = localStorage.getItem("URL");
	$.ajax(
			{			
				type: "POST",
				url: "play.php",				
				dataType: "text",
				data: {action:"dropSong", PHPURL:URL},
				success: function () {	
					
				}	
			}); 
}

$(document).ready(function(){
	init();
	// check for song playing every second
	window.setInterval(function(){
		// if song not currently playing
		if (localStorage.getItem("playing") == "false"){
			// play song
			play();
		}
	}, 1000);	
	// check for song skipped every second
	window.setInterval(function(){
		getSkipped();
	}, 1000);	
});