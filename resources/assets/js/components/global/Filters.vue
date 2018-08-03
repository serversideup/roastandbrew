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
    <div class="filters-container" id="filters-container" v-show="showFilters">
      <div class="close-filters" v-on:click="toggleShowFilters()">
        <img src="/img/grey-left.svg"/>
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

  /*
    Imports the cafe card component.
  */
  import CafeCard from '../../components/cafes/CafeCard.vue';

  export default {
    /*
      Defines the data used by the component.
    */
    data(){
      return {
        textSearch: '',
        activeLocationFilter: 'all',
        onlyLiked: false,
        brewMethodsFilter: [],
        hasMatcha: false,
        hasTea: false
      }
    },

    /*
      Defines the watchers used by the component.
    */
    watch: {
      /*
        Watch the text search variable
      */
      textSearch(){
        this.updateFilterDisplay();
      },

      /*
        Watch the active location filter
      */
      activeLocationFilter(){
        this.updateFilterDisplay();
      },

      /*
        Watch the only liked filter.
      */
      onlyLiked(){
        this.updateFilterDisplay();
      },

      /*
        Watch the brew methods filter.
      */
      brewMethodsFilter(){
        this.updateFilterDisplay();
      },

      /*
        Watch the has matcha filter.
      */
      hasMatcha(){
        this.updateFilterDisplay();
      },

      /*
        Watch the has tea filter.
      */
      hasTea(){
        this.updateFilterDisplay();
      }
    },

    /*
      Registers the components with the component.
    */
    components: {
      CafeCard
    },

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
        Gets the cafes from the state.
      */
      cafes(){
        return this.$store.getters.getCafes;
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
        this.activeLocationFilter = filter;
      },

      /*
        Toggle the brew method filter.
      */
      toggleBrewMethodFilter( id ){
        /*
          If the filter is in the selected filter, we remove it, otherwise
          we add it.
        */
        if( this.brewMethodsFilter.indexOf( id ) >= 0 ){
          this.brewMethodsFilter.splice( this.brewMethodsFilter.indexOf( id ), 1 );
        }else{
          this.brewMethodsFilter.push( id );
        }
      },

      /*
        Update filtered cafes when the filters have changed.
      */
      updateFilterDisplay(){
        EventBus.$emit('filters-updated', {
          text: this.textSearch,
          type: this.activeLocationFilter,
          liked: this.onlyLiked,
          brewMethods: this.brewMethodsFilter,
          matcha: this.hasMatcha,
          tea: this.hasTea
        });
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
        this.hasMatcha = !this.hasMatcha;
      },

      /*
        Toggle the tea filter.
      */
      toggleTeaFilter(){
        this.hasTea = !this.hasTea;
      },

      /*
        Clear all of the filters.
      */
      clearFilters(){
        this.textSearch = '';
        this.activeLocationFilter = 'all';
        this.onlyLiked = false;
        this.brewMethodsFilter = [];
        this.hasMatcha = false;
        this.hasTea = false;
      }
    }
  }
</script>
