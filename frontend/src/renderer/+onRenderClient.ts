import { createApp } from './app';

export { render };

async function render(pageContext: any) {
  const app = createApp(pageContext);
  app.mount('#app');
}
