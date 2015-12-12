/**
 * Created by Daniel on 08/12/2015.
 */
'use strict';

var ErrorHandler = require('./modules/common/error');

function _handleErrors(err, req, res, next) {
    if (err instanceof ErrorHandler) {
        res.statusCode = err.errorType;
        res.send(err.message);
    } else {
        var error = new ErrorHandler('Unexpected Error');
        //console.log(err);
        res.statusCode = error.errorType;
        res.send(error.message);
    }
}

module.exports = function(app) {
    app.use(_handleErrors);
}