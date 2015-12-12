/**
 * Created by Daniel on 12/12/2015.
 */

comunioApp.filter('transformPosition', function () {

    return function (value) {
        switch (value){
            case 'Goalkeeper':
                return 'GK'; break;
            case 'Defender':
                return 'DF'; break;
            case 'Midfielder':
                return 'MD'; break;
            case 'Striker':
                return 'FW'; break;
        }
    };
});