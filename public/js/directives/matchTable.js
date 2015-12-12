/**
 * Created by Daniel on 06/12/2015.
 */

//Directive for show a match table (players, result, points...)
comunioApp.directive('matchTable', function() {
        return {
            restrict: 'E',
            templateUrl: 'public/templates/match_table.html'
        };
    });