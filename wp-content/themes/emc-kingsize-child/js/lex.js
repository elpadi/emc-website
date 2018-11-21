$(function() {
    var iframe = $('#playa2')[0];
    var player = $f(iframe);
    var status = $('.status');

    // When the player is ready, add listeners for pause, finish, and playProgress
    player.addEvent('ready', function() {
        status.text('ready');
        
        player.addEvent('pause', onPause);
        player.addEvent('finish', onFinish);
        player.addEvent('playProgress', onPlayProgress);
    });

    // Call the API when a button is pressed
    $('button').bind('click', function() {
        player.api($(this).text().toLowerCase());
    });

    function onPause() {
        status.text('paused');
    }

    function onFinish() {
        status.text('finished');
    }

    function onPlayProgress(data) {
        status.text(data.seconds + 's played');
    }
});

// Set lesson times (in seconds)
var lilink_4_rack = 250; //2:30 
var link_5_track = 356; //7:36
var link_6_track = 565; //11:05

// Variables for status messages
var link_4_status = jQuery('.link_4_status');
var link_5_status = jQuery('.link_5_status');
var link_6_status = jQuery('.link_6_status');
var main_status = jQuery('.main_status');

// Message displayed when link is playing
var playNotice = ' - <strong><i>Now playing</i></strong>';

// Function to hide all status messages
hidePlayNoticeAll = function() {
    jQuery(".link_4_status").html("");
    jQuery(".link_5_status").html("");
    jQuery(".link_6_status").html("");
}

// Load Vimeo API for the embedded video
var iframe_player = jQuery('#playa2')[0];
var playa2 = $f(iframe_player);

// Option listeners for pause, finish, and playProgress
// Note: If you want to use this, you must define the functions or links wont work 
playa2.addEvent('ready', function() {
    playa2.addEvent('pause', onPause);
    playa2.addEvent('finish', onFinish);
    playa2.addEvent('playProgress', onPlayProgress);
}); 

// Functions for each listener event
function onPause(id) { // When the video is paused, do this.
    jQuery(".main_status").html('Paused');
}
function onFinish(id) { // When the video is finished, do this.
    jQuery(".main_status").html('Finished');
}
function onPlayProgress(data, id) { // While the video is playing, do this.
    jQuery(".main_status").html('Playing - ' + data.seconds + 's played'); // data.percent can also be used.
 
   if (data.seconds < link_4_track) {hidePlayNoticeAll();}
     
    //this will ensure that the 'Now playing' message automatically moves to the next link when the video plays through. 
    //Otherwise, it will always stay next to the link that was last clicked.
    if (data.seconds > link_4_track && data.seconds < link_5_track){
        hidePlayNoticeAll();
        jQuery(".link_4_status").html(playNotice);
    }
    if (data.seconds > link_5_track && data.seconds < link_6_track){
        hidePlayNoticeAll();
        jQuery(".link_5_status").html(playNotice);
    }
    if (data.seconds > link_6_track){
        hidePlayNoticeAll();
        jQuery(".link_6_status").html(playNotice);
    }
}
// Function to control what happens when each lesson link is clicked
function setupLinks() {
        
    jQuery(".link_4").click(function () {
        playa2.api('play'); //Play the video
        playa2.api('seekTo', link_4_track); //Slink_4 the number of seconds in the variable link_1_track
        hidePlayNoticeAll(); // Hide all status messages before displaying (to prevent them from sticking) 
        jQuery(".link_4_status").html(playNotice); //Display status message (playNotice) within span with class link_1_status
    });

    jQuery(".link_5").click(function () {
        playa2.api('play');
        playa2.api('seekTo', link_5_track);
        hidePlayNoticeAll();
        jQuery(".link_5_status").html(playNotice);
    });
    
    jQuery(".link_6").click(function () {
        playa2.api('play');
        playa2.api('seekTo', link_6_track);
        hidePlayNoticeAll();
        jQuery(".link_6_status").html(playNotice);
    });
}


$(function() {
    var iframe = $('#playa')[0];
    var player = $f(iframe);
    var status = $('.status');

    // When the player is ready, add listeners for pause, finish, and playProgress
    player.addEvent('ready', function() {
        status.text('ready');
        
        player.addEvent('pause', onPause);
        player.addEvent('finish', onFinish);
        player.addEvent('playProgress', onPlayProgress);
    });

    // Call the API when a button is pressed
    $('button').bind('click', function() {
        player.api($(this).text().toLowerCase());
    });

    function onPause() {
        status.text('paused');
    }

    function onFinish() {
        status.text('finished');
    }

    function onPlayProgress(data) {
        status.text(data.seconds + 's played');
    }
});

// Set lesson times (in seconds)
var link_1_track = 150; //2:30 
var link_2_track = 456; //7:36
var link_3_track = 665; //11:05

// Variables for status messages
var link_1_status = jQuery('.link_1_status');
var link_2_status = jQuery('.link_2_status');
var link_3_status = jQuery('.link_3_status');
var main_status = jQuery('.main_status');

// Message displayed when link is playing
var playNotice = ' - <strong><i>Now playing</i></strong>';

// Function to hide all status messages
hidePlayNoticeAll = function() {
	jQuery(".link_1_status").html("");
    jQuery(".link_2_status").html("");
    jQuery(".link_3_status").html("");
}

// Load Vimeo API for the embedded video
var iframe_player = jQuery('#playa')[0];
var playa = $f(iframe_player);

// Option listeners for pause, finish, and playProgress
// Note: If you want to use this, you must define the functions or links wont work 
playa.addEvent('ready', function() {
    playa.addEvent('pause', onPause);
    playa.addEvent('finish', onFinish);
    playa.addEvent('playProgress', onPlayProgress);
}); 

// Functions for each listener event
function onPause(id) { // When the video is paused, do this.
    jQuery(".main_status").html('Paused');
}
function onFinish(id) { // When the video is finished, do this.
    jQuery(".main_status").html('Finished');
}

function onPlayProgress(data, id) { // While the video is playing, do this.
    jQuery(".main_status").html('Playing - ' + data.seconds + 's played'); // data.percent can also be used.
}


// Function to control what happens when each lesson link is clicked
function setupLinks() {
        
    jQuery(".link_1").click(function () {
        playa.api('play'); //Play the video
        playa.api('seekTo', link_1_track); //Seek to the number of seconds in the variable link_1_track
        hidePlayNoticeAll(); // Hide all status messages before displaying (to prevent them from sticking) 
        jQuery(".link_1_status").html(playNotice); //Display status message (playNotice) within span with class link_1_status
    });
    
    jQuery(".link_2").click(function () {
        playa.api('play');
        playa.api('seekTo', link_2_track);
        hidePlayNoticeAll();
        jQuery(".link_2_status").html(playNotice);
    });
    
    jQuery(".link_3").click(function () {
        playa.api('play');
        playa.api('seekTo', link_3_track);
        hidePlayNoticeAll();
        jQuery(".link_3_status").html(playNotice);
    });
}


setupLinks();

// Function to control what happens when each lesson link is clicked
function setupLinks() {
         
    jQuery(".link_1").click(function () {
        playa.api('play'); //Play the video
        playa.api('seekTo', link_1_track); //Seek to the number of seconds in the variable link_1_track
        hidePlayNoticeAll(); // Hide all status messages before displaying (to prevent them from sticking) 
        jQuery(".link_1_status").html(playNotice); //Display status message (playNotice) within span with class link_1_status
    });
     
    jQuery(".link_2").click(function () {
        playa.api('play');
        playa.api('seekTo', link_2_track);
        hidePlayNoticeAll();
        jQuery(".link_2_status").html(playNotice);
    });
     
    jQuery(".link_3").click(function () {
        playa.api('play');
        playa.api('seekTo', link_3_track);
        hidePlayNoticeAll();
        jQuery(".link_3_status").html(playNotice);
    });
}

function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
// Get the element with id="defaultOpen" and click on it
$("#defaultOpen").trigger('click');
