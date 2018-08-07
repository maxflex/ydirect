<template>
  <div>
    <center autocomplete="off" class="login-form animated fadeIn">
        <div class="login-logo group">
            <img src="/img/svg/logo.svg" />
        </div>
        <div class="input-groups">
            <div class="group">
                <input :disabled="sms_verification" type="text" placeholder="логин" autofocus
                  ref="login" v-model="credentials.login" autocomplete="off" @keyup.enter="imitateSubmit">
            </div>
            <div class="group">
                <input :disabled="sms_verification" type="password" placeholder="пароль" ref="password"
                  v-model="credentials.password" autocomplete="new-password" @keyup.enter="imitateSubmit">
            </div>
            <div class="group" v-show="sms_verification">
                <input type="text" id="sms-code" placeholder="sms code" @keyup.enter="imitateSubmit"
                  v-model="credentials.code" autocomplete="off">
            </div>
            <div class="group">
              <div class="btn btn-submit" :class="{'btn--disabled': loading}" ref='submit'>
                <button @click="callback">войти</button>
              </div>
            </div>
        </div>
        <div v-show="error" class="login-errors">
          {{ error }}
        </div>
    </center>
  </div>
</template>

<script>
  import gRecaptcha from '@finpo/vue2-recaptcha-invisible';
  import Cookies from 'js-cookie';

  const TMP_CREDENTIALS_KEY = 'tmp-credentials'

  export default {
    components: { gRecaptcha },

    data() {
      return {
        credentials: {},
        loading: false,
        sms_verification: false,
        error: null
      }
    },

    created() {
      this.MIX_RECAPTCHA_SITE = process.env.MIX_RECAPTCHA_SITE
      const tmp_credentials = Cookies.getJSON(TMP_CREDENTIALS_KEY)
      if (tmp_credentials) {
        this.credentials = tmp_credentials
        this.sms_verification = true
      }
    },

    methods: {
      validate() {
        if (! this.credentials.login) {
          this.$refs.login.focus()
          return false
        }
        if (! this.credentials.password) {
          this.$refs.password.focus()
          return false
        }
        return true
      },

      callback(token) {
        this.loading = true
        axios.post(apiUrl('login'), {
          credentials: this.credentials,
          token
        }).then(response => {
          switch(response.status) {
            // подтверждение по смс
            case 202:
              this.sms_verification = true
              var {login, password} = this.credentials
              Cookies.set(TMP_CREDENTIALS_KEY, {login, password} , { expires: 1 / (24 * 60) * 2, path: '/' })
              break
            default:
              Cookies.remove(TMP_CREDENTIALS_KEY)
              this.$store.commit('setUser', response.data)
              location.reload()
          }
        }).catch(error => {
          this.error = error.response.data
        }).then(() => {
          this.loading = false
        })
      },

      imitateSubmit() {
        this.$refs.submit.querySelector('button').click()
      }
    }
  }
</script>

<style lang="scss">
  @import "~sass/login";
</style>
