<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#toggle-cafes-view{
    position: absolute;
    z-index: 9;
    right: 15px;
    top: 90px;
    -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
    border-radius: 5px;

    span.toggle-button{
      cursor: pointer;
      display: inline-block;
      padding: 5px 20px;
      background-color: white;
      font-family: "Lato", sans-serif;
      text-align: center;

      &.map-view{
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;

        &.active{
          color: white;
          background-color: $secondary-color;
        }
      }

      &.list-view{
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;

        &.active{
          color: white;
          background-color: $secondary-color;
        }
      }
    }
  }
</style>

<template>
  <div id="toggle-cafes-view" v-show="$route.name == 'cafes' || $route.name == 'city'">
    <span class="map-view toggle-button" v-bind:class="{ 'active': cafesView == 'map' }" v-on:click="displayView('map')">Map</span><span class="list-view toggle-button" v-bind:class="{ 'active': cafesView == 'list' }" v-on:click="displayView('list')">List</span>
  </div>
</template>

<script>
  export default {
    /*
      Defines the computed properties on the component.
    */
    computed: {
      /*
        Gets the current views the cafes are in.
      */
      cafesView(){
        return this.$store.getters.getCafesView;
      }
    },

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Changes the view of the cafes
      */
      displayView( type ){
        this.$store.dispatch( 'changeCafesView', type );
      }
    }
  }
</script>
