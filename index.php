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
    $timeFile = "time_ending.txt";
    $endingTime = time();
    $timeLeft = 0;

    if (file_exists($timeFile)) {
        $fh = fopen($timeFile, "r");
            $endingTime = fread($fh, filesize($timeFile));
        fclose($fh);
        $timeLeft = $endingTime - time();
    }

    if ($timeLeft < 0) {
            $timeLeft = 0;
    }
?>

<html lang="en">
<head>
    <script type="text/javascript">
        var startingTime = "<?php echo gmdate("i:s", $timeLeft); ?>";
        var busyText = "Please do not interrupt me for this much longer. Thanks!";
        var freeText = "I'm not busy, please feel free to interrupt.";
        var buttonStartText = "Start";
        var buttonStopText = "Stop";
        var buttonResetText = "Reset";
    </script>
    <title>DoNotDisturb Timer</title>
    <meta charset="utf-8">
    <meta name="description" content="Visual timer to prevent disturbance from others">
    <meta name="author" content="Jer Lance <me@jerlance.com>">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
    <style type="text/css">
    body {
      font-family: "Lucida Console", Monaco, monospace;
      background-color: DarkSlateBlue;
      color: white;
    }
    #timer {
      font-size: 54pt;
      font-weight: bold;
      text-align: center;
    }
    #content {
      font-size: 36pt;
      text-align: center;
    }
    #arrow {
      font-size: 64pt;
      font-weight: bold;
    }
    #footer {
      position: relative;
    }
    #copyright {
      position: absolute;
      bottom: 5px;
      right: 10px;
      text-align: right;
      font-size: 10pt;
      font-style: italic;
    }
    .busy {
      color: red;
    }
    </style>
</head>
<body>
    <header>
        <div id="timer">00:00</div>
    </header>
    <div id="content">
        <div id="arrow">
            &uarr;
        </div>
        <div id="contentText">I'm not busy, please feel free to interrupt.</div>
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

    setTimeout(function() { location.reload(); }, 10000);
});

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
