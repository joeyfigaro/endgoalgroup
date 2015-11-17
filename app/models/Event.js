var mongoose = require('mongoose'),
    Schema = mongoose.Schema;

var event_schema = new Schema({
  id: { type: String, required: true },
  organizer: { type: String, required: true },
  tracking_code: { type: String, required: false },
  categories: [
    {
      type: String, required: false
    }
  ],
  name: { type: String, required: true, index: true },
  description: { type: String, required: false },
  url: { type: String, required: true },
  capacity: { type: Number, required: true, default: 0 },
  published: { type: Boolean, required: true, index: true },
  tickets: [
    {
      id: { type: Number, required: true },
      name: { type: String, required: true },
      fees: { type: Boolean, required: false, default: false },
      description: { type: String, required: false },
      start_date: { type: Date, required: true },
      end_date: { type: Date, required: true },
      min: { type: Number, required: true, default: 1 },
      max: { type: Number, required: false, default: null },
      price: { type: String, required: true },
      sold: { type: Number, required: false, default: 0 },
      visible: { type: Boolean, required: true, default: true },
      currency: { type: String, required: false, default: 'USD' },
      available: { type: Number, required: true },
      display_price: { type: String, required: false },
      type: { type: Number, required: false, default: 0 }
    }
  ],
  date: {
    start: { type: Date, required: true },
    end: { type: Date, required: true }
  },
  venue: {
    city: { type: String, required: false },
    region: { type: String, required: false },
    country: { type: String, require: false }
  },
  category: { type: String, required: false }
});

event_schema.pre('save', function(next) {
  var event = this;

  // update updated_at field with current date
  event.updated_at = new Date();

  // if created_at doesn't exist, add field with current date
  if (!this.created_at)
    event.created_at = new Date();

  next();
});

event_schema.post('save', function(doc) {
  console.log('%s has been created!', doc.id);
});

// event_schema.plugin(require('mongoose-bcrypt'), { rounds: 12 });

module.exports = event_schema;
