import { redirect } from 'vite-plugin-ssr/abort';

export default function onBeforeRoute(pageContext: any) {
  const requiresAuth = pageContext.Page?.requiresAuth === true;
  const isAuthenticated = pageContext.user?.isAuthenticated;

  if (requiresAuth && !isAuthenticated) {
    throw redirect('/login');
  }
}
