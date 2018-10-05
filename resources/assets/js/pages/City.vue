<style>

</style>

<template>
  <div id="city">

  </div>
</template>

<script>
  /*
    Imports the event bus
  */
  import { EventBus } from '../event-bus.js';

  export default {
    /*
      On the created lifecycle hook, load the individual city.
    */
    created(){
      this.$store.dispatch( 'loadCity', {
        slug: this.$route.params.slug
      });
    },

    /*
      Defines the computed properties on the component.
    */
    computed: {
      /*
        Gets the city load status from the Vuex data store.
      */
      cityLoadStatus(){
        return this.$store.getters.getCityLoadStatus;
      },

      /*
        Gets the city from the Vuex data store.
      */
      city(){
        return this.$store.getters.getCity;
      }
    },

    /*
      Defines what to watch on the component.
    */
    watch: {
      /*
        When the city changes, load the city.
      */
      '$route.params.slug': function(){
        if( this.$route.name == 'city' ){
          this.$store.dispatch( 'loadCity', {
            slug: this.$route.params.slug
          });
        }
      },

      /*
        Watches the city load status.
      */
      'cityLoadStatus': function(){
        /*
          On success, go to the city.
        */
        if( this.cityLoadStatus == 2 ){
          EventBus.$emit('city-selected', { lat: parseFloat( this.city.latitude ), lng: parseFloat( this.city.longitude ) });
        }

        /*
          If the city fails to load, redirect back to the cafes screen.
        */
        if( this.cityLoadStatus == 3 ){
          EventBus.$emit('show-error', { notification: 'City Not Found!'} );
          this.$router.push({ name: 'cafes' });
        }
      }
    }
  }
</script>
