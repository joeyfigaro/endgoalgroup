import pages from './pages';

export default function(app, express) {
  const router = express.Router();

  router.use('/', router);
  app.use(router);
}
