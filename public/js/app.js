var comunioApp = angular.module('comunioUKApp', ['ngRoute','ngAnimate', 'ngResource', 'ui.bootstrap']);

comunioApp.
    config(['$routeProvider', function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'public/templates/main.html'
            }).
            //when('/add-user', {templateUrl: 'public/templates/add-new.html', controller: AddCtrl}).
            //when('/edit/:id', {templateUrl: 'public/templates/edit.html', controller: EditCtrl}).
            otherwise({redirectTo: '/'});
    }]);


/*function EditCtrl($scope, $http, $location, $routeParams) {
  var id = $routeParams.id;
  $scope.activePath = null;

  $http.get('api/users/'+id).success(function(data) {
    $scope.users = data;
  });

  $scope.update = function(user){
    $http.put('api/users/'+id, user).success(function(data) {
      $scope.users = data;
      $scope.activePath = $location.path('/');
    });
  };

  $scope.delete = function(user) {
    console.log(user);

    var deleteUser = confirm('Are you absolutely sure you want to delete?');
    if (deleteUser) {
      $http.delete('api/users/'+user.id);
      $scope.activePath = $location.path('/');
    }
  };
}*/