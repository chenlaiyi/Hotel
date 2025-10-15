# 店滴云智能管理系统

## 系统介绍

店滴云是一款面向经营场所的智能化管理系统，专注于茶室、酒店、健身房、公寓、出租房等服务休闲性场所，帮助用户解决引流锁客、业务管理和智能控制三大核心问题。

### 核心功能

#### 引流锁客
- **OTA平台同步**：将经营数据实时同步至抖音、携程、美团、小红书等平台，实现精准锁客。
- **全媒体发布**：对接中部博主资源，实现服务数据的全媒体推广。

#### 业务管理
- **房量房态管理**：实时管理房产、房量、房态、订单、优惠、电商等数据。
- **多模块支持**：支持多模块安装，便于快速扩展业务。

#### 智能控制
- **智能设备控制**：支持智能门锁、开关、插座、窗帘、音箱、无人售货柜等设备的智能控制。

## 官方地址
- [店滴云官网](https://www.dandicloud.com/)
- [官方开源库](https://toscode.gitee.com/wayfirer)
- [CMS源码](https://gitee.com/wayfirer/ddiot)
- [用户端](https://ext.dcloud.net.cn/plugin?id=17811)
- [物联网SDK](https://ext.dcloud.net.cn/plugin?id=13579)
- [店滴管理后台](https://gitee.com/wayfirer/dd-admin)

## 参与交流
- **QQ群**：[开发者交流群](https://qm.qq.com/q/EFTWtaGuP0)（群号：789294254）
- **微信公众号**：
  ![店滴云公众号](https://diandi-1255369109.cos.ap-nanjing.myqcloud.com/cms%2F8edc20c70e46975e7520a8961414295.jpg)

## 系统特性
- 基于 **Yii2.0** 框架开发，稳定高效。
- 支持多层权限管理（路由权限、数据权限、菜单权限、集团权限、商户权限等）。
- 多模块可扩展，支持横向、纵向、双向扩展。
- 表单多样化，支持一句话配置万能表单。
- **Gii代码自动生成**，包括模块、模型、控制器、接口等。
- 前端采用 **Element-UI + Uniapp + 店滴CMS**，支持多端兼容。
- 支持 **RPC、WebSocket、Socket.IO**，实现多端通信。

## 使用教程

### 安装教程
请参考：[安装教程](./docs/系统安装.md)

### 开发教程

#### 主要技术栈
- **后端框架**：Yii2.0
- **前端框架**：Vue.js 2.5.x
- **路由**：Vue-Router 3.x
- **数据交互**：Axios
- **UI框架**：Element-UI 2.6.3
- **运行环境要求**：PHP 8.1+

#### 系统目录结构
- `addons`：扩展插件目录
- `admin`：管理后台接口
- `api`：API接口目录
- `common`：公共文件
- `console`：控制台入口
- `ddAdmin`：商家后台与总后台前端资源
- `docs`：开发文档
- `environments`：环境配置
- `frontend`：前端页面
- `help`：代码自动生成工具
- `uniapp`：多终端前端页面
- `vendor`：依赖库
- `.env.example`：配置示例
- `rpc.php`：RPC微服务启动入口
- `rpcClient.php`：RPC微服务调用示例

### 开发文档
- [命令行使用](./docs/命令行/command.md)
- [控制器使用](./docs/控制器/controller.md)
- [插件开发](./docs/插件开发/index.md)
- [数据库操作](./docs/数据库/smproxy.md)
- [模型操作](./docs/模型/model.md)
- [缓存机制](./docs/缓存/关于缓存.md)
- [表单验证](./docs/form.md)
- [接口说明](./docs/接口说明.md)
- [队列处理](./docs/队列.md)
- [微服务配置](./docs/rpc/http.md)

### 微服务
- **RPC服务**：支持微服务架构，提供HTTP、内部请求、插件微服务等配置。
- **数据库操作**：支持新增、更新、聚合、删除、查询、JOIN、事务等数据库操作。

## 特别鸣谢
感谢以下开源项目的支持：
- [Yii](http://www.yiiframework.com)
- [EasyWechat](https://www.easywechat.com)
- [Vue](https://vuejs.org/)
- [Element-UI](https://element.eleme.cn/)
- [Easyswoole](https://www.easyswoole.com)

## 开源协议
本项目采用 **MIT** 协议，详情请查看 [LICENSE](./LICENSE) 文件。