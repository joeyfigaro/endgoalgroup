var bodyParser = require('body-parser');
var helmet = require('helmet');

module.exports = function(express, app) {
  app.set('view engine', 'mustache');
  app.set('views', './views');
  app.engine('mustache', require('hogan-middleware').__express);
  app.use(express.static('./public'));
  app.use(helmet());
  app.use(bodyParser.urlencoded({ extended: true }));
  app.use(bodyParser.json());
}
