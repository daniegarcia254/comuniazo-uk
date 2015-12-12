/**
 * Created by Daniel on 10/10/2015.
 */
angular.module('comunioUKApp')

/**********************************************************************
 * showResultCtrl: Controller for keeping updated the table with the ToDo's
 ***********************************************************************/

    .controller('matchTableCtrl', function($scope, $rootScope, $http, comunioService){

        //Load
        /*comunioService.getAll(function(data) {
            $rootScope.toDos = data;
            $scope.totalItems = $rootScope.toDos.length;
            for (var i= 0; i<$rootScope.toDos.length; i++){
                $rootScope.toDos[i].editing = false;
            }
        });*/
    });