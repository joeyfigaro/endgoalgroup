'use strict';

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

var _express = require('express');

var _express2 = _interopRequireDefault(_express);

var _routes = require('./routes');

var _routes2 = _interopRequireDefault(_routes);

var _configApp = require('./config/app');

var _configApp2 = _interopRequireDefault(_configApp);

var app = (0, _express2['default'])();
var port = process.env.PORT || 3000;

app.listen(port);
console.log('Endgoalgroup running on port', port);
//# sourceMappingURL=maps/server.js.map