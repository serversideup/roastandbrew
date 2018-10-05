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
  /*
    Imports the mixins used by the component.
  */
  import { CafeTypeFilter } from '../../mixins/filters/CafeTypeFilter.js';
  import { CafeBrewMethodsFilter } from '../../mixins/filters/CafeBrewMethodsFilter.js';
  import { CafeTagsFilter } from '../../mixins/filters/CafeTagsFilter.js';
  import { CafeTextFilter } from '../../mixins/filters/CafeTextFilter.js';
  import { CafeUserLikeFilter } from '../../mixins/filters/CafeUserLikeFilter.js';
  import { CafeHasMatchaFilter } from '../../mixins/filters/CafeHasMatchaFilter.js';
  import { CafeHasTeaFilter } from '../../mixins/filters/CafeHasTeaFilter.js';
  import { CafeSubscriptionFilter } from '../../mixins/filters/CafeSubscriptionFilter.js';
  import { CafeInCityFilter } from '../../mixins/filters/CafeInCityFilter.js';

  /*
    Imports the Event Bus to pass updates.
  */
  import { EventBus } from '../../event-bus.js';

  export default {
    /*
      Defines the properties used by the component.
    */
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

    /*
      Defines the mixins used by the component.
    */
    mixins: [
      CafeTypeFilter,
      CafeBrewMethodsFilter,
      CafeTagsFilter,
      CafeTextFilter,
      CafeUserLikeFilter,
      CafeHasMatchaFilter,
      CafeHasTeaFilter,
      CafeSubscriptionFilter,
      CafeInCityFilter
    ],

    /*
      Defines the computed properties used by the component.
    */
    computed: {
      /*
        Gets the cafes
      */
      cafes(){
        return this.$store.getters.getCafes;
      },

      /*
        Gets the city from the Vuex store.
      */
      city(){
        return this.$store.getters.getCity;
      },

      /*
        Gets the city filter from the Vuex store.
      */
      cityFilter(){
        return this.$store.getters.getCityFilter;
      },

      /*
        Grabs the text search filter.
      */
      textSearch(){
        return this.$store.getters.getTextSearch;
      },

      /*
        Grabs the active location filter
      */
      activeLocationFilter(){
        return this.$store.getters.getActiveLocationFilter;
      },

      /*
        Grabs the only liked filter.
      */
      onlyLiked(){
        return this.$store.getters.getOnlyLiked;
      },

      /*
        Grabs the brew methods filter.
      */
      brewMethodsFilter(){
        return this.$store.getters.getBrewMethodsFilter;
      },

      /*
        Grabs the has matcha filter
      */
      hasMatcha(){
        return this.$store.getters.getHasMatcha;
      },

      /*
        Grabs the has tea filter
      */
      hasTea(){
        return this.$store.getters.getHasTea;
      },

      /*
        Grabs the has subscription filter
      */
      hasSubscription(){
        return this.$store.getters.getHasSubscription;
      },

      /*
        Grabs the previous lat
      */
      previousLat(){
        return this.$store.getters.getLat;
      },

      /*
        Grabs the previous lng
      */
      previousLng(){
        return this.$store.getters.getLng;
      },

      /*
        Grabs the previous zoom
      */
      previousZoom(){
        return this.$store.getters.getZoomLevel;
      }
    },

    /*
      Defines the watched variables on the component.
    */
    watch: {
      /*
        When the route changes from an individual cafe to all of the cafes,
        check if a previous lat and lng are set and go back to where the user
        was located.
      */
      '$route' (to, from) {
        if( to.name == 'cafes' && from.name == 'cafe' ){
          if( this.previousLat != 0.0 && this.previousLng != 0.0 && this.previousZoom != '' ){
            var latLng = new google.maps.LatLng( this.previousLat, this.previousLng );
            this.$map.setZoom( this.previousZoom );
            this.$map.panTo( latLng );
          }
        }
      },

      /*
        Watches the cafes. When they are updated, clear the markers
        and re build them. We also process existing filters.
      */
      cafes(){
        this.clearMarkers();
        this.buildMarkers();
        this.processFilters();
      },

      /*
        Watch the city filter
      */
      cityFilter(){
        this.processFilters();
      },

      /*
        When the text input changes, process the filters.
      */
      textSearch(){
        this.processFilters();
      },

      /*
        When the active location filter changes, process the
        filters.
      */
      activeLocationFilter(){
        this.processFilters();
      },

      /*
        When the only liked changes, process the filters.
      */
      onlyLiked(){
        this.processFilters();
      },

      /*
        When the brew methods change, process the filters.
      */
      brewMethodsFilter(){
        this.processFilters();
      },

      /*
        When the has matcha changes, process the filters.
      */
      hasMatcha(){
        this.processFilters();
      },

      /*
        When the has tea changes, process the filters.
      */
      hasTea(){
        this.processFilters();
      },

      /*
        When the has subscription changes, process the filters.
      */
      hasSubscription(){
        this.processFilters();
      }
    },

    /*
      Handles the mounted lifecycle hook.
    */
    mounted(){
      /*
        Initializes the local markers array. This is not a reactive variable.
      */
      this.$markers = [];

      /*
        Initializes the local map variable. This is not reactive variable
      */
      this.$map = new google.maps.Map(document.getElementById('cafe-map'), {
        center: {lat: this.latitude, lng: this.longitude},
        zoom: this.zoom,
        fullscreenControl: false,
        mapTypeControl: false
      });

      /*
        Clear and re-build the markers
      */
      this.clearMarkers();
      this.buildMarkers();

      /*
        Listen to the location-selected event to zoom into the appropriate
        cafe.
      */
      EventBus.$on('location-selected', function( cafe ){
        var latLng = new google.maps.LatLng( cafe.lat, cafe.lng );
        this.$map.setZoom( 17 );
        this.$map.panTo(latLng);
      }.bind(this));

      /*
        Listen to the location-selected event to zoom into the appropriate
        cafe.
      */
      EventBus.$on('city-selected', function( city ){
        var latLng = new google.maps.LatLng( city.lat, city.lng );
        this.$map.setZoom( 11 );
        this.$map.panTo(latLng);
      }.bind(this));
    },

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Process filters on the map selected by the user.
      */
      processFilters(){
        for( var i = 0; i < this.$markers.length; i++ ){
          if( this.textSearch == ''
            && this.activeLocationFilter == 'all'
            && this.brewMethodsFilter.length == 0
            && !this.onlyLiked
            && !this.hasMatcha
            && !this.hasTea
            && !this.hasSubscription
            && this.cityFilter == '' ){
                this.$markers[i].setMap( this.$map );
              }else{
                /*
                  Initialize flags for the filtering
                */
                var textPassed = false;
                var brewMethodsPassed = false;
                var typePassed = false;
                var likedPassed = false;
                var matchaPassed = false;
                var teaPassed = false;
                var subscriptionPassed = false;
                var cityPassed = false;


                /*
                  Check if the roaster passes
                */
                if( this.processCafeTypeFilter( this.$markers[i].cafe, this.activeLocationFilter) ){
                  typePassed = true;
                }

                /*
                  Check if text passes
                */
                if( this.textSearch != '' && this.processCafeTextFilter( this.$markers[i].cafe, this.textSearch ) ){
                  textPassed = true;
                }else if( this.textSearch == '' ){
                  textPassed = true;
                }

                /*
                  Check if brew methods passes
                */
                if( this.brewMethodsFilter.length != 0 && this.processCafeBrewMethodsFilter( this.$markers[i].cafe, this.brewMethodsFilter ) ){
                  brewMethodsPassed = true;
                }else if( this.brewMethodsFilter.length == 0 ){
                  brewMethodsPassed = true;
                }

                /*
                  Check if liked passes
                */
                if( this.onlyLiked && this.processCafeUserLikeFilter( this.$markers[i].cafe ) ){
                  likedPassed = true;
                }else if( !this.onlyLiked ){
                  likedPassed = true;
                }

                /*
                  Checks if the cafe passes matcha filter
                */
                if( this.hasMatcha && this.processCafeHasMatchaFilter( this.$markers[i].cafe ) ){
                  matchaPassed = true;
                }else if( !this.hasMatcha ){
                  matchaPassed = true;
                }

                /*
                  Checks if the cafe passes the tea filter
                */
                if( this.hasTea && this.processCafeHasTeaFilter( this.$markers[i].cafe ) ){
                  teaPassed = true;
                }else if( !this.hasTea ){
                  teaPassed = true;
                }

                /*
                  Checks to see if the subscription filter works.
                */
                if( this.hasSubscription && this.processCafeSubscriptionFilter( this.$markers[i].cafe ) ){
                  subscriptionPassed = true;
                }else if( !this.hasSubscription ){
                  subscriptionPassed = true;
                }

                /*
                  Checks to see if the city passed or not.
                */
                if( this.cityFilter != '' && this.processCafeInCityFilter( this.$markers[i].cafe, this.cityFilter ) ){
                  cityPassed = true;
                }else if( this.cityFilter == '' ){
                  cityPassed = true;
                }

                /*
                  If everything passes, then we show the Cafe Marker
                */
                if( typePassed && textPassed && brewMethodsPassed && likedPassed && matchaPassed && teaPassed && subscriptionPassed && cityPassed ){
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


          /*
            If the cafe has a lat and lng, create a marker object and
            show it on the map.
          */
          if( this.cafes[i].latitude != null ){
            /*
              Create a new marker object.
            */
            var marker = new google.maps.Marker({
              position: { lat: parseFloat( this.cafes[i].latitude ), lng: parseFloat( this.cafes[i].longitude ) },
              map: this.$map,
              icon: image,
              cafe: this.cafes[i]
            });

            /*
              Localize the global router variable so when clicked, we go
              to the cafe and the store so we can dispatch the current locations.
            */
            let router = this.$router;
            let store = this.$store;

            marker.addListener('click', function() {
              let center = this.map.getCenter();

              store.dispatch( 'applyZoomLevel', this.map.getZoom() );
              store.dispatch( 'applyLat', center.lat() );
              store.dispatch( 'applyLng', center.lng() );

              router.push( { name: 'cafe', params: { slug: this.cafe.slug } } );
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
