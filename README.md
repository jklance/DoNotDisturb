DoNotDisturb
============

Visual DND timer in jQuery and HTML5

Files
------------

###dnd.html
A standalone timer client for displaying locally only. It is completely 
self-contained, so the one file can be used without any of the rest.

###index.php
Server-based client. Will display the remaining time on the server-timer 
(if any).

###admin.php
Server-based admin for the client. Will display the remaining time, as 
well as allow for starting and restarting the timer.

###clearTimer.php 
Ajax endpoint to zero-out the server time.

###resetTimer.php
Ajax endpoint to set the server time back to the starting value.
