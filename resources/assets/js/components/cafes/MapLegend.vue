<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#map-legend{
    position: absolute;
    z-index: 9;
    left: 15px;
    bottom: 90px;
    -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
    border-radius: 5px;
    background-color: white;
    padding: 10px;
    max-width: 200px;
    font-family: "Lato", sans-serif;

    span.legend-title{
      display: block;
      text-align: center;
      font-family: "Lato", sans-serif;
      margin-bottom: 10px;
      font-size: 18px;
      font-weight: bold;
    }

    div.legend-row{
      margin-bottom: 5px;

      img{
        margin-right: 10px;
      }
    }
  }
</style>

<template>
  <div id="map-legend" v-show="!showFilters">
    <div class="grid-x">
      <div class="large-12 medium-12 small-12 cell">
        <span class="legend-title">Legend</span>
      </div>
      <div class="large-12 medium-12 small-12 cell legend-row">
        <img src="/img/roaster-marker.svg"/> Roasts Own Coffee
      </div>
      <div class="large-12 medium-12 small-12 cell legend-row">
        <img src="/img/cafe-marker.svg"/> Cafe
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data(){
      return {

      }
    },

    /*
      Defines the computed properties on the component.
    */
    computed: {
      /*
        Gets the show filters data from the state.
      */
      showFilters(){
        return this.$store.getters.getShowFilters;
      }
    }
  }
</script>
