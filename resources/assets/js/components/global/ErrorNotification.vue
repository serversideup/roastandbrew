<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.error-notification-container{
    position: fixed;
    z-index: 999999;
    left: 0;
    right: 0;
    top: 0;

    div.error-notification{
      background: #FFFFFF;
      box-shadow: 0 0 4px 0 rgba(0,0,0,0.12), 0 4px 4px 0 rgba(0,0,0,0.24);
      border-left: 5px solid #FF0000;
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
        height: 20px;
      }
    }
  }

</style>

<template>
  <transition name="slide-in-top">
    <div class="error-notification-container" v-show="show">
      <div class="error-notification">
        <img src="/img/error.svg"/> {{ errorMessage }}
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
        errorMessage: '',
        show: false
      }
    },

    /*
      When mounted, bind the show error event.
    */
    mounted(){
      EventBus.$on('show-error', function( data ){
        this.errorMessage = data.notification;

        this.show = true;

        /*
          Hide the error notification after 3 seconds.
        */
        setTimeout( function(){
          this.show = false;
        }.bind(this), 3000);

      }.bind(this));
    }
  }
</script>
