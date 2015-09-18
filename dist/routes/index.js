'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

var _pages = require('./pages');

var _pages2 = _interopRequireDefault(_pages);

exports['default'] = function (app, express) {
  var router = express.Router();

  router.use('/', router);
  app.use(router);
};

module.exports = exports['default'];
//# sourceMappingURL=../maps/routes/index.js.map