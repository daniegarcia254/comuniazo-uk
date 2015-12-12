/**
 * Created by Daniel on 06/12/2015.
 */

//Directive for show a match table (players, result, points...)
angular.module('comunioUKApp').

    directive('matchTable', function() {
        return {
            restrict: 'E',
            templateUrl: '/templates/match_table.html'
        };
    });