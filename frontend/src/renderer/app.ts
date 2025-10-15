import { createSSRApp, createApp as createCSRApp, h } from 'vue';
import type { PageContextBuiltIn } from 'vite-plugin-ssr/types';
import PageShell from './PageShell.vue';
import { setPageContext } from './usePageContext';

export { createApp };

function createApp(pageContext: PageContextBuiltIn) {
  const { Page, pageProps } = pageContext;

  const create = import.meta.env.SSR ? createSSRApp : createCSRApp;
  const app = create({
    render: () => h(PageShell, null, { default: () => h(Page, pageProps || {}) })
  });

  setPageContext(app, pageContext);
  return app;
}
