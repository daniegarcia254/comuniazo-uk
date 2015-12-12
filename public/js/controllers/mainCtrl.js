/**
 * Created by Daniel on 10/10/2015.
 */
angular.module('comunioUKApp')

/**********************************************************************
 * mainCtrl: Controlador principal de la aplicación.
 ***********************************************************************/

    //.controller('mainCtrl', function ($document, $scope, comunioService) {
    .controller('mainCtrl', function ($scope, comunioService, APP_CONSTANTS) {

        $scope.calcularPuntos = function(){
            console.log("Calcular puntos");
            if (($scope.user=='') || (typeof ($scope.user) == 'undefined') || ($scope.user == null)){
                $('#user-empty-error').show();
            } else {
                $('#user-empty-error').hide();
                $('#calcularBtn').html('');
                $('#calcularBtn').addClass('loadinggif');

                comunioService.getUserId($scope.user, function(res){
                    //console.log("userId",res);

                    comunioService.getUserLineup(res.return.$value, function(userLineup){
                        //console.log("userLineup",userLineup);

                        angular.forEach(userLineup, function(player){
                            comunioService.getPlayerSearch(player, function(playerInfo){
                                console.log("Player ["+player+"] info:",playerInfo);
                                /*comunioService.getPlayerLastRating(playerInfo[0].link, function(res){
                                    console.log("Player ["+player+"] rating:",res.rating.replace(/\s+/g,""));
                                });*/
                            });
                        });

                        $('#calcularBtn').removeClass('loadinggif');
                        $('#calcularBtn').html('¡Calcular!');
                    });
                });
            }
        };

        function checkPremierLeagueTeam(value) {
            var r = false;
            angular.forEach(value, function(e){
                if (APP_CONSTANTS.teams.indexOf(e) != -1) { r=true; }
            });
            return r;
        }


    });