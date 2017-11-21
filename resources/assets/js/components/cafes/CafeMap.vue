<style lang="scss">
  div#cafe-map{
    position: absolute;
    top: 50px;
    left: 0px;
    right: 0px;
    bottom: 50px;
  }
</style>

<template>
  <div id="cafe-map">

  </div>
</template>

<script>
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

    data(){
      return {
        markers: [],
        infoWindows: []
      }
    },

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
    },

    methods: {
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
            icon: image
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
