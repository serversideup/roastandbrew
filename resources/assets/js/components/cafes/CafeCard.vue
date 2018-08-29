<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.cafe-card{
    border-radius: 5px;
    box-shadow: 0 2px 2px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.08);
    padding: 15px 5px 5px 5px;
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

    span.liked-meta{
      color: $grey;
      font-size: 10px;
      margin-left: 5px;
      margin-right: 3px;

      img{
        width: 10px;
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
        <div class="meta-data">
          <span class="liked-meta"><img v-bind:src="cafe.user_like_count > 0 ? '/img/liked.svg' : '/img/unliked.svg'"/> {{ cafe.likes_count }}</span>
        </div>
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

    computed: {
      textSearch(){
        return this.$store.getters.getTextSearch;
      },

      activeLocationFilter(){
        return this.$store.getters.getActiveLocationFilter;
      },

      onlyLiked(){
        return this.$store.getters.getOnlyLiked;
      },

      brewMethodsFilter(){
        return this.$store.getters.getBrewMethodsFilter;
      },

      hasMatcha(){
        return this.$store.getters.getHasMatcha;
      },

      hasTea(){
        return this.$store.getters.getHasTea;
      },

      hasSubscription(){
        return this.$store.getters.getHasSubscription;
      }
    },

    watch: {
      textSearch(){
        this.processFilters();
      },

      activeLocationFilter(){
        this.processFilters();
      },

      onlyLiked(){
        this.processFilters();
      },

      brewMethodsFilter(){
        this.processFilters();
      },

      hasMatcha(){
        this.processFilters();
      },

      hasTea(){
        this.processFilters();
      },

      hasSubscription(){
        this.processFilters();
      }
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
        if( this.textSearch == ''
          && this.activeLocationFilter == 'all'
          && this.brewMethodsFilter.length == 0
          && !this.onlyLiked
          && !this.hasMatcha
          && !this.hasTea
          && !this.hasSubscription ){
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
          if( this.processCafeTypeFilter( this.cafe, this.activeLocationFilter) ){
            typePassed = true;
          }

          /*
            Check if text passes
          */
          if( this.textSearch != '' && this.processCafeTextFilter( this.cafe, this.textSearch ) ){
            textPassed = true;
          }else if( this.textSearch == '' ){
            textPassed = true;
          }

          /*
            Check if brew methods passes
          */
          if( this.brewMethodsFilter.length != 0 && this.processCafeBrewMethodsFilter( this.cafe, this.brewMethodsFilter ) ){
            brewMethodsPassed = true;
          }else if( this.brewMethodsFilter.length == 0 ){
            brewMethodsPassed = true;
          }

          /*
            Check if liked passes
          */
          if( this.onlyLiked && this.processCafeUserLikeFilter( this.cafe ) ){
            likedPassed = true;
          }else if( !this.onlyLiked ){
            likedPassed = true;
          }

          /*
            Checks if the cafe passes matcha filter
          */
          if( this.hasMatcha && this.processCafeHasMatchaFilter( this.cafe ) ){
            matchaPassed = true;
          }else if( !this.hasMatcha ){
            matchaPassed = true;
          }

          /*
            Checks if the cafe passes the tea filter
          */
          if( this.hasTea && this.processCafeHasTeaFilter( this.cafe ) ){
            teaPassed = true;
          }else if( !this.hasTea ){
            teaPassed = true;
          }

          /*
            Checks to see if the subscription filter works.
          */
          if( this.hasSubscription && this.processCafeSubscriptionFilter( this.cafe ) ){
            subscriptionPassed = true;
          }else if( !this.hasSubscription ){
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
