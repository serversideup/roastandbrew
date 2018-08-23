<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.cafe-card{
    border-radius: 5px;
    box-shadow: 0 2px 2px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.08);
    padding: 15px 5px;
    margin-top: 20px;
    cursor: pointer;
    -webkit-transform: scaleX(1) scaleY(1);
    transform: scaleX(1) scaleY(1);
    transition: .2s;

    span.title{
      display: block;
      text-align: center;
      color: black;
      font-size: 18px;
      font-weight: bold;
      font-family: 'Lato', sans-serif;
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
        font-size: 14px;
      }

      span.state{
        font-size: 14px;
      }

      span.zip{
        font-size: 14px;
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
  <div class="large-3 medium-4 small-6 cell cafe-card-container" v-show="show">
    <router-link :to="{ name: 'cafe', params: { slug: cafe.slug} }" v-on:click.native="panToLocation( cafe )">
      <div class="cafe-card">
        <span class="title">{{ cafe.company.name }}</span>
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
  /*
    Imports the mixins used by the component.
  */
  import { CafeTypeFilter } from '../../mixins/filters/CafeTypeFilter.js';
  import { CafeBrewMethodsFilter } from '../../mixins/filters/CafeBrewMethodsFilter.js';
  import { CafeTextFilter } from '../../mixins/filters/CafeTextFilter.js';
  import { CafeUserLikeFilter } from '../../mixins/filters/CafeUserLikeFilter.js';
  import { CafeHasMatchaFilter } from '../../mixins/filters/CafeHasMatchaFilter.js';
  import { CafeHasTeaFilter } from '../../mixins/filters/CafeHasTeaFilter.js';
  import { CafeSubscriptionFilter } from '../../mixins/filters/CafeSubscriptionFilter.js';

  /*
    Imports the Event Bus to listen to filter updates
  */
  import { EventBus } from '../../event-bus.js';

  export default {
    /*
      The component accepts one cafe as a property
    */
    props: ['cafe'],

    /*
      Define the data used by the component.
    */
    data(){
      return {
        show: true
      }
    },

    /*
      Define the mixins used by the component.
    */
    mixins: [
      CafeTypeFilter,
      CafeBrewMethodsFilter,
      CafeTextFilter,
      CafeUserLikeFilter,
      CafeHasMatchaFilter,
      CafeHasTeaFilter,
      CafeSubscriptionFilter
    ],

    /*
      Listen to the mounted lifecycle hook.
    */
    mounted(){
      /*
        When the filters are updated, we process the filters.
      */
      EventBus.$on('filters-updated', function( filters ){
        this.processFilters( filters );
      }.bind(this));
    },

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Process the selected filters from the user.
      */
      processFilters( filters ){
        /*
          If no filters are selected, show the card
        */
        if( filters.text == ''
          && filters.type == 'all'
          && filters.brewMethods.length == 0
          && !filters.liked
          && !filters.matcha
          && !filters.tea
          && !filters.subscription ){
            this.show = true;
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

          /*
            Check if the roaster passes
          */
          if( this.processCafeTypeFilter( this.cafe, filters.type) ){
            typePassed = true;
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
          if( filters.brewMethods.length != 0 && this.processCafeBrewMethodsFilter( this.cafe, filters.brewMethods ) ){
            brewMethodsPassed = true;
          }else if( filters.brewMethods.length == 0 ){
            brewMethodsPassed = true;
          }

          /*
            Check if liked passes
          */
          if( filters.liked && this.processCafeUserLikeFilter( this.cafe ) ){
            likedPassed = true;
          }else if( !filters.liked ){
            likedPassed = true;
          }

          /*
            Checks if the cafe passes matcha filter
          */
          if( filters.matcha && this.processCafeHasMatchaFilter( this.cafe ) ){
            matchaPassed = true;
          }else if( !filters.matcha ){
            matchaPassed = true;
          }

          /*
            Checks if the cafe passes the tea filter
          */
          if( filters.tea && this.processCafeHasTeaFilter( this.cafe ) ){
            teaPassed = true;
          }else if( !filters.tea ){
            teaPassed = true;
          }

          /*
            Checks to see if the subscription filter works.
          */
          if( filters.subscription && this.processCafeSubscriptionFilter( this.cafe ) ){
            subscriptionPassed = true;
          }else if( !filters.subscription ){
            subscriptionPassed = true;
          }

          /*
            If everything passes, then we show the Cafe Card
          */
          if( typePassed && textPassed && brewMethodsPassed && likedPassed && matchaPassed && teaPassed && subscriptionPassed ){
            this.show = true;
          }else{
            this.show = false;
          }
        }
      },

      /*
        Pans to the location of the cafe on the map when selected.
      */
      panToLocation( cafe ){
        EventBus.$emit('location-selected', { lat: parseFloat( cafe.latitude ), lng: parseFloat( cafe.longitude ) });
      }
    }
  }
</script>
