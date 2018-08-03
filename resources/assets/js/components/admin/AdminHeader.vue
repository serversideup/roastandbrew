<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  header{
    background-color: #FFFFFF;
    height: 75px;

    z-index: 9999;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;

    img.logo{
      margin: auto;
      margin-top: 22.5px;
      margin-bottom: 22.5px;
      display: block;
    }

    img.hamburger{
      float: right;
      margin-right: 18px;
      margin-top: 30px;
      cursor: pointer;
    }

    img.avatar{
      float: right;
      margin-right: 20px;
      width: 40px;
      height: 40px;
      border-radius: 20px;
      margin-top: 18px;
    }

    &:after{
      content: "";
      display: table;
      clear: both;
    }
  }

  /* Small only */
  @media screen and (max-width: 39.9375em) {
    nav.top-navigation{
      span.login{
        display: none;
      }

      img.hamburger{
        margin-top: 26px;
      }
    }
  }

  /* Medium only */
  @media screen and (min-width: 40em) and (max-width: 63.9375em) {

  }

  /* Large only */
  @media screen and (min-width: 64em) and (max-width: 74.9375em) {

  }
</style>

<template>
  <header class="admin-header">
    <div class="grid-x">
      <div class="large-4 medium-4 small-4 cell">

      </div>
      <div class="large-4 medium-4 small-4 cell">
        <router-link :to="{ name: 'cafes'}">
          <img src="/img/logo.svg" class="logo"/>
        </router-link>
      </div>
      <div class="large-4 medium-4 small-4 cell">
        <img class="hamburger" src="/img/hamburger.svg" v-on:click="setShowPopOut()"/>
        <img class="avatar" v-if="user != '' && userLoadStatus == 2" :src="user.avatar" v-show="userLoadStatus == 2"/>
        <span class="login" v-if="user == ''" v-on:click="login()">Sign In</span>
      </div>
    </div>
  </header>
</template>

<script>
  export default {
    /*
      Defines the computed properties on the component.
    */
    computed: {
      /*
        Retrieves the User Load Status from Vuex
      */
      userLoadStatus(){
        return this.$store.getters.getUserLoadStatus();
      },

      /*
        Retrieves the User from Vuex
      */
      user(){
        return this.$store.getters.getUser;
      }
    },

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Toggles the showing of the pop out side nav
      */
      setShowPopOut(){
        this.$store.dispatch( 'toggleShowPopOut', { showPopOut: true } );
      }
    }
  }
</script>
