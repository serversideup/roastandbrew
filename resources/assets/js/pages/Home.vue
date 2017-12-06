<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#home{
    a.add-cafe-button{
      float: right;
      display: block;
      margin-top: 10px;
      margin-bottom: 10px;
      background-color: $dark-color;
      color: white;
      padding-top: 5px;
      padding-bottom: 5px;
      padding-left: 10px;
      padding-right: 10px;
    }

    a.add-cafe-text{
      float: right;
      display: inline-block;
      padding-top: 10px;
      padding-bottom: 10px;
      color: $dark-color;
      font-family: 'Lato', sans-serif;
    }
  }
</style>

<template>
  <div id="home" class="page">

    <div class="grid-container">
      <div class="grid-x">
        <div class="large-12 medium-12 small-12 columns">
          <router-link :to="{ name: 'newcafe' }" v-if="user != '' && userLoadStatus == 2"  class="add-cafe-button">+ Add Cafe</router-link>
          <a class="add-cafe-text" v-if="user == '' && userLoadStatus == 2" v-on:click="login()">Want to add a cafe? Create a profile and add your favorite cafe!</a>
        </div>
      </div>
    </div>

    <cafe-filter></cafe-filter>

    <div class="grid-container">
      <div class="grid-x grid-padding-x">
        <loader v-show="cafesLoadStatus == 1" :width="100" :height="100"></loader>
        <cafe-card v-for="cafe in cafes" :key="cafe.id" :cafe="cafe"></cafe-card>
      </div>
    </div>

  </div>
</template>

<script>
  import { EventBus } from '../event-bus.js';

  import CafeCard from '../components/cafes/CafeCard.vue';
  import Loader from '../components/global/Loader.vue';
  import CafeFilter from '../components/cafes/CafeFilter.vue';

  export default {
    components: {
      CafeCard,
      Loader,
      CafeFilter
    },

    /*
      Defines the computed properties on the component.
    */
    computed: {
      /*
        Gets the cafes load status
      */
      cafesLoadStatus(){
        return this.$store.getters.getCafesLoadStatus;
      },

      /*
        Gets the cafes
      */
      cafes(){
        return this.$store.getters.getCafes;
      },

      /*
        Gets the authenticated user.
      */
      user(){
        return this.$store.getters.getUser;
      },

      /*
        Gets the user's load status.
      */
      userLoadStatus(){
        return this.$store.getters.getUserLoadStatus();
      }
    },

    methods: {
      login(){
        EventBus.$emit('prompt-login');
      }
    }
  }
</script>
