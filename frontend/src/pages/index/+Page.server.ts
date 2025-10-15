import axios from 'axios';

interface RoomPreview {
  id: number;
  title: string;
  summary: string;
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

interface MeetingPreview {
  id: number;
  title: string;
  remark: string;
  thumb: string;
}

const FALLBACK_DATA = {
  hotel: {
    name: '武夷山九曲绿地铂骊酒店',
    summary:
      '坐落于九曲溪畔的园林会议度假酒店，融合武夷茶文化、山水景观与现代会务设施。',
    address: '福建省南平市武夷山市九曲溪路 1 号',
    phone: '0599-1234567'
  },
  rooms: [] as RoomPreview[],
  meetings: [] as MeetingPreview[],
  articles: [] as ArticlePreview[]
};

export async function onBeforeRender(pageContext: any) {
  const apiBase = process.env.VITE_API_BASE || 'http://localhost:8080';
  const cookie = pageContext?.request?.headers?.cookie || '';
  const isAuthenticated = /hotel-auth=1/.test(cookie);

  const structuredData = {
    '@context': 'https://schema.org',
    '@type': 'Hotel',
    name: FALLBACK_DATA.hotel.name,
    telephone: FALLBACK_DATA.hotel.phone,
    address: {
      '@type': 'PostalAddress',
      streetAddress: FALLBACK_DATA.hotel.address
    },
    url: pageContext?.urlPathname ? `https://example.com${pageContext.urlPathname}` : undefined
  };

  try {
    const [{ data: rooms }, { data: meetings }, { data: articles }] = await Promise.all([
      axios
        .get(`${apiBase}/api/hotel/rooms`, { params: { limit: 6 } })
        .then((res) => res.data)
        .catch(() => ({ items: [] })),
      axios
        .get(`${apiBase}/api/hotel/meetings`, { params: { limit: 3 } })
        .then((res) => res.data)
        .catch(() => ({ items: [] })),
      axios
        .get(`${apiBase}/api/hotel/articles`, { params: { limit: 4 } })
        .then((res) => res.data)
        .catch(() => ({ items: [] }))
    ]);

    return {
      pageProps: {
        hotel: FALLBACK_DATA.hotel,
        rooms: rooms.items || [],
        meetings: meetings.items || [],
        articles: articles.items || [],
        user: { isAuthenticated }
      },
      documentProps: {
        title: '武夷山九曲绿地铂骊酒店 | 官方站',
        description:
          '武夷山九曲绿地铂骊酒店官方 SSR 站点，通过店滴云 API 拉取客房、会议、餐饮与活动资讯。',
        structuredData
      },
      user: { isAuthenticated }
    };
  } catch (error) {
    console.warn('[hotel-frontend] 使用静态占位数据：', error);
    return {
      pageProps: { ...FALLBACK_DATA, user: { isAuthenticated } },
      documentProps: {
        title: '武夷山九曲绿地铂骊酒店 | 官方站',
        description:
          '武夷山九曲绿地铂骊酒店官方 SSR 站点，通过店滴云 API 拉取客房、会议、餐饮与活动资讯。',
        structuredData
      },
      user: { isAuthenticated }
    };
  }
}
