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
<html ng-app lang="en">
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
    <title>DoNotDisturb Timer</title>
    <meta charset="utf-8">
    <meta name="description" content="Visual timer to prevent disturbance from others">
    <meta name="author" content="Jer Lance <me@jerlance.com>">
    <!--script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script-->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.4/angular.min.js"></script>
    <script src="dnd.js"></script><script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.4/angular.min.js"></script>
    <link rel="stylesheet" type="text/css" href="dnd.css" />
</head>
<body>
    <div>
      <label>Name:</label>
      <input type="text" ng-model="yourName" placeholder="Enter a name here">
      <hr>
      <h1>Hello {{yourName}}!</h1>
    </div>
    <header ng-controller="TimerCtrl">
        <div id="timer">{{remaining()}}</div>
    </header>
    <div id="content">
        <div id="arrow">
            &uarr;
        </div>
        <div id="contentText" class="{{timer.status{{}}}}">{{currentStatus()}}</div>
        <div id="timerURL">Why am I doing this? <a href="http://heeris.id.au/2013/this-is-why-you-shouldnt-interrupt-a-programmer" target="_blank">Interruptions cost time and quality!</a></div>
    </div>
    <footer> 
        <div id="copyright">&copy;2013 Jer Lance &lt;me@jerlance.com&gt;</div>
    </footer>
</body>
</html>
