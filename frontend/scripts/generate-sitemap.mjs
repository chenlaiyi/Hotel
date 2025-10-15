import axios from 'axios';
import fs from 'fs';
import path from 'path';

const apiBase = process.env.VITE_API_BASE || 'http://localhost:8080';
const domain = process.env.SITE_DOMAIN || 'https://example.com';

async function fetchArticles() {
  try {
    const { data } = await axios.get(`${apiBase}/api/hotel/articles`, {
      params: { limit: 50 }
    });
    return data.items || [];
  } catch (error) {
    console.warn('[sitemap] 获取文章失败，使用空列表。', error?.message || error);
    return [];
  }
}

async function main() {
  const articles = await fetchArticles();
  const urls = [
    { loc: `${domain}/`, priority: '1.0' },
    ...articles.map((article) => ({
      loc: `${domain}/news/${article.id}`,
      priority: '0.7'
    }))
  ];

  const xml = `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n${urls
    .map(
      (url) => `  <url>
    <loc>${url.loc}</loc>
    <priority>${url.priority}</priority>
  </url>`
    )
    .join('\n')}\n</urlset>\n`;

  const outputPath = path.resolve('public/sitemap.xml');
  fs.mkdirSync(path.dirname(outputPath), { recursive: true });
  fs.writeFileSync(outputPath, xml);
  console.log(`[sitemap] 已生成 ${outputPath}`);
}

main();
