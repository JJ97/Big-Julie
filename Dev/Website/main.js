function init(){    
  // ensure that first search result will be displayed
  localStorage.setItem("searchResCount",0);   
  // if user is logged in on page load
  if (localStorage.getItem("loggedIn") == "TRUE"){
    // update page to logged in mode
    toggleLogged();
  }
  else{
    localStorage.setItem("loggedIn","FALSE")
  }
  // for each vote button
  $('.btnVote').each(function(currentElement, index) {  
    // assign button with offset index
    $(this).data( "offset", currentElement );     
  });
  
  // use difference in height from CSS media query to determine whether user is on mobile or desktop
  if ($(".nextSong").height() == 360){
    localStorage.setItem("mobile", "TRUE");
  }
  else {
    localStorage.setItem("mobile", "FALSE");
  }
  refresh();   
};

function refresh(){
  getNowPlaying();
  getNextSongs(); 
}

function getNowPlaying(){
  // get album cover of currently playing song
  $.ajax(
      {       
        type: "POST",
        url: "init.php",        
        dataType: "text",
        data: {action:"getNextSongCover", PHPindex:0},
        success: function (cover) { 
          var thumb = $('#thumbNow');
		  // if thumbnail has changed
          if (($(thumb).prop('src')) != $.trim(cover)){
		    // enable skip button if disabled
            if ($('#btnSkip').prop('disabled') == true ){   
              $("#btnSkip").toggleClass("skipped");
              $("#btnSkip").prop('disabled', function(i, v) { return !v; });
            }  
            // enable vote buttons if disabled			
            $('.voteBtn').each(function(currentElement, index) {  
            if ($(this).prop('disabled') == true ){ 
              $(this).toggleClass("voted");
              $(this).prop('disabled', function(i, v) { return !v; })
            }   
            });
            // get name of currently playing song     
            $.ajax(
            {       
              type: "POST",
              url: "init.php",        
              dataType: "text",
              data: {action:"getNextSongName", PHPindex:0},
              success: function (name) {  
                // update marquee with new name
                $('#nowPlaying').fadeToggle("slow",function(){
                  $('#nowPlaying').html('<marquee>NOW PLAYING : '+name+'</marquee>'); 
                });         
                $('#nowPlaying').fadeToggle("slow");
               }   
            }); 
          }
		  // update thumbnail with new cover art
          setThumb(thumb,$.trim(cover));
        }   
      }); 
}

function getNextSongs(){  
  // for each thumbnail with class 'thumbNext'
  $('.thumbNext').each(function(currentElement, index) {  
    // get nth album cover from request database
    $.ajax(
      {       
        type: "POST",
        url: "init.php",        
        dataType: "text",
        data: {action:"getNextSongCover", PHPindex:(currentElement+1)},
        success: function (cover) { 
          // set thumbnail to new cover
          var thumb = $('#thumbNext'+String(currentElement+1));
          setThumb(thumb,$.trim(cover));
        }   
      });   
  }); 
  // for each paragraph with class 'pNext'
  $('.pNext').each(function(currentElement, index) {  
    // get nth name from request database
    $.ajax(
      {       
        type: "POST",
        url: "init.php",        
        dataType: "text",
        data: {action:"getNextSongName", PHPindex:(currentElement+1)},
        success: function (name) {  
          name = $.trim(name);
		  // if track name will overflow module
          if (name.length > 20) {
            // set text to first twenty characters and append ellipses
            name = (name.substr(0,20)+'...');
          }   
          // update page with new song name
          var para = $('#pNext'+String(currentElement+1));
          setName(para,name);
          
        }   
      });   
  }); 
}

function setThumb(thumb,art){   
  if (($(thumb).prop('src')) != art){
    $(thumb).fadeToggle("slow",function(){
      $(thumb).attr("src",art);
    });   
    $(thumb).fadeToggle("slow");
  }     
}

function setName(para,name){
  if ($(para).text() != name){
    $(para).fadeToggle("slow",function(){    
      $(para).html(name);
    });   
    $(para).fadeToggle("slow");
  }   
}

function toggleLogged(){
  // if search box is open then close
  if ($('#btnYes').prop('disabled') == false){  
    searchClose();
  }   
  // toggle vote buttons and search box visible
  if ($(".nextSong").height() == 360 || $(".nextSong").height() == 290){
    $(".toToggle").delay(200).fadeToggle("slow");
  }
  else{
    $(".toToggle").delay(0).fadeToggle("slow");
  }  
  // animate resize for next song module
  if (localStorage.getItem("mobile") == 'TRUE') {  
    // expand module to fit vote button
    if ($(".nextSong").height() == 360){
      $('.nextSong').animate({height:450},500);
    }
    // retract module to initial height
    else{
      $('.nextSong').animate({height:370},500);
    }
  }
  // if user on desktop
  else{
    if ($(".nextSong").height() == 290){
      $('.nextSong').animate({height:355},500);
    }
    else{
      $('.nextSong').animate({height:300},500);
    }
  }
  // animate resize for jumbotron and search box
  // expand to fit search box and skip button
  if ($('.jumbotron').height() == 325){
    $('.jumbotron').animate({height:579},500);
    $('.search .container').animate({height:79},500);
  }
  // retract to initial height
  else{
    $('.jumbotron').delay(100).animate({height:508},500);
    $('.search .container').delay(100).animate({height:25},500);
  }   
  // toggle button between login and logout
  $("#btnLogin").fadeToggle("slow",function(){  
    // toggle button text and CSS
    if ($("#btnLogin").text() == "LOGIN") { 
      $("#btnLogin").text("LOGOUT"); 
    } 
    else { 
      $("#btnLogin").text("LOGIN"); 
    }; 
    $("#btnLogin").toggleClass("logged");
    });   
  $("#btnLogin").fadeToggle("slow");
};

function login(){
  // get login values from textboxes
    var username = $('#username').val();
    var password = $('#password').val();    
    // if user not logged in
    if (localStorage.getItem("loggedIn") == 'FALSE') {  
      // send login request to login.php with username and password
      $.ajax(
      {       
        type: "POST",
        url: "login.php",
        dataType: "text",
        data: {action:"login", PHPusername: username, PHPpassword: password},
        // return if login was successful
        success: function (loginValid) {  
          // if login successful
          if (loginValid == 1) {
            // update page to logged in mode
            toggleLogged();
			// set user as logged in
            localStorage.setItem("loggedIn", "TRUE");   
			// save username in localstorage for use in requests
            localStorage.setItem("username", username);     
          }
		  // if login unsuccessful
          else {      
            // output login failed message
            alert("Login unsuccessful, check username and password are correct");     
          }           
        }   
      });     
    }
    // if user logged in
    else {        
      // send logout request to login.php with username
      $.ajax(
      {       
        type: "POST",
        url: "login.php",
        dataType: "text",
        data: {action:"logout", PHPusername: username},
      }); 
      // update page to logged out mode
      toggleLogged();
	  // set user as logged out
      localStorage.setItem("loggedIn", "FALSE");
    }
}

function songSearch(){
  // get search result index
  var i = (localStorage.getItem("searchResCount"));
  // get track and artist to search, convert string to valid format for spotify
  var searchTrack = ($('#tbSong').val()).replace(/ /g,"%20"); 
  var searchArtist = ($('#tbArtist').val()).replace(/ /g,"%20");
  // construct query depending on which data was sent by user
  var query = "https://api.spotify.com/v1/search?q=";
  // if track and artist specified
  if (searchTrack != "" && searchArtist != ""){
    // construct request with track and artist
    query = query + "track:" + searchTrack + "+artist:" + searchArtist;
  }
  // if only track specified
  else if (searchTrack != ""){
    // construct request with only track
    query = query + "track:" + searchTrack;
  }
  // if only artist specified
  else if (searchArtist != ""){
    // construct request with only artist
    query = query + "artist:" + searchArtist;
  }
  query = query + "&type=track&market=GB";
  // query spotify with constructed string
  $.ajax(
      {       
        type: "GET",
        url: query,   
        success: function (trackObject) {               
            var validTracks = [];
            // get array of valid indices for trackObject
            validTracks = validateTrack(trackObject);             
            var j = validTracks[i];       
            $(".toUpdate").fadeToggle("1200",function(){
              // if object with index j present in trackObject
              if (typeof(trackObject.tracks.items[j]) != "undefined"){
                // assign web elements with corresponding values from trackObject
                $('#thumbResult').attr("src",trackObject.tracks.items[j].album.images[0].url);
                $('#titleResult').html(trackObject.tracks.items[j].name);
                $('#artistResult').html(trackObject.tracks.items[j].artists[0].name);
                $('#lengthResult').html(parseInt(trackObject.tracks.items[j].duration_ms));               
                $('#idResult').html(trackObject.tracks.items[j].id);
              }
            }); 
            // update display on page
            $(".toUpdate").fadeToggle("1200");
        }     
      }); 
}

function validateTrack(trackObject){
  var returnTracks = [];
  var i = 0;
  // iterate through each item in trackObject
  while (typeof(trackObject.tracks.items[i]) != "undefined"){
    // if song is under 7 minutes long and not explicit
    if (((trackObject.tracks.items[i].duration_ms) < 420000)  && ((trackObject.tracks.items[i].explicit) == false))){
      // push corresponding index to returnTracks
      returnTracks.push(i);   
    }
    i = i + 1;
  }
  // return array of  indices for valid songs in trackObject
  return returnTracks;
}

function searchOpen(){  
  // if search results not already displayed
  if ($('#btnYes').prop('disabled') == true){   
    // animate opening of search results
    $(".reqConfBtn").prop('disabled', function(i, v) { return !v; });
    $('.nextSong').animate({top:345},500);
    $(".toSearchToggle").fadeToggle("slow");  
  }   
}

function searchClose(){
  // empty search boxes
  $('#tbSong').val("");
  $('#tbArtist').val(""); 
  // animate closing of search results
  $(".reqConfBtn").prop('disabled', function(i, v) { return !v; });
      $('.nextSong').animate({top:0},500);
      $(".toSearchToggle").fadeToggle("slow");
};

function songReq(){
  // get request data from html
  var username = (localStorage.getItem("username"));
  var currentTime = getCurrentTime();
  var id = $('#idResult').text();
  // send request data to songReq.php for validation
  $.ajax(
      {       
        type: "POST",
        url: "songReq.php",
        dataType: "text",
        data: {action:"validateSongReq", PHPusername: username, PHPcurrentTime: currentTime, PHPURL: id},
        success: function (songReqValid) {    
          // if request has passed all validation 
          if ($.trim(songReqValid).charAt(0) == "p"){ 
            // get cooldown time from returned data
            var cooldown = parseInt($.trim(songReqValid).substring(1)); 
			// disable song request button
            toggleSongReqDisabled();
			// enable song request button after cooldown
            setTimeout(toggleSongReqDisabled,cooldown);
			// send song request 
            pushSongReq(id,username);             
          }
          // if request has failed validation
          else {
		    // display error message
            alert(songReqValid);
          }
        }   
      }); 
}

function getCurrentTime(){
  // return current UNIX timestamp in milliseconds
  return(Math.floor(Date.now()));
}

function toggleSongReqDisabled(){
  $('.toSongReqDisable').each(function(currentElement, index) {   
    $(this).prop('disabled', function(i, v) { return !v; });    
  });
  
}

function pushSongReq(id,username){  
  // get request data from html
  var name = $('#titleResult').text();
  var artist = $('#artistResult').text();
  var art = $('#thumbResult').attr("src");
  var length = parseInt($('#lengthResult').text());
  var reqTime = getCurrentTime();
  var skipCount = 0;
  // send track information to songReq.php to add song to queue
  $.ajax(
      {       
        type: "POST",
        url: "songReq.php",
        dataType: "text",
        data: {action:"pushSongReq", PHPURL: id, PHPusername: username, PHPname: name, PHPartist: artist, PHPart: art, PHPlength: length, PHPreqTime: reqTime, PHPskipCount: skipCount},
        success: function () {          
          
        }   
      }); 

}

function skipReq(){ 
  var username = (localStorage.getItem("username"));
  $.ajax(
      {       
        type: "POST",
        url: "skip.php",
        dataType: "text",
        data: {action:"skipReq",PHPusername:username},
        success: function (skipped) {
          // if skip request not made
          if ($.trim(skipped) != "1"){
            // output error message
            alert(skipped);
          }
		  // if skip request success
          else{
            // change skip button colour to grey
            $("#btnSkip").toggleClass("skipped");
			// disable skip button
            $("#btnSkip").prop('disabled', function(i, v) { return !v; });            
            setTimeout(refresh,1000);
          }
        }   
      }); 
}

function voteReq(offset){
var username = (localStorage.getItem("username"));
  $.ajax(
      {       
        type: "POST",
        url: "vote.php",
        dataType: "text",
        data: {action:"voteReq",PHPusername:username,PHPoffset:offset},
        success: function (voted) {
		  // if vote request not successful
          if ($.trim(voted) != "1"){
		    // display error message
            alert(voted);
          }
          else {
		    // disable vote button
            $('#btnVote'+String(offset+1)).toggleClass("voted");
            $('#btnVote'+String(offset+1)).prop('disabled', function(i, v) { return !v; });
          }
        }   
      }); 
  
}

$(document).ready(function(){
  init();
  // if login button pressed
  $('#btnLogin').click(function(){
    login();
  });     
  // if enter key pressed in password box
  $("#password").keypress(function(e) {   
    if(e.which == 13) {  
      login();
    }     
  });
  // if search button pressed
  $('#btnSearch').click(function(){
    // reset search counter so 1st result is shown
    localStorage.setItem("searchResCount",0)
    // open search results module
    searchOpen();
	// send search request
    songSearch();
  }); 
  // if enter key pressed in track or artist box
  $(".searchBox").keypress(function(e) {  
    if(e.which == 13) {   
      localStorage.setItem("searchResCount",0)
      searchOpen();
      songSearch();
    }     
  });
  $('.btnYes').click(function(){
    songReq();
    // update page after request sent   
    setTimeout(refresh,1000);
    // close search results
    searchClose();
  });
  $('.btnNo').click(function(){
    // increment search result index and update search result     
    localStorage.setItem("searchResCount",(parseInt(localStorage.getItem("searchResCount"))+1));
    songSearch();
  });
  // if skip button pressed
  $('#btnSkip').click(function(){ 
    skipReq();
  });
  // if vote button pressed
  $('.btnVote').click(function(){   
    // get position of song voted for as offset from left side
    var offset = $(this).data('offset');
    voteReq(offset);
  });
  window.setInterval(function(){
    // update page with latest data every 5s
    refresh();
  }, 5000);
});