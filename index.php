<!doctype html>
<!--
    Copyright (c) 2013 Jer Lance <me@jerlance.com>

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.
-->
<?php 
    $configs = parse_ini_file('default.ini.php', true);
    if (!is_array($configs)) {
        die('Failure loading configurations.');
    }
?>
<html lang="en">
<head>
    <script type="text/javascript">
        var startingTime    = "00:00";
        var busyText        = "<?php echo $configs['site_text']['busy_text']; ?>";
        var freeText        = "<?php echo $configs['site_text']['free_text']; ?>";
        var siteUrl         = "<?php echo $configs['paths']['install_url']; ?>";
        var pollingTime     = <?php echo $configs['general']['polling_time']; ?> * 1000;
    </script>
    <title>DoNotDisturb Timer</title>
    <meta charset="utf-8">
    <meta name="description" content="Visual timer to prevent disturbance from others">
    <meta name="author" content="Jer Lance <me@jerlance.com>">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="dnd.css" />
</head>
<body>
    <header>
        <div id="timer">00:00</div>
    </header>
    <div id="content">
        <div id="arrow">
            &uarr;
        </div>
        <div id="contentText"><?php echo $configs['site_text']['free_text']; ?></div>
        <div id="timerURL">Why am I doing this? <a href="http://heeris.id.au/2013/this-is-why-you-shouldnt-interrupt-a-programmer" target="_blank">Interruptions cost time and quality!</a></div>
    </div>
    <footer>
        <div id="copyright">&copy;2013 Jer Lance &lt;me@jerlance.com&gt;</div>
    </footer>
</body>

<script type="text/javascript">
var secondsLeft = 0;
var timerRunning = false;
var execTimer;
var timerParts;

$(document).ready(function() {
    $("#timer").text(startingTime);
    $("#arrow").hide();

    if (startingTime != "00:00") {
        doStartTimerActions();
    }

    getCurrentTimeFromServer();
});

function getCurrentTimeFromServer() {
    $.ajax({
        url: siteUrl + "/getTimer.php",
        type: "GET",
        success: function(response) {
            var timeResults = $.parseJSON(response);
            $('#timer').text(timeResults.time);
            setTimeout(getCurrentTimeFromServer, pollingTime);

            if (timeResults.time == "00:00") {
                doStopTimerActions();
            } else {
                doStartTimerActions();
            }
        }
    })
}

function countdown() {
    secondsLeft = getSecondsLeft(getTimerParts());
    execTimer = setInterval(decrementTime, 1000);
}

function decrementTime() {
    getTimerParts();
    secondsLeft = getSecondsLeft();

    if (!secondsLeft) {
        clearInterval(execTimer);
        doStopTimerActions();
    }

    var mins = getMinutes();
    var secs = getSeconds();
    $("#timer").text(mins + ":" + secs);
}


function doStartTimerActions() {
    timerRunning = true;
    var timerTxt = $('#timer').text();
    if (timerTxt == "00:00") {
        $('#timer').text(startingTime);
    }
    styleTextAsBusy();
    countdown();
}
function doStopTimerActions() {
    timerRunning = false;
    styleTextAsFree();
    clearInterval(execTimer);
}
function styleTextAsBusy() {
    $("#btnControl").text(buttonStopText);
    $("#contentText").text(busyText);
    $("#arrow").show();
    $('header').addClass('busy');
    $('#content').addClass('busy');
}
function styleTextAsFree() {
    $("#btnControl").text(buttonStartText);
    $("#contentText").text(freeText);
    $("#arrow").hide();
    $('header').removeClass('busy');
    $('#content').removeClass('busy');
}

function getTimerParts() {
    var timer = $("#timer").text();
    timerParts =timer.split(':');
}

function getSecondsLeft() {
    var seconds = parseInt(timerParts[0]) * 60 + parseInt(timerParts[1]);
    return(--seconds);
}

function getMinutes() {
    var mins = Math.floor(secondsLeft / 60);
    if (mins < 10) {
        mins = "0" + mins;
    }
    return(mins);
}
function getSeconds() {
    var secs = secondsLeft % 60;
    if (secs < 10) {
        secs = "0" + secs;
    }
    return(secs);
}
</script>
</html>
