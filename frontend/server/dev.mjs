import { createServer as createViteServer } from 'vite';
import { createServer as createNodeServer } from 'http';
import { renderPage } from 'vite-plugin-ssr/server';

const port = process.env.PORT || 5173;

async function start() {
  const vite = await createViteServer({
    server: { middlewareMode: true },
    appType: 'custom'
  });

  const server = createNodeServer(async (req, res) => {
    const url = req.originalUrl || req.url || '/';
    const pageContextInit = { urlOriginal: url };
    const pageContext = await renderPage(pageContextInit);

    if (!pageContext.httpResponse) {
      res.statusCode = 404;
      res.end('Not Found');
      return;
    }

    const { body, statusCode, headers } = pageContext.httpResponse;
    res.writeHead(statusCode, headers);
    res.end(body);
  });

  server.on('request', vite.middlewares);
  server.listen(port, () => {
    console.log(`▸ SSR dev server ready at http://localhost:${port}`);
  });
}

start();
