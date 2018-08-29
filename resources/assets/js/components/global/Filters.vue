<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.filters-container{
    background-color: white;
    position: fixed;
    left: 0;
    bottom: 0;
    top: 75px;
    max-width: 550px;
    width: 100%;
    padding-top: 50px;
    box-shadow: 0 2px 4px 0 rgba(3,27,78,0.10);
    z-index: 99;

    span.clear-filters{
      font-size: 16px;
      color: $text-secondary-color;
      font-family: "Lato", sans-serif;
      cursor: pointer;
      display: block;
      float: left;
      margin-bottom: 20px;
      display: none;

      img{
        margin-right: 10px;
        float: left;
        margin-top: 6px;
      }
    }

    span.filters-header{
      display: block;
      font-family: "Lato", sans-serif;
      font-weight: bold;
      margin-bottom: 10px;
    }

    input[type="text"].search{
      box-shadow: none;
      border-radius: 3px;
      color: #BABABA;
      font-size: 16px;
      font-family: "Lato", sans-serif;
      background-image: url('/img/search-icon.svg');
      background-repeat: no-repeat;
      background-position: 6px;
      padding-left: 35px;
      padding-top: 5px;
      padding-bottom: 5px;
    }

    label.filter-label{
      font-family: "Lato", sans-serif;
      text-transform: uppercase;
      font-weight: bold;
      color: black;
      margin-top: 20px;
      margin-bottom: 10px;
    }

    div.location-filter{
      text-align: center;
      font-family: "Lato", sans-serif;
      font-size: 16px;
      color: $secondary-color;
      border-bottom: 1px solid $secondary-color;
      border-top: 1px solid $secondary-color;
      border-left: 1px solid $secondary-color;
      border-right: 1px solid $secondary-color;
      width: 33%;
      display: inline-block;
      height: 55px;
      line-height: 55px;
      cursor: pointer;
      margin-bottom: 5px;

      &.active{
        color: white;
        background-color: $secondary-color;
      }

      &.all-locations{
        border-top-left-radius: 3px;
        border-bottom-left-radius: 3px;
      }

      &.roasters{
        border-left: none;
        border-right: none;
      }

      &.cafes{
        border-top-right-radius: 3px;
        border-bottom-right-radius: 3px;
      }
    }

    span.liked-location-label{
      color: #666666;
      font-size: 16px;
      font-family: "Lato", sans-serif;
      margin-left: 10px;
    }

    div.close-filters{
      height: 90px;
      width: 23px;
      position: absolute;
      right: -20px;
      background-color: white;
      border-top-right-radius: 3px;
      border-bottom-right-radius: 3px;
      line-height: 90px;
      top: 50%;
      cursor: pointer;
      margin-top: -82px;
      text-align: center;
    }

    span.no-results{
      display: block;
      text-align: center;
      margin-top: 50px;
      color: #666666;
      text-transform: uppercase;
      font-weight: 600;
    }
  }

  /* Small only */
  @media screen and (max-width: 39.9375em) {
    div.filters-container{
      padding-top: 25px;
      overflow-y: auto;

      span.clear-filters{
        display: block;
      }

      div.close-filters{
        display: none;
      }
    }
  }

  /* Medium only */
  @media screen and (min-width: 40em) and (max-width: 63.9375em) {

  }

  /* Large only */
  @media screen and (min-width: 64em) and (max-width: 74.9375em) {

  }
</style>

<template>
  <transition name="slide-in-left">
    <div class="filters-container" id="filters-container" v-show="showFilters && cafesView == 'map'">
      <div class="close-filters" v-on:click="toggleShowFilters()">
        <img src="/img/grey-left.svg"/>
      </div>

      <div class="grid-x grid-padding-x">
        <div class="large-12 medium-12 small-12 cell">
          <span class="filters-header">Find the type of coffee shop you are looking for!</span>
        </div>
      </div>

      <div class="grid-x grid-padding-x" id="text-container">
        <div class="large-12 medium-12 small-12 cell">
          <span class="clear-filters" v-show="showFilters" v-on:click="clearFilters()">
            <img src="/img/clear-filters-icon.svg"/> Clear filters
          </span>
          <input type="text" class="search" v-model="textSearch" placeholder="Find locations by name"/>
        </div>
      </div>

      <div id="location-type-container">
        <div class="grid-x grid-padding-x">
          <div class="large-12 medium-12 small-12 cell">
            <label class="filter-label">Location Types</label>
          </div>
        </div>

        <div class="grid-x grid-padding-x">
          <div class="large-12 medium-12 small-12 cell">
            <div class="location-filter all-locations" v-bind:class="{ 'active': activeLocationFilter == 'all' }" v-on:click="setActiveLocationFilter('all')">
              All Locations
            </div><div class="location-filter roasters" v-bind:class="{ 'active': activeLocationFilter == 'roasters' }" v-on:click="setActiveLocationFilter('roasters')">
              Roasters
            </div><div class="location-filter cafes" v-bind:class="{ 'active': activeLocationFilter == 'cafes' }" v-on:click="setActiveLocationFilter('cafes')">
              Cafes
            </div>
          </div>
        </div>
      </div>

      <div class="grid-x grid-padding-x" id="only-liked-container" v-show="user != '' && userLoadStatus == 2">
        <div class="large-12 medium-12 small-12 cell">
          <input type="checkbox" v-model="onlyLiked"/> <span class="liked-location-label">Show only locations that I like</span>
        </div>
      </div>

      <div class="grid-x grid-padding-x" v-show="activeLocationFilter == 'roasters' || activeLocationFilter == 'all'">
        <div class="large-12 medium-12 small-12 cell">
          <label class="filter-label">Has Subscription Service</label>
        </div>
      </div>

      <div class="grid-x grid-padding-x" v-show="activeLocationFilter == 'roasters' || activeLocationFilter == 'all'">
        <div class="large-12 medium-12 small-12 cell">
          <div class="subscription option" v-on:click="toggleSubscriptionFilter()" v-bind:class="{'active': hasSubscription }">
            <div class="option-container">
              <img src="/img/icons/coffee-pack.svg" class="option-icon"/> <span class="option-name">Coffee Subscription</span>
            </div>
          </div>
        </div>
      </div>

      <div id="brew-methods-container">
        <div class="grid-x grid-padding-x">
          <div class="large-12 medium-12 small-12 cell">
            <label class="filter-label">Brew Methods</label>
          </div>
        </div>

        <div class="grid-x grid-padding-x">
          <div class="large-12 medium-12 small-12 cell" >
            <div class="brew-method option" v-on:click="toggleBrewMethodFilter( method.id )" v-for="method in brewMethods" v-if="method.cafes_count > 0" v-bind:class="{'active': brewMethodsFilter.indexOf( method.id ) >= 0 }">
              <div class="option-container">
                <img v-bind:src="method.icon+'.svg'" class="option-icon"/> <span class="option-name">{{ method.method }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="drink-options-container">
        <div class="grid-x grid-padding-x">
          <div class="large-12 medium-12 small-12 cell">
            <label class="filter-label">Drink Options</label>
          </div>
        </div>

        <div class="grid-x grid-padding-x">
          <div class="large-12 medium-12 small-12 cell">
            <div class="drink-option option" v-on:click="toggleMatchaFilter()" v-bind:class="{'active':hasMatcha}">
              <div class="option-container">
                <img src="/img/icons/matcha-latte.svg" class="option-icon"/> <span class="option-name">Matcha</span>
              </div>
            </div>
            <div class="drink-option option" v-on:click="toggleTeaFilter()" v-bind:class="{'active':hasTea}">
              <div class="option-container">
                <img src="/img/icons/tea-bag.svg" class="option-icon"/> <span class="option-name">Tea</span>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </transition>
</template>

<script>
  /*
    Imports the event bus
  */
  import { EventBus } from '../../event-bus.js';

  export default {
    /*
      Defines the mounted lifecycle hook.
    */
    mounted(){
      /*
        When the user wants to show the filters, we show the filter
        sidebar.
      */
      EventBus.$on('show-filters', function(){
        this.show = true;
      }.bind(this));

      /*
        When the user clears the filters, we clear all set filters.
      */
      EventBus.$on('clear-filters', function(){
        this.clearFilters();
      }.bind(this));
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
      },

      /*
        Gets the brew methods from the state.
      */
      brewMethods(){
        return this.$store.getters.getBrewMethods;
      },

      /*
        Gets the user from the state.
      */
      user(){
        return this.$store.getters.getUser;
      },

      /*
        Gets the user load status from the state.
      */
      userLoadStatus(){
        return this.$store.getters.getUserLoadStatus();
      },

      /*
        Gets the current views the cafes are in.
      */
      cafesView(){
        return this.$store.getters.getCafesView;
      },

      /*
        Gets the current filter text search
      */
      textSearch: {
        set( textSearch ) {
          this.$store.commit( 'setTextSearch', textSearch )
        },
        get() {
          return this.$store.getters.getTextSearch;
        }
      },

      /*
        Gets the active location filter.
      */
      activeLocationFilter(){
        return this.$store.getters.getActiveLocationFilter;
      },

      /*
        Gets the only liked filter.
      */
      onlyLiked: {
        set( onlyLiked ){
          this.$store.commit( 'setOnlyLiked', onlyLiked );
        },
        get(){
          return this.$store.getters.getOnlyLiked;
        }
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

    /*
      Defines the methods on the compnent.
    */
    methods: {
      /*
        Sets the active location filter.
      */
      setActiveLocationFilter( filter ){
        this.$store.dispatch('updateActiveLocationFilter', filter);
      },

      /*
        Toggle the brew method filter.
      */
      toggleBrewMethodFilter( id ){
        let localBrewMethodsFilter = this.brewMethodsFilter;
        /*
          If the filter is in the selected filter, we remove it, otherwise
          we add it.
        */

        if( localBrewMethodsFilter.indexOf( id ) >= 0 ){
          localBrewMethodsFilter.splice( localBrewMethodsFilter.indexOf( id ), 1 );
        }else{
          localBrewMethodsFilter.push( id );
        }

        this.$store.dispatch('updateBrewMethodsFilter', localBrewMethodsFilter );
      },

      /*
        Toggle the show and hide of filter sidebar.
      */
      toggleShowFilters(){
        this.$store.dispatch( 'toggleShowFilters', { showFilters : !this.showFilters } );
      },

      /*
        Toggle the matcha filter.
      */
      toggleMatchaFilter(){
        this.$store.dispatch( 'updateHasMatcha', !this.hasMatcha );
      },

      /*
        Toggle the tea filter.
      */
      toggleTeaFilter(){
        this.$store.dispatch( 'updateHasTea', !this.hasTea );
      },

      /*
        Toggle the subscription filter.
      */
      toggleSubscriptionFilter(){
        this.$store.dispatch( 'updateHasSubscription', !this.hasSubscription );
      },

      /*
        Clear all of the filters.
      */
      clearFilters(){
        this.$store.dispatch( 'resetFilters' );
      }
    }
  }
</script>
