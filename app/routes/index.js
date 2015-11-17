module.exports = function(app, router, mongoose) {
  require('./pages')(app, router, mongoose);
  require('./api/index.js')(app, router, mongoose);

  router.route('/').get(function(request, response) {
    response.render('index');
  });

  app.use('/', router);
}
