<template>
  <div>
    <v-data-table v-if="keywords.length" hide-actions hide-headers :items="keywords">
      <template slot="items" slot-scope="{ item }">
        <td>
          {{ item.Keyword }}
        </td>
        <td>
          {{ item.Bid }}
        </td>
        <td class="text-xs-right" style='white-space: nowrap'>
          <v-btn flat icon color="grey">
            <v-icon>edit</v-icon>
          </v-btn>
          <v-btn flat icon color="grey">
            <v-icon>delete</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
  export default {
    data: () => {
      keywords: []
    },
    created() {
      this.$store.commit('loading', true)
      axios.get(apiUrl(`direct/keywords/${this.$store.state.campaign.id}`)).then(response => {
        this.keywords = response.data
        this.$store.commit('loading', false)
      })
    }
  }
</script>
