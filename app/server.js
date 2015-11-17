var express = require('express');
var app = express();
var port = process.env.PORT || 3000;
var router = express.Router();
var winston = require('winston');
var mongoose = require('mongoose');

// App configuration
require('./config/app.js')(express, app);

// DB configuration
require('./includes/mongoose.js')(app, mongoose);

// Routes
require('./routes/index.js')(app, router, mongoose);

app.listen(port);
console.log('Endgoalgroup running on port', port);
