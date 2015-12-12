'use strict';

function ErrorHandler(message, errorType) {
    this.message = message;
    this.name = 'ErrorHandler';
    if (errorType) {
        this.errorType = errorType;
    } else {
        this.errorType = 500;
    }
}

ErrorHandler.prototype = Object.create(Error.prototype);
ErrorHandler.prototype.constructor = ErrorHandler;

module.exports = ErrorHandler;

// error codes
module.exports.NOT_FOUND = 404;