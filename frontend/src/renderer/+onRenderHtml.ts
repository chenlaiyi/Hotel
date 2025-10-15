import { escapeInject, dangerouslySkipEscape } from 'vite-plugin-ssr/server';
import { createApp } from './app';

export { render };

async function render(pageContext: any) {
  const app = createApp(pageContext);
  const appHtml = await app.renderToString();

  const { documentProps } = pageContext;
  const title = documentProps?.title || '武夷山九曲绿地铂骊酒店';
  const desc = documentProps?.description || '武夷山九曲溪畔园林会议度假酒店';
  const structuredData = documentProps?.structuredData
    ? JSON.stringify(documentProps.structuredData)
    : null;

  return escapeInject`<!DOCTYPE html>
    <html lang="zh-CN">
      <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>${title}</title>
        <meta name="description" content="${desc}" />
        ${structuredData ? dangerouslySkipEscape(`<script type="application/ld+json">${structuredData}</script>`) : ''}
      </head>
      <body>
        <div id="app">${dangerouslySkipEscape(appHtml)}</div>
      </body>
    </html>`;
}
