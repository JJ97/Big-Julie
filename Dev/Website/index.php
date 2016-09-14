<!DOCTYPE html>
<html>
<!-- load external resources -->
<head>
  <!-- load twitter bootstrap css template, used to create dynamic components e.g. navbar, jumbotron etc -->
  <link rel="stylesheet" href="dist/css/bootstrap.css"> 
  <!-- load css style for page -->
  <link rel="stylesheet" href="main.css"> 
   <!-- load jQuery library, used to add functionality to and fix bugs in vanilla javascript e.g. animation and DOM object selection -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <!-- load javascript for page -->
  <script src="main.js"></script>     
</head>

<body id="page">
  <!-- create navigation bar at top of screen, bootstrap.css handles basic layout -->
  <nav class="navbar navbar-default navbar-fixed-top" role= "navigation">
    <div class="container">
      <!-- create bootstrap row with width 12, used to arrange elements into dynamic columns -->
      <div class="row">
        <!-- create column with width 3, xs ensures that display is consistent on all devices -->
        <div class="col-xs-3">
          <!-- display Big Julie logo in column -->
          <div class="BJ">
              <img id="BJ" src="julie.png" alt="Big Julie"> 
           </div>
        </div>
        <!-- create column with width 6 -->
        <div class="col-xs-6">
          <!-- display marquee moving right to left with currently playing song name -->
          <!-- main.js updates text on page load and refresh function called -->
          <p id="nowPlaying" class="nowPlaying toRefresh"><marquee> </marquee></p>
        </div>
        <!-- create column with width 2 to contain login textboxes -->
        <div class="col-xs-2">
          <div class="login form-group toToggle">          
              <div class="username">
                <!-- create textbox with default text "Username..." -->
                <!-- main.js gets text entered when login function called, fades textbox out if login successful, calls login function when enter key pressed while in textbox -->
                <input id="username" type="text" class="form-control " placeholder="Username...">
              </div>                
              <div class="password">
                <!-- create textbox with default text "Password..." -->
                <!-- main.js performs same logic as to username -->
                <input id="password" type="password" class="form-control " placeholder="Password...">
              </div>                
          </div>
        </div>
        <!-- create column with width 6 -->
        <div class="col-xs-1">
          <div class="btnLogin">
            <!-- create button with text "LOGIN" -->
            <!-- main.js calls login function when pressed and adds class "logged", changes text to LOGOUT if login successful, main.css changes display accordingly-->
            <button id="btnLogin" type="button" class="btn btn-default navbar-btn">LOGIN</button>
          </div>
        </div>        
      </div>            
    </div>     
  </nav>
  <!-- create jumbotron in centre of page, bootstrap.css handles basic layout  -->  
  <div class="jumbotron">
    <div class="container">
      <!-- create thumbnail in centre of jumbotron, bootstrap.css handles basic layout -->
      <div class="thumbnail">
        <!-- display cover art of currently playing song -->
        <!-- main.js updates image on page load and refresh function called -->
        <img id="thumbNow" class="thumbNow toRefresh" src="mockFLoyd.png"> 
      </div>      
      <div class ="btnSkip">
        <!-- create button with text "SKIP", not displayed until user logged in -->
        <!-- main.js calls skip function when pressed -->
        <button id="btnSkip" type="button" class="btn btn-default navbar-btn toToggle locked" style="display: none;">SKIP</button>
      </div>      
    </div>     
  </div>
  <!-- contains all elements related to searching and requesting songs -->
  <div class="search">
    <div class="container">
      <!-- all contained elements not displayed until user logged in successfully -->
      <!-- main.js temporarily disables elements when song succesfully requested -->
      <div class="initSearch">
        <div class="row-fluid">
          <div class="col-sm-5">
            <div class="form-group">
              <!-- create textbox with default text "Track name..." -->
              <!-- main.js gets text entered when songReq function called, calls songReq function when enter key pressed while in textbox -->
              <input id="tbSong" type="text" class="toSongReqDisable form-control toToggle searchBox" placeholder="Track name..." style="display: none;">
            </div>
          </div>
          <div class="col-sm-5">
            <div class="form-group">
              <!-- create textbox with default text "Artist..." -->
              <!-- main.js performs same logic as to tbSong -->
              <input id="tbArtist" type="text" class="toSongReqDisable form-control toToggle searchBox" placeholder="Artist..." style="display: none;">
            </div>
          </div>
          <div class="col-sm-2">
             <!-- create button with text "SEARCH" -->
             <!-- main.js calls songReq function when pressed -->
            <button id="btnSearch" type="button" class="toSongReqDisable btnSearch btn btn-default navbar-btn toToggle" style="display: none;">SEARCH</button>
          </div>
        </div>  
      </div>
      <!-- all contained elements not displayed until song request successfully made  -->
      <!-- main.js animates fade in of contained elements and moves next song modules to fit when searchOpen function called -->
      <div class="searchResult" >
        <div class="searchToggle toSearchToggle" style="display: none;">
          <div class="row-fluid">
            <!-- create column with width 10 to contain search result information -->
            <div class="col-sm-10">
              <div class="row-fluid">
                <div class="col-sm-4">
                   <!-- create thumbnail to left of page, bootstrap.css handles basic layout -->
                  <div class="thumbnail toUpdate">
                     <!-- display cover art of search result song -->
                     <!-- main.js updates image when new search query made -->
                    <img id="thumbResult" class="thumbResult" src="julie.png">          
                  </div>              
                </div>
                <!-- contains information on search result song -->
                <!-- main.js updates text when new search query made -->
                <div class="col-sm-8">  
                  <div class="trackInfo ">
                    <p id="titleResult" class="toUpdate">Title</p>
                    <p id="artistResult" class="toUpdate">Artist</p>
                    <!-- elements are never displayed, used for song validation process -->
                    <p id="lengthResult" style="display: none;">Length</p>
                    <p id="idResult"  style="display: none;">Explicit</p>
                  </div>
                </div>  
              </div>
            </div>
            <!-- contains buttons for selecting whether search result is correct song -->
            <!-- main.js disables buttons temporarily when song request successfully made -->
            <div class="col-sm-2">
              <div class="reqConfBtns">
                <!-- main.js calls songReq function when pressed, closes search result module -->
                <button id="btnYes" type="button" class="toSongReqDisable btn btn-default navbar-btn reqConfBtn btnYes" disabled>YES</button>
                <!-- main.js calls search function again, gets information of next search result and updates accordingly  -->
                <button id="btnNo" type="button" class="toSongReqDisable btn btn-default navbar-btn reqConfBtn btnNo" disabled>NO</button>
              </div>
            </div>            
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- contains all elements related to upcoming songs -->
  <div class="nextSongs">
    <div class="container">
      <div class="row-fluid">
        <!-- md column causes modules to display horizontally on desktop, sm column creates grid layout on mobile -->
        <div class="col-sm-6">          
          <div class="col-md-6">
            <!-- contains all elements for first next song module -->
            <div class="nextSong">   
              <!-- create thumbnail at top of module, bootstrap.css handles basic layout -->            
              <div class="thumbnail">
                <!-- display cover art of next song -->
                <!-- main.js updates image when getNextSongs function called if next song has changed -->
                <img id="thumbNext1" class="thumbNext toRefresh" src="#">              
              </div>
              <!-- display title of next song -->
              <!-- main.js updates text when getNextSong function called if next song has changed, if text will not fit in module, text is truncated to 20 characters and ellipses appended -->
              <p id="pNext1" style="white-space: nowrap; text-overflow: ellipsis;" class="pNext toRefresh"> - </p>            
              <span class="btnVote">
                <!-- create button with text "VOTE" -->
                <!-- main.js calls vote function when pressed-->
                <button id="btnVote1" type="button" class="voteBtn locked btn btn-default navbar-btn toToggle" style="display: none;">VOTE</button>
              </span>  
            </div>          
          </div>
          <!-- contains all elements for second next song module -->
          <div class="col-md-6">
            <div class="nextSong">            
              <div class="thumbnail">
                <img id="thumbNext2" class="thumbNext toRefresh" src="#">              
              </div>              
              <p id="pNext2" style="white-space: nowrap; text-overflow: ellipsis;" class="pNext toRefresh"> - </p>            
               <span class="btnVote">
                <button id="btnVote2" type="button" class="voteBtn locked btn btn-default navbar-btn toToggle" style="display: none;">VOTE</button>
               </span>            
            </div>         
          </div>          
        </div>        
        <div class="col-sm-6">      
          <div class="col-md-6">
            <!-- contains all elements for third next song module -->
            <div class="nextSong">            
              <div class="thumbnail">
                <img id="thumbNext3" class="thumbNext toRefresh" src="#" >              
              </div>              
              <p id="pNext3" style="white-space: nowrap; text-overflow: ellipsis;" class="pNext toRefresh"> - </p>            
              <span class="btnVote">
                <button id="btnVote3" type="button" class="voteBtn locked btn btn-default navbar-btn toToggle" style="display: none;">VOTE</button>
              </span>            
            </div>          
          </div>
          <!-- contains all elements for fourth next song module -->
          <div class="col-md-6">
            <div class="nextSong">            
              <div class="thumbnail">
                <img id="thumbNext4" class="thumbNext toRefresh" src="#">              
              </div>              
              <p id="pNext4" style="white-space: nowrap; text-overflow: ellipsis;"  class="pNext toRefresh"> - </p>            
              <span class="btnVote">
                <button id="btnVote4" type="button" class="voteBtn locked btn btn-default navbar-btn toToggle" style="display: none;">VOTE</button>
              </span>            
            </div>         
          </div>          
        </div>       
      </div>
    </div>
  </div>   
</body> 
  
</html>