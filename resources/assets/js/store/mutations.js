export default {
    setUser(state, user) {
      state.user = user
    },
    setCampaign(state, campaign) {
      state.campaign = campaign
      localStorage.setItem('campaign_id', campaign.id)
    },
    toggleDrawer(state, value = null) {
      if (value !== null) {
        state.drawer = value
      } else {
        state.drawer = !state.drawer
      }
      localStorage.setItem('drawer', state.drawer)
    },
    loading(state, value) {
      state.loading = value
    }
}
