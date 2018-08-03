<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#app-layout{
    div.show-filters{
      height: 90px;
      width: 23px;
      position: absolute;
      left: 0px;
      background-color: white;
      border-top-right-radius: 3px;
      border-bottom-right-radius: 3px;
      line-height: 90px;
      top: 50%;
      cursor: pointer;
      margin-top: -45px;
      z-index: 9;
      text-align: center;
    }
  }
</style>

<template>
  <div id="app-layout">
    <div class="show-filters" v-show="!showFilters" v-on:click="toggleShowFilters()">
      <img src="/img/grey-right.svg"/>
    </div>

    <success-notification></success-notification>
    <error-notification></error-notification>

    <navigation></navigation>

    <router-view></router-view>

    <login-modal></login-modal>

    <filters></filters>

    <pop-out></pop-out>
  </div>
</template>

<script>
  /*
    Imports the Event Bus to pass events on tag updates
  */
  import { EventBus } from '../event-bus.js';

  /*
    Define the components used in the Layout
  */
  import Navigation from '../components/global/Navigation.vue';
  import LoginModal from '../components/global/LoginModal.vue';
  import Filters from '../components/global/Filters.vue';
  import PopOut from '../components/global/PopOut.vue';
  import SuccessNotification from '../components/global/SuccessNotification.vue';
  import ErrorNotification from '../components/global/ErrorNotification.vue';

  export default {
    /*
      Register the components with the layout.
    */
    components: {
      Navigation,
      LoginModal,
      Filters,
      PopOut,
      SuccessNotification,
      ErrorNotification
    },

    /*
      When created, set up the layout.
    */
    created(){
      /*
        Load the necessary data in the layout.
      */
      this.$store.dispatch( 'loadCafes' );
      this.$store.dispatch( 'loadUser' );
      this.$store.dispatch( 'loadBrewMethods' );

      /*
        If the admin module is set, unregister it. We don't need
        it here.
      */
      if( this.$store._modules.get(['admin'] ) ){
        this.$store.unregisterModule( 'admin', {} );
      }
    },

    /*
      Define the computed properties in the layout.
    */
    computed: {
      /*
        Determine if we should show the filters or not.
      */
      showFilters(){
        return this.$store.getters.getShowFilters;
      },

      /*
        Get the cafe that was added.
      */
      addedCafe(){
        return this.$store.getters.getAddedCafe;
      },

      /*
        Get the cafe added status.
      */
      addCafeStatus(){
        return this.$store.getters.getCafeAddStatus;
      }
    },

    /*
      Define what to watch in the module.
    */
    watch: {
      /*
        When the cafe added status changes, emit the success
        if the cafe was added successfully!
      */
      'addCafeStatus': function(){
        if( this.addCafeStatus == 2 ){
          EventBus.$emit('show-success', {
            notification: this.addedCafe.name+' has been added!'
          });
        }
      }
    },

    /*
      Defines the methods used by the layout.
    */
    methods: {
      /*
        Toggle the showing and hiding of the filters.
      */
      toggleShowFilters(){
        this.$store.dispatch( 'toggleShowFilters', { showFilters : !this.showFilters } );
      }
    }
  }
</script>
