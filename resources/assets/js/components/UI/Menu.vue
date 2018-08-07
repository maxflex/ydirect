<template>
  <v-navigation-drawer
    clipped
    fixed
    v-model="drawer"
    app
  >
    <v-list dense>
      <v-list-group
         no-action
       >
         <v-list-tile slot="activator">
           <v-list-tile-action>
             <v-icon>dashboard</v-icon>
           </v-list-tile-action>
           <v-list-tile-title>Кампании</v-list-tile-title>
         </v-list-tile>

         <v-list-tile v-for="campaign in campaigns" :key="campaign.id" @click="selectCampaign(campaign)">
           <v-list-tile-action>
             <v-list-tile-title>{{ campaign.title }}</v-list-tile-title>
           </v-list-tile-action>
         </v-list-tile>
       </v-list-group>
      <v-list-tile @click="">
        <v-list-tile-action>
          <v-icon>attach_money</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>Ставки</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <v-list-tile @click="">
        <v-list-tile-action>
          <v-icon>settings</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>Настройки</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <v-list-tile @click="">
        <v-list-tile-action>
          <v-icon>exit_to_app</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>Выход</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
import campaigns from '@/api/campaigns'

export default {
  data: () => ({
    drawer: true,
    campaigns
  }),

  methods: {
    selectCampaign(campaign) {
      this.$store.commit('setCampaign', campaign)
      this.$router.push('/keywords')
    }
  }
}
</script>
