import express from 'express';
import { renderPage } from 'vite-plugin-ssr/server';
import compression from 'compression';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');
const isProduction = process.env.NODE_ENV === 'production';
const port = process.env.PORT || 4173;

async function start() {
  const app = express();
  app.use(compression());
  app.use(express.static(path.join(root, 'dist/client')));

  app.get('*', async (req, res, next) => {
    const pageContextInit = { urlOriginal: req.originalUrl };
    const pageContext = await renderPage(pageContextInit);
    if (!pageContext.httpResponse) {
      return next();
    }
    const { body, statusCode, headers } = pageContext.httpResponse;
    headers.forEach(([name, value]) => res.setHeader(name, value));
    res.status(statusCode).send(body);
  });

  app.listen(port, () => {
    console.log(`▸ SSR preview server running at http://localhost:${port} (production=${isProduction})`);
  });
}

start();
