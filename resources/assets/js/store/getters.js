import campaigns from '@/api/campaigns'

export default {
  campaign: state => {
    if (state.campaign) {
      return state.campaign
    } else {
      const campaign_id = localStorage.getItem('campaign_id')
      if (campaign_id) {
        return campaigns.find(campaign => campaign.id == campaign_id)
      }
    }
    return null
  },
  drawer(state) {
    if (localStorage.hasOwnProperty('drawer')) {
      return localStorage.getItem('drawer') === 'true'
    }
    return state.drawer
  }
}
