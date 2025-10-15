# Hotel 管理系统二次开发版

## 项目概览

本仓库是基于开源店滴云体系的二次开发项目，聚焦酒店、公寓及长短租场景的全流程运营管理。我们在保留原有能力的基础上，根据当前业务需要补充了多端会员应用、营销组件、设备对接能力以及更细粒度的权限体系，旨在为自营或加盟物业提供一站式的数字化管理方案。

## 新增与优化功能

- **业态适配**：针对连锁酒店、公寓及周边生活服务场景，补充了套餐房型、联合营销、入住前后服务等业务流程。
- **多端体验**：包含后台管理端（Vue + Element UI）、开放接口（REST / RPC）以及基于 UniApp 的会员端，支持 H5、小程序与 App 快速发布。
- **运营工具**：提供会员成长、优惠券、积分商城、活动配置等常见运营能力，支持单店与集团双层策略。
- **设备联动**：通过 IoT 插件对接智能门锁、能源开关等硬件，可按房间、时段或订单执行控制策略。
- **权限与审计**：补充岗位模板、数据分域和操作日志，满足多组织、多项目协同开发与运维需求。
- **开发支撑**：扩展 Gii 代码生成器与插件机制，便于在本地快速构建新模块、接口或自动化脚本。

## 技术栈

- **后端**：PHP 8.1+、Yii2、Composer、MySQL、Redis、RabbitMQ（可选）
- **管理端前端**：Vue 2.6、Element UI、Axios、Vite 构建脚本
- **会员端**：UniApp、Vuex、UView/自研组件
- **基础服务**：Nginx、Supervisor/pm2、Swoole RPC、WebSocket（订单推送）

## 目录速览

```
addons/             插件及扩展模块
admin/              后台接口与业务模块
api/                C 端及开放接口
common/             公共组件、服务、模型与工具类
console/            命令行入口、计划任务、迁移文件
develop/            自研后台前端资源（打包后发布至 public）
docs/               二开手册与功能说明
environments/       环境区分配置
frontend/           Web 前端工程脚手架
install/            安装向导及 SQL 初始化
public/             Web 根目录与静态资源
runtime/            缓存、日志、编译文件
uniapp/             会员端多端项目
vendor/             Composer 依赖
```

## 快速开始

### 环境要求

- PHP >= 8.1，启用常见扩展（pdo_mysql、mbstring、redis、swoole 可选）
- MySQL 5.7+/8.0，Redis 5+，Node.js 16+，npm 或 yarn
- Composer 2.x

### 安装步骤

1. 克隆代码并安装依赖：  

    ```bash
    git clone https://github.com/chenlaiyi/Hotel.git
    cd Hotel
    composer install
    npm install --prefix frontend
    npm install --prefix uniapp/hotel-member-app
    ```

2. 复制环境变量模板并根据实际环境填写：  

    ```bash
    cp .env.example .env
    ```

    - `DB_*`：数据库连接配置
    - `REDIS_*`：缓存与队列服务
    - `FILE_DOMAIN`、`UPLOAD_*`：静态资源域名
    - `IOT_*`、`SMS_*`：第三方接口密钥（按需配置）

3. 初始化数据库：  

    ```bash
    php yii migrate --interactive=0
    php yii diandi:init-admin # 初始化基础账户
    ```

4. 构建前端资源：  

    ```bash
    npm run build --prefix develop             # 管理端静态资源
    npm run build --prefix frontend            # Web 站点
    npm run build:h5 --prefix uniapp/hotel-member-app
    ```
5. 配置 Web 服务器，将 `public/` 设为站点根目录，并确保 `runtime/` 与 `vendor/` 可写。

> 如需快速体验，可参考 `install/` 下的安装向导或查看 `docs/系统安装.md`。

## 日常开发指南

- **热更新**：`npm run dev --prefix develop` 可开启管理端本地开发；UniApp 工程推荐使用 HBuilderX 或 `npm run dev:h5`。
- **代码规范**：遵循 PSR-12 与项目内置的 PHP-CS-Fixer 规则，可执行 `composer lint`。
- **插件扩展**：在 `addons/` 内实现模块化功能，激活流程为“上传包 → 安装 → 配置权限”。
- **队列与定时任务**：使用 `php yii queue/listen` 处理异步任务，Linux 建议配合 Supervisor 守护；计划任务通过 `console/controllers/*` 统一管理。
- **配置分层**：`common/config` 提供主配置，`environments/dev|prod` 可覆盖差异化参数。

## 部署建议

- 使用 CI/CD 将 `develop` 或 `frontend` 构建产物同步到 `public`，保持版本一致。
- 生产环境建议开启 PHP OPCache，并使用 `php yii diandi:cache/clear-all` 管理缓存。
- RPC、WebSocket 服务需分别通过 `php rpc.php start`、`php yii websocket/start` 启动，可根据节点拆分部署。

## 常见问题

- **迁移失败**：确认数据库字符集为 `utf8mb4`，并检查 `.env` 中的连接权限。
- **静态资源 404**：确保 `public/assets` 与 `public/attachment` 拥有写权限，且 `FILE_DOMAIN` 指向正确。
- **登录异常**：清除浏览器缓存，并检查 `common/config/main.php` 中的 `cookieValidationKey` 是否设置。

## 贡献与协作

- Fork 后提交 Pull Request，请确保通过 `composer test` 与相关前端构建。
- Issue 中请附带复现步骤、环境信息与相关日志，便于排查。
- 业务扩展建议以插件形式实现，减少核心代码冲突。

## 版权与协议

本项目遵循原始开源协议（MIT），详见 `LICENSE`。二次开发内容仅供研究与业务自用，请确保合法合规地对接第三方服务与内容资源。