<template>
  <div>
    <center autocomplete="off" id='center' class="animated fadeIn" style='animation-duration: 1.5s'>
        <div class="g-recaptcha" data-sitekey="test" data-size="invisible" data-callback="captchaChecked"></div>
        <div class="login-logo group">
            <img src="/img/svg/logo.svg" />
        </div>
        <div class="input-groups">
            <div class="group">
                <input :disabled="sms_verification" type="text" id="inputLogin" placeholder="логин" autofocus
                  v-model="credentials.login" autocomplete="off">
            </div>
            <div class="group">
                <input :disabled="sms_verification" type="password" id="inputPassword" placeholder="пароль"
                  v-model="credentials.password" autocomplete="new-password">
            </div>
            <div class="group" v-show="sms_verification">
                <input type="text" id="sms-code" placeholder="sms code"
                  v-model="credentials.code" autocomplete="off">
            </div>
            <div class="group">
              <div class="btn btn-submit">
                <button @click="callback">войти</button>
              </div>
            </div>
            <!-- <div class="group">
              <g-recaptcha class="btn btn-submit"
                :data-sitekey="MIX_RECAPTCHA_SITE"
                :data-validate="validate"
                :data-callback="callback"
                :class="{'btn--disabled': loading}"
              >войти
              </g-recaptcha>
            </div> -->
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
        this.loading = true
        return true
      },

      callback(token) {
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
              console.log('login success')
          }
        }).catch(error => {
          this.error = error.response.data
        }).then(() => {
          this.loading = false
        })
      }
    }
  }
</script>

<style lang="scss">
  @import "~sass/login";
</style>
