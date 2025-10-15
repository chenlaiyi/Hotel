import type { App } from 'vue';
import { inject } from 'vue';
import type { PageContextBuiltIn } from 'vite-plugin-ssr/types';

const key = Symbol('pageContext');

export function setPageContext(app: App, pageContext: PageContextBuiltIn) {
  app.provide(key, pageContext);
}

export function usePageContext() {
  const pageContext = inject<PageContextBuiltIn>(key);
  if (!pageContext) {
    throw new Error('pageContext missing. Did you forget to call setPageContext()?');
  }
  return pageContext;
}
