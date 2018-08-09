<template>
  <v-app id="inspire">
    <Menu></Menu>
    <v-toolbar app fixed clipped-left>
      <v-toolbar-side-icon @click.stop="$store.commit('toggleDrawer')"></v-toolbar-side-icon>
      <v-toolbar-title>
        <span v-if="$store.getters.campaign != null">{{ $store.getters.campaign.title }}</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon>
        <v-icon medium>notifications</v-icon>
      </v-btn>
    </v-toolbar>
    <v-content>
      <v-container fluid fill-height v-show="$store.state.loading">
        <v-layout justify-center align-center>
          <v-progress-circular :size="50" color="primary" indeterminate></v-progress-circular>
        </v-layout>
      </v-container>
      <v-container fluid v-show="!$store.state.loading">
        <router-view></router-view>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  import Menu from '@/components/UI/Menu'

  export default {
    components: { Menu },
  }
</script>
