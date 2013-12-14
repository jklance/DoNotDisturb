var dndFilters = angular.module('dndFilters', []);

dndFilters.filter("twoDigit", function() {
    return function(inData) {
        if (inData != null && inData != undefined) {
            inData = String(inData);
            if (inData.length < 2) {
                inData = String("0") + inData;
            }
        }
        return inData;
    };
});
