<template>
  <div class="sidebar-logo-container" :class="{ collapse: collapse }">
    <transition v-if="collapse" name="sidebarLogoFade">
      <img v-if="webSite.blogo" :src="webSite.blogo" class="sidebar-logo">
      <h1 v-else class="sidebar-title">{{ webSite.name }}</h1>
    </transition>

    <transition v-else name="sidebarLogoFade">
      <img v-if="webSite.blogo" :src="webSite.blogo" class="sidebar-logo">
      <h1 class="sidebar-title">{{ webSite.name }}</h1>
    </transition>

  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { config } from '@projectName/utils/publicUtil'
export default {
  name: 'SidebarLogo',
  props: {
    collapse: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      title: 'Vue Element Admin',
      siteName: config.siteName,
      logo: 'https://wpimg.wallstcn.com/69a1c46c-eb1c-4b46-8bd4-e9e686ef5251.png'
    }
  },
  computed: {
    ...mapGetters(['webSite', 'Layout', 'storeName'])
  },
  created() {
    console.log('webSite-logo', this.webSite)
  }
}
</script>

<style lang="scss" scoped>
.sidebarLogoFade-enter-active {
  transition: opacity 1.5s;
}

.sidebarLogoFade-enter,
.sidebarLogoFade-leave-to {
  opacity: 0;
}

</style>
