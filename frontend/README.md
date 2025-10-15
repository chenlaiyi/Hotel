# 武夷山九曲绿地铂骊酒店 SSR 前端原型

该目录提供基于 **Vite + Vue 3 + vite-plugin-ssr** 的轻量脚手架，用于将现有静态首页升级为可对接店滴云（DDIoT）接口的动态站点。

## 项目结构速览

```
frontend/
├── package.json             # 依赖与脚本
├── tsconfig.json
├── vite.config.ts
├── server/                  # 开发 & 预览服务器脚本
├── src/
│   ├── pages/               # 基于文件的页面（index 已接入样例）
│   └── renderer/            # SSR 渲染逻辑
└── README.md
```

核心页面位于 `src/pages/index/+Page.vue`，服务端数据获取逻辑在 `src/pages/index/+Page.server.ts`。默认会调用以下拟定 API：

- `GET /api/hotel/rooms?limit=6`
- `GET /api/hotel/meetings?limit=3`
- `GET /api/hotel/articles?limit=4`

如暂未实现对应后端接口，页面会回退到静态占位数据。

## 使用步骤

1. 在 `frontend/` 目录安装依赖：
   ```bash
   cd ddiot/frontend
   npm install
   ```
2. 启动 SSR 开发服务器（默认端口 5173）：
   ```bash
   npm run dev
   ```
   `VITE_API_BASE` 会默认指向 `http://localhost:8080`，可在命令前通过 `cross-env` 覆盖。
3. 构建与预览：
   ```bash
   npm run build
   npm run preview
   ```
4. 生成站点地图（会调用 `/api/hotel/articles`）：
   ```bash
   npm run sitemap
   ```

## 如何对接店滴云数据

1. 在后台或自建 API 层开放 REST 接口，例如：
   - `/api/hotel/rooms`：聚合 `dd_diandi_place_room_type` 表字段，返回 `{ id, title, summary, thumb }`。
   - `/api/hotel/meetings`：聚合 `dd_diandi_place_room` 会务场地数据。
   - `/api/hotel/articles`：聚合 `dd_diandi_website_article` 新闻/活动数据，附带分类名称与时间。
2. 确认接口返回 JSON，字段名称与 `+Page.server.ts` 中解构保持一致。
3. 若需要自定义字段，可修改 `onBeforeRender()` 中的 axios 请求与序列化逻辑。

## 鉴权、路由与 SEO

- 在 `src/pages/+onBeforeRoute.ts` 中实现了基础路由守卫：若页面 export `requiresAuth = true` 且用户未登录（示例通过 `hotel-auth=1` cookie 判断）即重定向至 `/login`。
- `src/pages/login/+Page.vue` 作为示例登录页，可替换为店滴云 SSO 或自建 OAuth。
- 在 `+Page.server.ts` 中注入 JSON-LD 结构化数据，`+onRenderHtml.ts` 会自动输出 `<script type="application/ld+json">`，提升 SEO 可见度。
- `scripts/generate-sitemap.mjs` 可以在 CI 中生成 `public/sitemap.xml`，配合 Nginx / CDN 部署。

## CI / CD

`.github/workflows/deploy.yml` 提供 GitHub Actions 示例流程：

1. 在 `main` 分支检测到 `frontend/` 变更时自动执行 `npm ci`、`npm run sitemap`、`npm run build`。
2. 构建产物以 artifact 方式上传，后续任务可通过 SCP、OSS、K8s 等方式部署到生产环境（需要在仓库中配置 `DEPLOY_HOST` 等 Secrets）。

## 下一步建议

- 将 SSR 服务部署至 Node / PM2 或 Nginx 反向代理，结合缓存层提升响应速度。
- 继续扩展后台 API，增加房态日历、在线预订等接口，并在前端添加对应路由与组件。
- 为 `scripts/generate-sitemap.mjs`、`API` 请求增加错误监测与告警，保障上线后的可 observability。
