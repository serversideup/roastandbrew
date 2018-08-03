<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.pop-out{
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    top: 0;
    background-color: rgba( 55, 44, 12, .29 );
    z-index: 9999;

    div.pop-out-side-bar{
      position: fixed;
      right: 0;
      bottom: 0;
      top: 0;
      width: 250px;
      background-color: white;
      box-shadow: -2px 0 4px 0 rgba(3,27,78,0.10);
      padding: 30px;

      div.side-bar-link{
        border-bottom: 1px solid #BABABA;
        font-size: 16px;
        font-weight: bold;
        font-family: "Lato", sans-serif;
        text-transform: uppercase;
        padding-top: 25px;
        padding-bottom: 25px;

        a{
          color: black;
        }
      }

      img.close-menu-icon{
        float: right;
        cursor: pointer;
      }

      div.ssu-container{
        position: absolute;
        bottom: 30px;

        span.ssu-built-on{
          color: black;
          font-size: 14px;
          font-family: "Lato", sans-serif;
          display: block;
          margin-bottom: 10px;
        }

        img{
          margin: auto;
          max-width: 190px;
        }
      }
    }
  }
</style>

<template>
  <div class="pop-out" v-show="showPopOut" v-on:click="hideNav()">
    <transition name="slide-in-right">
      <div class="pop-out-side-bar" v-show="showRightNav" v-on:click.stop>
        <img src="/img/close-menu.svg" class="close-menu-icon" v-on:click="hideNav()"/>
        <div class="side-bar-link">
          <router-link :to="{ name: 'cafes' }" v-on:click.native="hideNav()">
            Cafes
          </router-link>
        </div>
        <div class="side-bar-link" v-if="user != '' && userLoadStatus == 2">
          <router-link :to="{ name: 'newcafe' }" v-on:click.native="hideNav()">
            Add Cafe
          </router-link>
        </div>
        <div class="side-bar-link" v-if="user != '' && userLoadStatus == 2">
          <router-link :to="{ name: 'profile' }" v-on:click.native="hideNav()">
            My Profile
          </router-link>
        </div>
        <div class="side-bar-link" v-if="user != '' && userLoadStatus == 2 && user.permission >= 1">
          <router-link :to="{ name: 'admin'}" v-on:click.native="hideNav()">
            Admin
          </router-link>
        </div>
        <div class="side-bar-link">
          <a v-if="user != '' && userLoadStatus == 2" v-show="userLoadStatus == 2" v-on:click="logout()">Sign Out</a>
          <a v-if="user == ''" v-on:click="login()">Sign In</a>
        </div>
        <div class="side-bar-link">
          <a href="https://github.com/serversideup/roastandbrew/issues/new/choose" target="_blank">
            Report a Bug
          </a>
        </div>
        <div class="side-bar-link">
          <a href="https://serversideup.net/series/api-driven-development-laravel-vuejs/" target="_blank">
            About This Project
          </a>
        </div>
        <div class="side-bar-link">
          <a href="https://github.com/serversideup/roastandbrew" target="_blank">
            View on Github
          </a>
        </div>

        <div class="ssu-container">
          <span class="ssu-built-on">Learn how this app was built on</span>
          <a href="https://serversideup.net/courses/api-driven-development-laravel-vuejs/" target="_blank">
            <img src="/img/ssu-logo.png"/>
          </a>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
  /*
    Imports the event bus.
  */
  import { EventBus } from '../../event-bus.js';

  export default {
    /*
      Defines the computed properties.
    */
    computed: {
      /*
        Gets whether or not the popout should be shown or not.
      */
      showPopOut(){
        return this.$store.getters.getShowPopOut;
      },

      /*
        Determines if we should show the popout.
      */
      showRightNav(){
        return this.showPopOut;
      },

      /*
        Retrieves the User from Vuex
      */
      user(){
        return this.$store.getters.getUser;
      },

      /*
        Retrieves the User Load Status from Vuex
      */
      userLoadStatus(){
        return this.$store.getters.getUserLoadStatus();
      }
    },

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Toggles the hiding if the navigation.
      */
      hideNav(){
        this.$store.dispatch( 'toggleShowPopOut', { showPopOut: false } );
      },

      /*
        hides the popout and shows the log in form.
      */
      login(){
        this.$store.dispatch( 'toggleShowPopOut', { showPopOut: false } );
        EventBus.$emit('prompt-login');
      },

      /*
        Logs the user out.
      */
      logout(){
        this.$store.dispatch( 'logoutUser' );
        window.location = '/logout';
      }
    }
  }
</script>
