var dndApp = angular.module('dndApp', ['dndFilters']);

dndApp.controller('TimerCtrl', function($scope, $timeout, $http) {
    var STATUSES        = {
        "FREE": "I'm not busy, please feel free to interrupt.",
        "BUSY": "Please do not interrupt me for this much longer. Thanks!",
    }
    var STATUS_CODES     = {
        "FREE": "free",
        "BUSY": "busy",
    }
    var NW_STATUSES      = {
        "UP"  : "up",
        "DOWN": "down",
    }
    var CONTROLS         = {
        "FREE": "Start",
        "BUSY": "Stop",
    }
    var DEFAULT_TIME     = 27 * 60; // Seconds
    var REFRESH_INTERVAL = 5;       // Seconds

    var offlineTimer     = 1;
    var timerObj;

    $scope.timeClock     = DEFAULT_TIME;
    $scope.timeSetting   = DEFAULT_TIME;
    $scope.controlButton = CONTROLS.FREE;
    $scope.statusText    = STATUSES.FREE;
    $scope.statusClass   = STATUS_CODES.FREE;
    $scope.networkStatus = NW_STATUSES.DOWN;
    $scope.displayTime   = {
        "h": "00",
        "m": "00",
        "s": "00",
    }

    $scope.init = function() {
        $scope.getServerTime();
        $scope.runTimer();
        if ($scope.timeClock) {
            $scope.setPageAsBusy();
        }
    }

    $scope.control = function() {
        if ($scope.timerIsRunning()) {
            $scope.stopTimerButton();
        } else {
            $scope.startTimerButton();
        }
    };

    $scope.stopTimerButton = function() {
        $scope.clearServerTime();
        $scope.setPageAsFree();
        $scope.timeClock = 0;
        $scope.generateTimeDisplay();
    }

    $scope.startTimerButton = function() {
        $scope.manageTimeSetting();
        $scope.resetServerTime($scope.timeSetting);
        $scope.getServerTime();
        $scope.setPageAsBusy();
    }

    $scope.manageTimeSetting = function() {
        if (!$scope.timeSetting) {
            $scope.timeSetting = DEFAULT_TIME;
        }
    }

    $scope.timerIsRunning = function() {
        if ($scope.controlButton == CONTROLS.FREE) {
            return false;
        }
        return true;
    }

    $scope.runTimer = function() {
        if ($scope.timeClock % REFRESH_INTERVAL == 0) {
            $scope.getServerTime();
        }
        $scope.generateTimeDisplay();
        timerObj = $timeout( function() {
            if ($scope.timeClock == 0) {
                $scope.setPageAsFree();
            } else {
                $scope.setPageAsBusy();
                --$scope.timeClock;
            }
            $scope.runTimer();
        }, 1000);
    };

    $scope.clearServerTime = function() {
        $http.get('clearTimer.php')
            .success(
                function(response, status, headers, config) {
                    $scope.networkStatus = NW_STATUSES.UP;
                }
            ).error(
                function(data, status, headers, config) {
                    $scope.networkStatus = NW_STATUSES.DOWN;
                }
            );
    }

    $scope.resetServerTime = function(delayTime) {
        if (delayTime == undefined) {
            delayTime = DEFAULT_TIME;
        }
        $http.get('resetTimer.php?delay_time=' + delayTime)
            .success(
                function(response, status, headers, config) {
                    $scope.networkStatus = NW_STATUSES.UP;
                }
            ).error(
                function(data, status, headers, config) {
                    $scope.networkStatus = NW_STATUSES.DOWN;
                }
            );
    }

    $scope.getServerTime = function() {
        $http.get('getTimer.php')
            .success(
                function(response, status, headers, config) {
                    $scope.networkStatus = NW_STATUSES.UP;
                    $scope.timeClock = response.time;
                    $scope.generateTimeDisplay();
                }
            ).error(
                function(data, status, headers, config) {
                    $scope.networkStatus = NW_STATUSES.DOWN;
                }
            );
    }

    $scope.generateTimeDisplay = function() {
        $scope.displayTime.h = Math.floor($scope.timeClock / (60 * 60));
        $scope.displayTime.m = Math.floor(($scope.timeClock % (60 * 60)) / 60);
        $scope.displayTime.s = ($scope.timeClock % 60);
    }

    $scope.setPageAsBusy = function() {
        $scope.controlButton = CONTROLS.BUSY;
        $scope.statusText = STATUSES.BUSY;
        $scope.statusClass = STATUS_CODES.BUSY;
    };

    $scope.setPageAsFree = function() {
        $scope.controlButton = CONTROLS.FREE;
        $scope.statusText = STATUSES.FREE;
        $scope.statusClass = STATUS_CODES.FREE;
    };

    $scope.init();
});
