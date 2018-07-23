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
    <div class="grid-x grid-padding-x cafe-grid-container" id="cafe-grid">
      <cafe-card v-for="cafe in cafes" :key="cafe.id" :cafe="cafe"></cafe-card>
      <div class="large-12 medium-12 small-12 cell">
        <span class="no-results" v-if="shownCount == 0">No Results</span>
      </div>
    </div>
  </div>
</template>

<script>
  import { CafeTypeFilter } from '../../mixins/filters/CafeTypeFilter.js';
  import { CafeBrewMethodsFilter } from '../../mixins/filters/CafeBrewMethodsFilter.js';
  import { CafeTagsFilter } from '../../mixins/filters/CafeTagsFilter.js';
  import { CafeTextFilter } from '../../mixins/filters/CafeTextFilter.js';
  import { CafeUserLikeFilter } from '../../mixins/filters/CafeUserLikeFilter.js';
  import { CafeHasMatchaFilter } from '../../mixins/filters/CafeHasMatchaFilter.js';
  import { CafeHasTeaFilter } from '../../mixins/filters/CafeHasTeaFilter.js';

  import CafeCard from '../../components/cafes/CafeCard.vue';

  /*
    Imports the Event Bus to pass updates.
  */
  import { EventBus } from '../../event-bus.js';

  export default {

    data(){
      return {
        shownCount: 1
      }
    },

    components: {
      CafeCard
    },

    mixins: [
      CafeTypeFilter,
      CafeBrewMethodsFilter,
      CafeTagsFilter,
      CafeTextFilter,
      CafeUserLikeFilter,
      CafeHasMatchaFilter,
      CafeHasTeaFilter
    ],

    mounted(){
      EventBus.$on('filters-updated', function( filters ){
        this.computeShown();
      }.bind(this));
    },

    computed: {
      /*
        Gets the cafes
      */
      cafes(){
        return this.$store.getters.getCafes;
      }
    },

    methods: {
      computeShown(){
        this.shownCount = $('.cafe-card-container').filter(function() {
              return $(this).css('display') !== 'none';
          }).length;
      }
    }

  }
</script>
