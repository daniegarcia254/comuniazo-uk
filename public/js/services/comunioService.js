/**
 * Created by Daniel on 10/10/2015.
 */
comunioApp.service('comunioService', function ($http, APP_CONSTANTS) {

    return({
        getUserPoints: getUserPoints
    });

    function getUserPoints(user, dates, ok, error) {
        $http.get(APP_CONSTANTS.API_URI+"/points/"+user+"?dates="+dates).then(
            function(response){
                console.log("Response",response);
                ok(response.data);
            },
            function(err){
                console.log("Error",err);
                error(err);
            }
        );
    }
});