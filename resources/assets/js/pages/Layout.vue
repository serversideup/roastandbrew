<style>

</style>

<template>
  <div id="app-layout">
    <success-notification></success-notification>

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

  import Navigation from '../components/global/Navigation.vue';
  import LoginModal from '../components/global/LoginModal.vue';
  import Filters from '../components/global/Filters.vue';
  import PopOut from '../components/global/PopOut.vue';
  import SuccessNotification from '../components/global/SuccessNotification.vue';

  export default {
    components: {
      Navigation,
      LoginModal,
      Filters,
      PopOut,
      SuccessNotification
    },

    created(){
      this.$store.dispatch( 'loadCafes' );
      this.$store.dispatch( 'loadUser' );
      this.$store.dispatch( 'loadBrewMethods' );
    },

    computed: {
      addedCafe(){
        return this.$store.getters.getAddedCafe;
      },

      addCafeStatus(){
        return this.$store.getters.getCafeAddStatus;
      }
    },

    watch: {
      'addCafeStatus': function(){
        if( this.addCafeStatus == 2 ){
          EventBus.$emit('show-success', {
            notification: this.addedCafe.name+' has been added!'
          });
        }
      }
    }
  }
</script>
