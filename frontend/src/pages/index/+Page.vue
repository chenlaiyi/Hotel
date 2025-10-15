<template>
  <div class="page">
    <section class="hero">
      <div class="hero__inner">
        <p class="hero__eyebrow">武夷山 · 九曲溪畔</p>
        <h1>{{ hotel.name }}</h1>
        <p class="hero__lead">
          {{ hotel.summary }}
        </p>
        <div class="hero__cta">
          <a :href="`tel:${hotel.phone}`">电话预订</a>
          <a href="#news" class="secondary">查看最新活动</a>
        </div>
      </div>
    </section>

    <section class="block">
      <header class="block__header">
        <h2>客房精选</h2>
        <p>实时客房数据来自店滴云房态 API，如需更多房型请在后台新增后刷新页面。</p>
      </header>
      <div class="grid">
        <article v-for="room in rooms" :key="room.id" class="card">
          <img :src="resolveAsset(room.thumb)" :alt="room.title" loading="lazy" />
          <div class="card__body">
            <h3>{{ room.title }}</h3>
            <p>{{ room.summary }}</p>
          </div>
        </article>
        <p v-if="rooms.length === 0" class="placeholder">
          尚未接入房态数据，可通过 <code>/api/hotel/rooms</code> 端点返回房型信息。
        </p>
      </div>
    </section>

    <section class="block block--alt">
      <header class="block__header">
        <h2>会议与宴会</h2>
        <p>展示来自店滴云会议厅表的图文内容。</p>
      </header>
      <div class="grid grid--three">
        <article v-for="meeting in meetings" :key="meeting.id" class="card">
          <img :src="resolveAsset(meeting.thumb)" :alt="meeting.title" loading="lazy" />
          <div class="card__body">
            <h3>{{ meeting.title }}</h3>
            <p>{{ meeting.remark }}</p>
          </div>
        </article>
        <p v-if="meetings.length === 0" class="placeholder">
          尚无会议数据，请在数据库 `dd_diandi_place_room` 中补充并确保 API 返回字段。
        </p>
      </div>
    </section>

    <section id="news" class="block">
      <header class="block__header">
        <h2>新闻 / 活动</h2>
        <p>依托后台 CMS 新增的文章会自动展示，支持新闻与活动分类。</p>
      </header>
      <div class="articles">
        <article v-for="article in articles" :key="article.id">
          <img :src="resolveAsset(article.thumb)" :alt="article.title" loading="lazy" />
          <div class="articles__body">
            <span class="badge">{{ article.categoryName }}</span>
            <h3>{{ article.title }}</h3>
            <p>{{ article.summary }}</p>
            <small>{{ formatDate(article.createdAt) }}</small>
          </div>
        </article>
        <p v-if="articles.length === 0" class="placeholder">
          静态占位数据，接入后端接口 `/api/hotel/articles` 后即可自动填充。
        </p>
      </div>
    </section>

    <footer class="footer">
      <div>
        <h4>{{ hotel.name }}</h4>
        <p>{{ hotel.address }}</p>
        <p>预订电话：{{ hotel.phone }}</p>
      </div>
      <small>基于 Vite + vite-plugin-ssr + Vue 3，支持与店滴云 API 对接。</small>
    </footer>
  </div>
</template>

<script setup lang="ts">
import type { PageContextBuiltIn } from 'vite-plugin-ssr/types';

interface HotelInfo {
  name: string;
  summary: string;
  address: string;
  phone: string;
}

interface RoomPreview {
  id: number;
  title: string;
  summary: string;
  thumb: string;
}

interface MeetingPreview {
  id: number;
  title: string;
  remark: string;
  thumb: string;
}

interface ArticlePreview {
  id: number;
  title: string;
  summary: string;
  thumb: string;
  categoryName: string;
  createdAt: string;
}

const props = defineProps<PageContextBuiltIn & { pageProps: { hotel: HotelInfo; rooms: RoomPreview[]; meetings: MeetingPreview[]; articles: ArticlePreview[] } }>();

const { hotel, rooms, meetings, articles } = props.pageProps;

function resolveAsset(path: string) {
  if (!path) return '';
  if (path.startsWith('http')) return path;
  return `${import.meta.env.VITE_API_BASE || ''}/${path}`.replace(/\\/g, '/');
}

function formatDate(input: string) {
  if (!input) return '';
  const date = new Date(Number(input) * 1000 || input);
  return Number.isNaN(date.getTime()) ? input : date.toLocaleDateString();
}
</script>

<style scoped>
.page {
  font-family: "PingFang SC", "Microsoft Yahei", sans-serif;
  color: #18211f;
  background: #f6f6f4;
}
.hero {
  position: relative;
  background: linear-gradient(120deg, rgba(15,81,50,0.85), rgba(5,38,24,0.72)),
              url('attachment/hotelwuyi/hero_lobby.jpg') center/cover no-repeat;
  color: #fff;
  padding: 120px 24px;
  text-align: center;
}
.hero__inner {
  max-width: 720px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 18px;
}
.hero__eyebrow {
  letter-spacing: 4px;
  text-transform: uppercase;
  font-size: 12px;
}
.hero__lead {
  line-height: 1.9;
}
.hero__cta {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}
.hero__cta a {
  padding: 12px 30px;
  border-radius: 999px;
  font-weight: 600;
  background: #fff;
  color: #0f5132;
}
.hero__cta a.secondary {
  background: transparent;
  border: 1px solid rgba(255,255,255,0.7);
  color: #fff;
}
.block {
  max-width: 1100px;
  margin: 0 auto;
  padding: 72px 24px;
}
.block--alt {
  background: #ffffff;
  border-radius: 24px;
  margin-top: -32px;
  box-shadow: 0 18px 60px rgba(15,81,50,0.12);
}
.block__header {
  margin-bottom: 32px;
}
.block__header h2 {
  margin: 0 0 12px;
  font-size: 28px;
  color: #0f5132;
}
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 22px;
}
.grid--three {
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}
.card {
  background: #fff;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 12px 32px rgba(15,81,50,0.08);
  display: flex;
  flex-direction: column;
}
.card img {
  width: 100%;
  height: 210px;
  object-fit: cover;
}
.card__body {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.card__body h3 {
  margin: 0;
  font-size: 20px;
  color: #0f5132;
}
.card__body p {
  margin: 0;
  line-height: 1.7;
  color: #3e4a46;
}
.articles {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 24px;
}
.articles article {
  background: #fff;
  border-radius: 18px;
  overflow: hidden;
  display: grid;
  grid-template-columns: 1.1fr 1fr;
  box-shadow: 0 12px 32px rgba(15,81,50,0.08);
}
.articles article img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.articles__body {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.badge {
  display: inline-block;
  padding: 4px 12px;
  font-size: 12px;
  border-radius: 999px;
  background: rgba(15,81,50,0.12);
  color: #0f5132;
}
.placeholder {
  grid-column: 1 / -1;
  text-align: center;
  color: #8b8b8b;
}
.footer {
  background: #0c3520;
  color: #d6e9dc;
  padding: 48px 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  align-items: center;
  text-align: center;
}
.footer h4 {
  margin: 0 0 8px;
  letter-spacing: 2px;
}
@media (max-width: 768px) {
  .articles article {
    grid-template-columns: 1fr;
  }
}
</style>
