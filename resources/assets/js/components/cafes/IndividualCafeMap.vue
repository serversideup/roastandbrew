<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#individual-cafe-map{
    width: 700px;
    height: 500px;
    margin: auto;
    margin-bottom: 200px;
  }
</style>

<template>
  <div id="individual-cafe-map">

  </div>
</template>

<script>
  export default {
    /*
      Defines the computed properties on the component.
    */
    computed: {
      /*
        Gets the cafe load status from the Vuex state.
      */
      cafeLoadStatus(){
        return this.$store.getters.getCafeLoadStatus;
      },

      /*
        Gets the cafe from the Vuex state.
      */
      cafe(){
        return this.$store.getters.getCafe;
      }
    },

    /*
      Defines the variables we need to watch on the component.
    */
    watch: {
      /*
        The cafe load status. When the cafe load status equals 2
        we display the individual cafe map. We have to wait until the
        cafe is loaded so we get the lat and long for the cafe.
      */
      cafeLoadStatus(){
        if( this.cafeLoadStatus == 2 ){
          this.displayIndividualCafeMap();
        }
      }
    },

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Displays the individual cafe map.
      */
      displayIndividualCafeMap(){
        /*
          Builds the individual cafe map.
        */
        this.map = new google.maps.Map(document.getElementById('individual-cafe-map'), {
          center: {lat: parseFloat( this.cafe.latitude ), lng: parseFloat( this.cafe.longitude )},
          zoom: 13
        });

        /*
          Defines the image used for the marker.
        */
        var image = '/img/coffee-marker.png';

        /*
          Builds the marker for the cafe on the map.
        */
        var marker = new google.maps.Marker({
          position: { lat: parseFloat( this.cafe.latitude ), lng: parseFloat( this.cafe.longitude )},
          map: this.map,
          icon: image
        });
      }
    }
  }
</script>
