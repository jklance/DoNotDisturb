var dndApp = angular.module('dndApp', ['dndFilters']);

dndApp.controller('TimerCtrl', function($scope, $timeout) {
    var statuses      = {
        "free": "I'm not busy, please feel free to interrupt.",
        "busy": "Please do not interrupt me for this much longer. Thanks!",
    }
    var controls      = {
        "free": "Start",
        "busy": "Stop",
    }
    var defaultTime   = 7;

    var timeClock     = defaultTime;
    var timerObj;

    $scope.controlButton = controls.free;
    $scope.resetButton   = 'Reset';
    $scope.statusText    = statuses.free;
    $scope.displayTime   = {
        "h": "00",
        "m": "00",
        "s": "00",
    }

    $scope.control = function() {
        if ($scope.timeClock != 0 && $scope.controlButton == controls.free) {
            $scope.setPageAsBusy();
            $scope.runTimer();
        } else {
            $scope.setPageAsFree();
            $timeout.cancel(timerObj);
        }
    };

    $scope.reset = function() {
        $scope.setPageAsFree();
        $timeout.cancel(timerObj);
        timeClock = defaultTime;
        $scope.generateTimeDisplay();
    };

    $scope.runTimer = function() {
        timerObj = $timeout( function() {
            if ($scope.timeClock == 0) {
                $timeout.cancel(timerObj);
                $scope.setPageAsFree();
            } else {
                --timeClock;
                $scope.generateTimeDisplay();
                $scope.runTimer();
            }
        }, 1000);
    };

    $scope.generateTimeDisplay = function() {
        $scope.displayTime.h = Math.floor(timeClock / (60 * 60));
        $scope.displayTime.m = Math.floor((timeClock % (60 * 60)) / 60);
        $scope.displayTime.s = (timeClock % 60);
    }

    $scope.setPageAsBusy = function() {
        $scope.controlButton = controls.busy;
        $scope.statusText = statuses.busy;
    };

    $scope.setPageAsFree = function() {
        $scope.controlButton = controls.free;
        $scope.statusText = statuses.free;
    };
});
