var keys = require('../../config/keys.js');
var config = require('config');
var eventbrite = require('eventbrite');
var _ = require('underscore');

module.exports = function(app, router, mongoose) {
  var client = eventbrite({
    'app_key': 'YS2AMAIAJ7UPYIEVDC',
    'user_key': '1414067739124739137117'
  });
  var Event = mongoose.model('Event');

  try {
    var api = eventbrite({
      token: keys.eventbrite.oauth.personal,
      version: 'v3'
    });
  } catch (error) {
    throw(error.message);
  }

  router.route('/api/events').get(function(request, response) {
    Event.find({}, function(error, events) {
      var eventMap = {};

      events.forEach(function(event) {
        eventMap[event._id] = event;
      });

      response.send(eventMap);
    });
  });

  router.route('/api/events').post(function(request, response) {
    var action = request.body.config.action;
    var message = request.body;
    var api_url = request.body.api_url;
    var eid = api_url.replace('https://www.eventbriteapi.com/v3/events/', '');
    var event_id =  eid.replace('/', '');
    var event = new Event();

    client.event_get({ 'id': event_id }, function(err, data) {
      var event_data = data.event;

      //
      // Event published
      //
      if (action === 'event.published') {
        Event.findOne({ 'id': event_data.id }, function(error, event_object, done) {
          if (error) return done(error);

          if (event_object) {
            event_object.update({
              published: true
            }, function(error, raw) {
              if (error) throw error;

              console.log('MongoDB said: ' + raw);
            });

          }

          // No event found
          if (!event_object) {
            // Assign all of the returned event's tickets to an array
            var tickets = [];
             _.each(data.event.tickets, function(element, index, list) {
              tickets.push({
                id: element.ticket.id,
                name: element.ticket.name,
                fees: element.ticket.include_fee,
                description: element.ticket.description,
                start_date: element.ticket.start_date,
                end_date: element.ticket.end_date,
                min: element.ticket.min,
                max: element.ticket.max,
                price: element.ticket.price,
                sold: element.ticket.quantity_sold,
                visible: element.ticket.visible,
                currency: element.ticket.currency,
                available: element.ticket.quantity_available,
                display_price: element.ticket.display_price,
                type: element.ticket.type
              });
            });

            event.id = event_data.id;
            event.name = event_data.title;
            event.url = event_data.url;
            event.capacity = event_data.capacity;
            event.published = (event_data.status === 'Live') ? true : false;
            event.tickets = tickets;
            event.date = {
              start: event_data.start_date,
              end: event_data.end_date
            };
            event.category = event_data.category;

            event.save(function(error, done) {
              if (error) throw error;
            });
          }
        });
      } else if (action === 'event.unpublished') {
        Event.findOne({ 'id': event_data.id }, function(error, event_object, done) {
          console.log('\n\n\n' + event_object + '\n\n\n\n');
          if (error) return done(error);

          // No event found
          if (!event_object) {
            console.log('Event ' + event_id + ' seems to be missing from the database.');
          }

          if (event_object) {
            event_object.update({
              published: false
            }, function(error, raw) {
              if (error) throw error;

              console.log('MongoDB said: ' + raw);
            });
          }
        });
      }
    });

    response.status(200).send('A-OK!');
  });
}
