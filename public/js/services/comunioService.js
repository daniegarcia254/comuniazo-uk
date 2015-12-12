/**
 * Created by Daniel on 10/10/2015.
 */
angular.module('comunioUKApp')

    .service('comunioService', function ($http, APP_CONSTANTS) {

        return({
            getUserId: getUserId,
            getUserLineup: getUserLineup,
            getPlayerSearch: getPlayerSearch,
            getPlayerLastRating: getPlayerLastRating
        });

        function getUserId(user, cb) {
            return $http.get(APP_CONSTANTS.API_URI+"/id/"+user)
                .success(cb)
                .error(function(error){
                    console.log(error);
                });
        }

        function getUserLineup(userId, cb) {
            return $http.get(APP_CONSTANTS.API_URI+"/lineup/"+userId)
                .success(cb)
                .error(function(error){
                    console.log(error);
                });
        }

        function getPlayerSearch(player,cb) {
            return $http.get(APP_CONSTANTS.API_URI+"/info/"+player)
                .success(cb)
                .error(function(error){
                    console.log(error);
                });
        }

        function getPlayerLastRating(playerURI,cb) {
            return $http.get(APP_CONSTANTS.API_URI+"/rating/last?playerurl="+playerURI)
                .success(cb)
                .error(function(error){
                    console.log(error);
                });
        }
    });