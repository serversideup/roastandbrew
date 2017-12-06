<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  nav.top-navigation{
    background-color: $white;
    height: 50px;
    border-bottom: 2px solid $dark-color;

    span.logo{
      border-right: 1px solid $dark-color;
      display: block;
      float: left;
      height: 50px;
      line-height: 50px;
      padding: 0px 20px 0px 20px;
      font-family: 'Josefin Sans', sans-serif;
      font-weight: bold;
      color: $dark-color;

      &:hover{
        color: white;
        background-color: $dark-color;
      }
    }

    ul.links{
      display: block;
      float: left;

      li{
        display: inline-block;
        list-style-type: none;
        line-height: 50px;

        a{
          font-family: 'Lato', sans-serif;
          font-weight: bold;
          color: $black;

          &:hover{
            color: $dark-color;
          }
        }
      }
    }

    div.right{
      float: right;

      img.avatar{
        width: 40px;
        height: 40px;
        border-radius: 40px;
        margin-top: 5px;
        margin-right: 10px;
      }

      span.login{
        height: 50px;
        line-height: 50px;
        padding: 0px 20px 0px 20px;
        font-family: 'Josefin Sans', sans-serif;
        font-weight: bold;
        color: $dark-color;
        cursor: pointer;
      }

      span.logout{
        height: 50px;
        line-height: 50px;
        padding: 0px 20px 0px 20px;
        font-family: 'Josefin Sans', sans-serif;
        font-weight: bold;
        color: $dark-color;
        cursor: pointer;
      }
    }

    &:after{
      content: "";
      display: table;
      clear: both;
    }
  }
</style>

<template>
  <nav class="top-navigation">

    <router-link :to="{ name: 'home'}">
      <span class="logo">Roast And Brew</span>
    </router-link>

    <ul class="links">
      <li>
        <router-link :to="{ name: 'cafes' }">
          Cafes
        </router-link>
      </li>
    </ul>

    <div class="right">
      <img class="avatar" v-if="user != '' && userLoadStatus == 2" :src="user.avatar" v-show="userLoadStatus == 2"/>
      <span class="logout" v-if="user != '' && userLoadStatus == 2" v-on:click="logout()">Logout</span>
      <span class="login" v-if="user == ''" v-on:click="login()">Login</span>
    </div>

  </nav>
</template>

<script>
  import { EventBus } from '../../event-bus.js';

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

    methods: {
      login(){
        EventBus.$emit('prompt-login');
      },

      logout(){
        this.$store.dispatch('logoutUser');

        window.location = '/logout';
      }
    }
  }
</script>
