<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.cafe-card{
    border-radius: 5px;
    box-shadow: 0 2px 2px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.08);
    padding: 5px;
    margin-top: 20px;
    cursor: pointer;
    -webkit-transform: scaleX(1) scaleY(1);
    transform: scaleX(1) scaleY(1);
    transition: .2s;

    span.title{
      display: block;
      text-align: center;
      color: $dark-color;
      font-family: 'Josefin Sans', sans-serif;
    }

    span.address{
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
    }

    &:hover{
      -webkit-transform: scaleX(1.041) scaleY(1.041);
      transform: scaleX(1.041) scaleY(1.041);
      transition: .2s;
    }
  }
</style>

<template>
  <div class="large-3 medium-3 small-6 cell" v-show="show">
    <router-link :to="{ name: 'cafe', params: { id: cafe.id} }">
      <div class="cafe-card">
        <span class="title">{{ cafe.name }}</span>
        <span class="address">
          <span class="street">{{ cafe.address }}</span>
          <span class="city">{{ cafe.city }}</span> <span class="state">{{ cafe.state }}</span>
          <span class="zip">{{ cafe.zip }}</span>
        </span>
      </div>
    </router-link>
  </div>
</template>

<script>
  import { CafeIsRoasterFilter } from '../../mixins/filters/CafeIsRoasterFilter.js';
  import { CafeBrewMethodsFilter } from '../../mixins/filters/CafeBrewMethodsFilter.js';
  import { CafeTagsFilter } from '../../mixins/filters/CafeTagsFilter.js';
  import { CafeTextFilter } from '../../mixins/filters/CafeTextFilter.js';

  /*
    Imports the Event Bus to listen to filter updates
  */
  import { EventBus } from '../../event-bus.js';

  export default {
    props: ['cafe'],

    data(){
      return {
        show: true
      }
    },

    mixins: [
      CafeIsRoasterFilter,
      CafeBrewMethodsFilter,
      CafeTagsFilter,
      CafeTextFilter
    ],

    mounted(){
      EventBus.$on('filters-updated', function( filters ){
        this.processFilters( filters );
      }.bind(this));
    },

    methods: {
      processFilters( filters ){
        /*
          If no filters are selected, show the card
        */
        if( filters.text == ''
          && filters.tags.length == 0
          && filters.roaster == false
          && filters.brew_methods.length == 0 ){
            this.show = true;
        }else{
          /*
            Initialize flags for the filtering
          */
          var textPassed = false;
          var tagsPassed = false;
          var brewMethodsPassed = false;
          var roasterPassed = false;


          /*
            Check if the roaster passes
          */
          if( filters.roaster && this.processCafeIsRoasterFilter( this.cafe ) ){
            roasterPassed = true;
          }else if( !filters.roaster ){
            roasterPassed = true;
          }

          /*
            Check if text passes
          */
          if( filters.text != '' && this.processCafeTextFilter( this.cafe, filters.text ) ){
            textPassed = true;
          }else if( filters.text == '' ){
            textPassed = true;
          }

          /*
            Check if brew methods passes
          */
          if( filters.brew_methods.length != 0 && this.processCafeBrewMethodsFilter( this.cafe, filters.brew_methods ) ){
            brewMethodsPassed = true;
          }else if( filters.brew_methods.length == 0 ){
            brewMethodsPassed = true;
          }

          /*
            Check if tags passes
          */
          if( filters.tags.length != 0 && this.processCafeTagsFilter( this.cafe, filters.tags ) ){
            tagsPassed = true;
          }else if( filters.tags.length == 0 ){
            tagsPassed = true;
          }

          /*
            If everything passes, then we show the Cafe Card
          */
          if( roasterPassed && textPassed && brewMethodsPassed && tagsPassed ){
            this.show = true;
          }else{
            this.show = false;
          }
        }
      }
    }
  }
</script>
