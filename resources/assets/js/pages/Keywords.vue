<template>
  <div>
    <v-dialog
      v-model="dialog"
      width="500"
    >
    <v-card v-if="dialog_item">
      <v-card-title>
        <span class="headline">{{ dialog_item.Keyword }}</span>
      </v-card-title>
      <v-card-text>
        <v-layout wrap align-center>
          <v-flex sm12 d-flex>
            <v-select
              clearable
              v-model="dialog_item.params.strategy.id"
              :items="strategies"
              item-text="name"
              item-value="id"
              label="стратегия"
            ></v-select>
          </v-flex>
          <v-flex sm12 d-flex v-if="selected_strategy">
            <v-select
              clearable
              v-model="dialog_item.params.strategy_mode_id"
              :items="selected_strategy.modes"
              :disabled="!selected_strategy"
              item-text="name"
              item-value="id"
              label="режим"
              >
            </v-select>
          </v-flex>
          <v-flex sm12 d-flex v-if="selected_strategy">
            <v-text-field v-model="dialog_item.params.param_1" label="желаемая позиция"></v-text-field>
          </v-flex>
        </v-layout>
        <v-card-actions>
         <v-spacer></v-spacer>
         <v-btn color="primary" flat @click.native="closeDialog">Отмена</v-btn>
         <v-btn color="primary" :loading="loading" @click.native="save">Сохранить</v-btn>
       </v-card-actions>
      </v-card-text>
    </v-card>
   </v-dialog>

    <v-data-table v-if="keywords.length" hide-actions hide-headers :items="keywords">
      <template slot="items" slot-scope="{ item }">
        <td>
          {{ item.Keyword }}
        </td>
        <td>
          {{ item.Bid }}
        </td>
        <td>
          {{ item.position }}
        </td>
        <td>
          <span v-if="item.params.strategy_mode_id">
            {{ item.params.strategy.name }}={{ item.params.param_1 }}
          </span>
        </td>
        <td class="text-xs-right" style='white-space: nowrap'>
          <v-btn flat icon color="grey" @click.stop="edit(item)">
            <v-icon>edit</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        dialog: false,
        loading: false,
        headers: [
          { text: 'ключевое слово', value: 'Keyword' },
          { text: 'ставка', value: 'Bid' },
          { text: 'средняя позиция', value: 'position' },
          { text: '', sortable: 'false' },
        ],
        dialog_item: null,
        strategies: [],
        strategy_modes: [],
        keywords: []
      }
    },

    created() {
      this.fetchKeywords()
      this.fetchStrategies()
    },

    watch: {
      '$store.state.campaign': 'fetchKeywords'
    },

    methods: {
      fetchKeywords() {
        this.$store.commit('loading', true)
        axios.get(apiUrl(`direct/keywords/${this.$store.getters.campaign.id}`)).then(response => {
          this.keywords = response.data
          this.$store.commit('loading', false)
        })
      },

      fetchStrategies() {
        axios.get(apiUrl(`strategies`)).then(response => this.strategies = response.data)
      },

      edit(item) {
        this.dialog_item = JSON.parse(JSON.stringify(item))
        this.dialog = true
      },

      save() {
        this.loading = true
        if (this.dialog_item.params.id) {
          if (this.dialog_item.params.strategy_mode_id && this.dialog_item.params.strategy.id) {
            var promise = axios.put(apiUrl(`strategies/${this.dialog_item.params.id}`), {
              ... _.pick(this.dialog_item.params, ['param_1'])
            })
          } else {
            var promise = axios.delete(apiUrl(`strategies/${this.dialog_item.params.id}`))
          }
        } else {
          var promise = axios.post(apiUrl(`strategies`), {
            keyword_id: this.dialog_item.Id,
            ... _.pick(this.dialog_item.params, ['strategy_mode_id', 'param_1'])
          })
        }
        promise.then(response => {
          this.keywords[this.keywords.findIndex(e => e.Id == this.dialog_item.Id)].params = response.data
          this.closeDialog()
          this.loading = false
        })
      },

      closeDialog() {
        this.dialog_item = null
        this.dialog = false
      }
    },

    computed: {
      selected_strategy() {
        if (this.dialog_item.params.strategy) {
          return this.strategies.find(s => s.id == this.dialog_item.params.strategy.id)
        }
        return null
      }
    }
  }
</script>
