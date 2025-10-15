<template>
  <div class="dashboard-container">
    <component :is="currentRole" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import blocDashboard from './bloc'
import storeDashboard from './store'
import pluginsDashboard from './plugins'

export default {
  name: 'Dashboard',
  components: { blocDashboard, storeDashboard, pluginsDashboard },
  data() {
    return {
      currentRole: 'blocDashboard'
    }
  },
  computed: {
    ...mapGetters(['roles'])
  },
  created() {
    // if (!this.roles.includes('admin')) {
    //   this.currentRole = 'editorDashboard'
    // }
  }
}
</script>
