<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#cafe-list-container{
    position: absolute;
    top: 75px;
    left: 0px;
    right: 0px;
    bottom: 0px;
    background-color: white;
    overflow-y: scroll;

    div.cafe-grid-container{
      max-width: 900px;
      margin: auto;
    }
  }

  /* Small only */
  @media screen and (max-width: 39.9375em) {
    div.cafe-grid-container{
      height: inherit;
    }
  }
</style>

<template>
  <div id="cafe-list-container">
    <div class="grid-x grid-padding-x cafe-grid-container">
      <list-filters></list-filters>
    </div>

    <div class="grid-x grid-padding-x cafe-grid-container" id="cafe-grid">
      <cafe-card v-for="cafe in cafes" :key="cafe.id" :cafe="cafe"></cafe-card>
      <div class="large-12 medium-12 small-12 cell">
        <span class="no-results" v-if="shownCount == 0">No Results</span>
      </div>
    </div>
  </div>
</template>

<script>
  /*
    Imports the cafe card component.
  */
  import CafeCard from '../../components/cafes/CafeCard.vue';
  import ListFilters from '../../components/cafes/ListFilters.vue';

  export default {

    /*
      Defines the data used by the component.
    */
    data(){
      return {
        shownCount: 1
      }
    },

    /*
      Regisers the components with the component.
    */
    components: {
      CafeCard,
      ListFilters
    },

    /*
      Defines the computed properties on the component.
    */
    computed: {
      /*
        Gets the cafes
      */
      cafes(){
        return this.$store.getters.getCafes;
      },

      /*
        Gets the current views the cafes are in.
      */
      cafesView(){
        return this.$store.getters.getCafesView;
      }
    }
  }
</script>
