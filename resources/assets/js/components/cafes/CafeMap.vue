<style lang="scss">
  div#cafe-map-container{
    position: absolute;
    top: 50px;
    left: 0px;
    right: 0px;
    bottom: 50px;

    div#cafe-map{
      position: absolute;
      top: 0px;
      left: 0px;
      right: 0px;
      bottom: 0px;
    }
  }
</style>

<template>
  <div id="cafe-map-container">
    <div id="cafe-map">

    </div>
    <cafe-map-filter></cafe-map-filter>
  </div>
</template>

<script>
  import { CafeIsRoasterFilter } from '../../mixins/filters/CafeIsRoasterFilter.js';
  import { CafeBrewMethodsFilter } from '../../mixins/filters/CafeBrewMethodsFilter.js';
  import { CafeTagsFilter } from '../../mixins/filters/CafeTagsFilter.js';
  import { CafeTextFilter } from '../../mixins/filters/CafeTextFilter.js';

  /*
    Imports the Event Bus to pass updates.
  */
  import { EventBus } from '../../event-bus.js';

  import CafeMapFilter from './CafeMapFilter.vue';

  export default {
    props: {
      'latitude': {
        type: Number,
        default: function(){
          return 39.50
        }
      },
      'longitude': {
        type: Number,
        default: function(){
          return -98.35
        }
      },
      'zoom': {
        type: Number,
        default: function(){
          return 4
        }
      }
    },

    components: {
      CafeMapFilter
    },

    data(){
      return {
        markers: [],
        infoWindows: []
      }
    },

    mixins: [
      CafeIsRoasterFilter,
      CafeBrewMethodsFilter,
      CafeTagsFilter,
      CafeTextFilter
    ],

    computed: {
      /*
        Gets the cafes
      */
      cafes(){
        return this.$store.getters.getCafes;
      }
    },

    watch: {
      /*
        Watches the cafes. When they are updated, clear the markers
        and re build them.
      */
      cafes(){
        this.clearMarkers();
        this.buildMarkers();
      }
    },

    mounted(){
      /*
        We don't want the map to be reactive, so we initialize it locally,
        but don't store it in our data array.
      */
      this.map = new google.maps.Map(document.getElementById('cafe-map'), {
        center: {lat: this.latitude, lng: this.longitude},
        zoom: this.zoom
      });

      /*
        Clear and re-build the markers
      */
      this.clearMarkers();
      this.buildMarkers();

      /*
        Listen to the filters-updated event to filter the map markers
      */
      EventBus.$on('filters-updated', function( filters ){
        this.processFilters( filters );
      }.bind(this));
    },

    methods: {
      /*
        Process filters on the map selected by the user.
      */
      processFilters( filters ){
        for( var i = 0; i < this.markers.length; i++ ){
          if( filters.text == ''
              && filters.roaster == false
              && filters.brew_methods.length == 0 ){
                this.markers[i].setMap( this.map );
              }else{
                /*
                  Initialize flags for the filtering
                */
                var textPassed = false;
                var brewMethodsPassed = false;
                var roasterPassed = false;


                /*
                  Check if the roaster passes
                */
                if( filters.roaster && this.processCafeIsRoasterFilter( this.markers[i].cafe ) ){
                  roasterPassed = true;
                }else if( !filters.roaster ){
                  roasterPassed = true;
                }

                /*
                  Check if text passes
                */
                if( filters.text != '' && this.processCafeTextFilter( this.markers[i].cafe, filters.text ) ){
                  textPassed = true;
                }else if( filters.text == '' ){
                  textPassed = true;
                }

                /*
                  Check if brew methods passes
                */
                if( filters.brew_methods.length != 0 && this.processCafeBrewMethodsFilter( this.markers[i].cafe, filters.brew_methods ) ){
                  brewMethodsPassed = true;
                }else if( filters.brew_methods.length == 0 ){
                  brewMethodsPassed = true;
                }

                /*
                  If everything passes, then we show the Cafe Marker
                */
                if( roasterPassed && textPassed && brewMethodsPassed){
                  this.markers[i].setMap( this.map );
                }else{
                  this.markers[i].setMap( null );
                }
              }
        }
      },

      /*
        Clears the markers from the map.
      */
      clearMarkers(){
        /*
          Iterate over all of the markers and set the map
          to null so they disappear.
        */
        for( var i = 0; i < this.markers.length; i++ ){
          this.markers[i].setMap( null );
        }
      },

      /*
        Builds all of the markers for the cafes
      */
      buildMarkers(){
        /*
          Initialize the markers to an empty array.
        */
        this.markers = [];

        /*
          Iterate over all of the cafes
        */
        for( var i = 0; i < this.cafes.length; i++ ){

          /*
            Create the marker for each of the cafes and set the
            latitude and longitude to the latitude and longitude
            of the cafe. Also set the map to be the local map.
          */
          var image = '/img/coffee-marker.png';

          var marker = new google.maps.Marker({
            position: { lat: parseFloat( this.cafes[i].latitude ), lng: parseFloat( this.cafes[i].longitude ) },
            map: this.map,
            icon: image,
            cafe: this.cafes[i]
          });

          /*
            Create the info window and add it to the local
            array.
          */
          let infoWindow = new google.maps.InfoWindow({
            content: this.cafes[i].name
          });

          this.infoWindows.push( infoWindow );

          /*
            Add the event listener to open the info window for the marker.
          */
          marker.addListener('click', function() {
            infoWindow.open(this.map, this);
          });

          /*
            Push the new marker on to the array.
          */
          this.markers.push( marker );
        }
      }
    }
  }
</script>
