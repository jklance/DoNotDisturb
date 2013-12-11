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
        var defaultTime     = <?php echo $configs['general']['default_timer']; ?>;
        var startingTime    = "00:00";
        var busyText        = "<?php echo $configs['site_text']['busy_text']; ?>";
        var freeText        = "<?php echo $configs['site_text']['free_text']; ?>";
        var buttonStartText = "<?php echo $configs['site_buttons']['start_text']; ?>";
        var buttonStopText  = "<?php echo $configs['site_buttons']['stop_text']; ?>";
        var buttonResetText = "<?php echo $configs['site_buttons']['reset_text']; ?>";
        var siteUrl         = "<?php echo $configs['paths']['install_url']; ?>";
        var pollingTime     = <?php echo $configs['general']['polling_time']; ?> * 1000;
    </script>
    <title>DoNotDisturb Admin</title>
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
        <div id="control">
            <button id="btnControl"><?php echo $configs['site_buttons']['start_text']; ?></button>&nbsp;
            <button id="btnReset"><?php echo $configs['site_buttons']['reset_text']; ?></button>
        </div>
        <div id="timerURL">
            View my status at: 
            <a href="<?php echo $configs['paths']['install_url']; ?>">
                <?php echo $configs['paths']['install_url']; ?>
            </a>
        </div>
    </div>
    <footer>
        <div id="copyright">&copy;2013 Jer Lance &lt;me@jerlance.com&gt;</div>
    </footer>
</body>

<script type="text/javascript">
var secondsLeft = 0;
var execTimer;
var timerParts;

$(document).ready(function() {
    $("#timer").text(startingTime);
    $("#arrow").hide();
    $("#btnControl").text(buttonStartText);

    if (startingTime != "00:00") {
        doStartTimerActions();
    }
    $("#btnControl").click( function() { handleButtonClick(); });
    $("#btnReset").click( function() { handleResetClick(); });

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
                styleTextAsBusy();
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

function handleButtonClick() {
    var currBtnText = $("#btnControl").text();
    switch(currBtnText) {
        case buttonStartText:
            handleResetClick();
            resetTimer();
            doStartTimerActions();
            break;
        case buttonStopText:
            clearTimer();
            doStopTimerActions();
            break;
    }
}

function handleResetClick() {
    resetTimer();
}

function resetTimer() {
    $.ajax({
        url: siteUrl + "/resetTimer.php?delay_time=" + defaultTime,
        success: function() {
            getCurrentTimeFromServer();
        }
    });
}
function clearTimer() {
    $.ajax({
        url: siteUrl + "/clearTimer.php",
        success: function() {

        }
    });
}


function doStartTimerActions() {
    var timerTxt = $('#timer').text();
    if (timerTxt == "00:00") {
        $('#timer').text(startingTime);
    }
    $("#btnControl").text(buttonStopText);
    $("#contentText").text(busyText);
    $("#arrow").show();
    styleTextAsBusy();
    countdown();
}
function doStopTimerActions() {
    $("#btnControl").text(buttonStartText);
    $("#contentText").text(freeText);
    $("#arrow").hide();
    styleTextAsFree();
    clearInterval(execTimer);
}
function styleTextAsBusy() {
    $('header').addClass('busy');
    $('#content').addClass('busy');
}
function styleTextAsFree() {
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
