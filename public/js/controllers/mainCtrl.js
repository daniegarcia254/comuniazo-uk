/**
 * Created by Daniel on 10/10/2015.
 */
comunioApp.controller('mainCtrl', function ($scope, $http, comunioService, $timeout) {

    $scope.ratings = [];
    $scope.players = [];

    $scope.calcularPuntos = function(){
        $scope.getLineupRatings(function(error, ratings){
            $('#calcularBtn').removeClass('loadinggif');
            $('#calcularBtn').html('¡Calcular!');

            if (error){
                window.alert(error.data.error);
            } else {
                console.log("Ratings",ratings);
                $scope.players = ratings;
                $('#totalPointsRow').css({ 'display': "table-row" });
                $scope.addEventsRows(ratings);
            }
        });
    };

    $scope.getLineupRatings = function(callback){
        console.log("Calcular puntos");
        if (($scope.user=='') || (typeof ($scope.user) == 'undefined') || ($scope.user == null)){
            $('#user-empty-error').show();
        } else {
            $('#user-empty-error').hide();
            $('#calcularBtn').html('');
            $('#calcularBtn').addClass('loadinggif');

            comunioService.getUserPoints($scope.user,
                function(ratings) {
                    console.log("Ratings", ratings);
                    callback(null, ratings);
                },
                function(err){ callback(err);}
            );
        }
    };

    $scope.totalPoints = function(){
        var sum = 0;
        angular.forEach($scope.players, function(player){
            if (player.ComunioRating != 'N/A')
                sum += parseInt(player.ComunioRating);
        });
        return sum;
    };

    $scope.addEventsRows = function(ratings){
        $timeout(function() {
            var rows = $('.pointstable tr');
            for (var i=1; i<rows.length-2; i++){
                var j=0, html='';
                for (j=0; j<ratings[i-1].Goals; j++){
                    html += '<img src="public/img/goal.png" alt="Goal" class="event-img">';
                }
                for (j=0; j<ratings[i-1].Yellow; j++){
                    html += '<img src="public/img/yellow.png" alt="Goal" class="event-img card-img">';
                }
                for (j=0; j<ratings[i-1].Red; j++){
                    html += '<img src="public/img/red.png" alt="Goal" class="event-img card-img">';
                }
                angular.element(rows[i]).append('<td>'+html+'</td>');
            }
        }, 500);
    };

    $scope.getPosClass = function(player){
        /*$timeout(function() {
            var rows = $('.pointstable tr');
            for (var i=1; i<rows.length-2; i++){
                var j=0, html='';
                for (j=0; j<ratings[i-1].Goals; j++){
                    html += '<img src="public/img/goal.png" alt="Goal" class="event-img">';
                }
                for (j=0; j<ratings[i-1].Yellow; j++){
                    html += '<img src="public/img/yellow.png" alt="Goal" class="event-img card-img">';
                }
                for (j=0; j<ratings[i-1].Red; j++){
                    html += '<img src="public/img/red.png" alt="Goal" class="event-img card-img">';
                }
                angular.element(rows[i]).append('<td>'+html+'</td>');
            }
        }, 500);*/
        console.log("getPosClass", player);
    };

});