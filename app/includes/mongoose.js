var config = require('config');

module.exports = function(app, mongoose) {
  app.set('app_db_connection', mongoose.createConnection(config.get('Database.app.uri')));
  mongoose.connect(config.get('Database.app.uri'));

  mongoose.connection.on('connected', function() {
      console.log('Connection established successfully');
  });

  mongoose.connection.on('error', function(error) {
      console.log('Error connecting to database: ' + error);
  });

  // Bootstrap models
  mongoose.model('Event', require('../models/Event'));
}
