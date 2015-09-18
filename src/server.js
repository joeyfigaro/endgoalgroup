import express from 'express';
import routes from './routes';
import config from './config/app';

const app = express();
const port = process.env.PORT || 3000;

app.listen(port);
console.log('Endgoalgroup running on port', port);
