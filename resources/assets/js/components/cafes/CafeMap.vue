<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#cafe-map-container{
    position: absolute;
    top: 75px;
    left: 0px;
    right: 0px;
    bottom: 0px;

    div#cafe-map{
      position: absolute;
      top: 0px;
      left: 0px;
      right: 0px;
      bottom: 0px;
    }

    div.cafe-info-window{
      div.cafe-name{
        display: block;
        text-align: center;
        color: $dark-color;
        font-family: 'Josefin Sans', sans-serif;
      }

      div.cafe-address{
        display: block;
        text-align: center;
        margin-top: 5px;
        color: $grey;
        font-family: 'Lato', sans-serif;

        span.street{
          font-size: 14px;
          display: block;
        }

        span.city{
          font-size: 12px;
        }

        span.state{
          font-size: 12px;
        }

        span.zip{
          font-size: 12px;
          display: block;
        }

        a{
          color: $secondary-color;
          font-weight: bold;
        }
      }
    }
  }
</style>

<template>
  <div id="cafe-map-container">
    <div id="cafe-map">

    </div>
  </div>
</template>

<script>
  import { CafeTypeFilter } from '../../mixins/filters/CafeTypeFilter.js';
  import { CafeBrewMethodsFilter } from '../../mixins/filters/CafeBrewMethodsFilter.js';
  import { CafeTagsFilter } from '../../mixins/filters/CafeTagsFilter.js';
  import { CafeTextFilter } from '../../mixins/filters/CafeTextFilter.js';
  import { CafeUserLikeFilter } from '../../mixins/filters/CafeUserLikeFilter.js';

  /*
    Imports the Event Bus to pass updates.
  */
  import { EventBus } from '../../event-bus.js';

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
          return 5
        }
      }
    },

    data(){
      return {

      }
    },

    mixins: [
      CafeTypeFilter,
      CafeBrewMethodsFilter,
      CafeTagsFilter,
      CafeTextFilter,
      CafeUserLikeFilter
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
      this.$markers = [];

      this.$map = new google.maps.Map(document.getElementById('cafe-map'), {
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

      EventBus.$on('location-selected', function( cafe ){
        var latLng = new google.maps.LatLng( cafe.lat, cafe.lng );
        this.$map.setZoom( 17 );
        this.$map.panTo(latLng);
      }.bind(this));
    },

    methods: {
      /*
        Process filters on the map selected by the user.
      */
      processFilters( filters ){
        for( var i = 0; i < this.$markers.length; i++ ){
          if( filters.text == ''
            && filters.type == 'all'
            && filters.brewMethods.length == 0
            && !filters.liked  ){

                this.$markers[i].setMap( this.$map );
              }else{
                /*
                  Initialize flags for the filtering
                */
                var textPassed = false;
                var brewMethodsPassed = false;
                var typePassed = false;
                var likedPassed = false;


                /*
                  Check if the roaster passes
                */
                if( this.processCafeTypeFilter( this.$markers[i].cafe, filters.type) ){
                  typePassed = true;
                }

                /*
                  Check if text passes
                */
                if( filters.text != '' && this.processCafeTextFilter( this.$markers[i].cafe, filters.text ) ){
                  textPassed = true;
                }else if( filters.text == '' ){
                  textPassed = true;
                }

                /*
                  Check if brew methods passes
                */
                if( filters.brewMethods.length != 0 && this.processCafeBrewMethodsFilter( this.$markers[i].cafe, filters.brewMethods ) ){
                  brewMethodsPassed = true;
                }else if( filters.brewMethods.length == 0 ){
                  brewMethodsPassed = true;
                }

                /*
                  Check if liked passes
                */
                if( filters.liked && this.processCafeUserLikeFilter( this.$markers[i].cafe ) ){
                  likedPassed = true;
                }else if( !filters.liked ){
                  likedPassed = true;
                }

                /*
                  If everything passes, then we show the Cafe Marker
                */
                if( typePassed && textPassed && brewMethodsPassed && likedPassed ){
                  this.$markers[i].setMap( this.$map );
                }else{
                  this.$markers[i].setMap( null );
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
        for( var i = 0; i < this.$markers.length; i++ ){
          this.$markers[i].setMap( null );
        }
      },

      /*
        Builds all of the markers for the cafes
      */
      buildMarkers(){
        /*
          Initialize the markers to an empty array.
        */
        this.$markers = [];

        /*
          Iterate over all of the cafes
        */
        for( var i = 0; i < this.cafes.length; i++ ){

          /*
            Create the marker for each of the cafes and set the
            latitude and longitude to the latitude and longitude
            of the cafe. Also set the map to be the local map.
          */
          if( this.cafes[i].company.roaster == 1 ){
            var image = '/img/roaster-marker.svg';
          }else{
            var image = '/img/cafe-marker.svg';
          }


          if( this.cafes[i].latitude != null ){
            var marker = new google.maps.Marker({
              position: { lat: parseFloat( this.cafes[i].latitude ), lng: parseFloat( this.cafes[i].longitude ) },
              map: this.$map,
              icon: image,
              cafe: this.cafes[i]
            });

            let router = this.$router;

            marker.addListener('click', function() {
              router.push( { name: 'cafe', params: { id: this.cafe.id } } );
            });

            /*
              Push the new marker on to the array.
            */
            this.$markers.push( marker );
          }
        }
      }
    }
  }
</script>
