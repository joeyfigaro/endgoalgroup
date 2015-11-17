module.exports = function(app, router, mongoose) {
  require('./events.js')(app, router, mongoose);
}
