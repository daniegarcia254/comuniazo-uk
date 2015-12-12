/**
 * Created by Daniel on 08/12/2015.
 */

//Directive for show a player lineup points
comunioApp.directive('pointsTable', function() {
    return {
        restrict: 'E',
        templateUrl: 'public/templates/points_table.html'
    };
});