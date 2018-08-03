<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.success-notification-container{
    position: fixed;
    z-index: 999999;
    left: 0;
    right: 0;
    top: 0;

    div.success-notification{
      background: #FFFFFF;
      box-shadow: 0 0 4px 0 rgba(0,0,0,0.12), 0 4px 4px 0 rgba(0,0,0,0.24);
      border-left: 5px solid #00C853;
      height: 50px;
      line-height: 50px;
      margin: auto;
      width: 400px;
      margin-top: 150px;
      color: #242E38;
      font-family: "Lato", sans-serif;
      font-size: 16px;

      img{
        margin-right: 20px;
        margin-left: 20px;
      }
    }
  }

</style>

<template>
  <transition name="slide-in-top">
    <div class="success-notification-container" v-show="show">
      <div class="success-notification">
        <img src="/img/success.svg"/> {{ successMessage }}
      </div>
    </div>
  </transition>
</template>

<script>
  /*
    Imports the Event Bus to pass events on tag updates
  */
  import { EventBus } from '../../event-bus.js';

  export default {
    /*
      Defines the data used by the component.
    */
    data(){
      return {
        successMessage: '',
        show: false
      }
    },

    /*
      Sets up the component when mounted.
    */
    mounted(){
      /*
        On show success, show the notification.
      */
      EventBus.$on('show-success', function( data ){
        this.successMessage = data.notification;

        this.show = true;

        /*
          After 3 seconds hide the notification.
        */
        setTimeout( function(){
          this.show = false;
        }.bind(this), 3000);

      }.bind(this));
    }
  }
</script>
