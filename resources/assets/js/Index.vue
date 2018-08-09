<template>
  <div>
    <AppLayout v-if="$store.state.user"></AppLayout>
    <LoginLayout v-else></LoginLayout>
  </div>
</template>

<script>
  import AppLayout from './layouts/AppLayout'
  import LoginLayout from './layouts/LoginLayout'

  export default {
    props: ['user'],
    components: { AppLayout, LoginLayout },
    created() {
      if (this.user) {
        this.$store.commit('setUser', this.user)
      }
      if (localStorage.hasOwnProperty('drawer')) {
        this.$store.commit('toggleDrawer', localStorage.getItem('drawer') === 'true')
      }
    }
  }
</script>
