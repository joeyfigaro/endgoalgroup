var superagent = require('superagent');

module.exports = function(app, router) {
  // Home
  router.route('/').get(function(request, response) {
    response.render('index', {
        title: 'Home',
        body_class: 'home'
    });
  });

  // About us
  router.route('/about-us').get(function(request, response) {
    response.render('about-us', {
        title: 'About Us',
        body_class: 'about-us'
    });
  });

  // Agile resources
  router.route('/agile-resources').get(function(request, response) {
    response.render('agile-resources', {
        title: 'Agile Resources',
        body_class: 'agile-resources'
    });
  });

  // Contact
  router.route('/contact').get(function(request, response) {
    response.render('contact', {
        title: 'Contact Us',
        body_class: 'contact'
    });
  });

  // Our services
  router.route('/our-services').get(function(request, response) {
    response.render('our-services', {
        title: 'Our Services',
        body_class: 'our-services'
    });
  });

  // Projects
  router.route('/projects').get(function(request, response) {
    response.render('projects', {
        title: 'Projects',
        body_class: 'projects'
    });
  });

  // Training and events
  router.route('/training-and-events').get(function(request, response) {
    response.render('training-and-events', {
        title: 'Training & Events',
        body_class: 'training-and-events'
    });
  });

  // Why endgoal
  router.route('/why-endgoal').get(function(request, response) {
    response.render('why-endgoal', {
        title: 'Why Endgoal',
        body_class: 'why-endgoal'
    });
  });

  // TrainIT
  router.route('/train-it').get(function(request, response) {
    response.render('train-it', {
        title: 'TrainIT',
        body_class: 'train-it'
    });
  });
}
