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

    div.brew-method{
      font-size: 16px;
      color: #666666;
      font-family: "Lato", sans-serif;
      border-radius: 4px;
      background-color: #F9F9FA;
      width: 150px;
      height: 57px;
      float: left;
      margin-right: 10px;
      margin-bottom: 10px;
      padding: 5px;
      cursor: pointer;
      position: relative;

      &.active{
        color: white;
        background-color: $secondary-color;
      }

      div.brew-method-container{
        position: absolute;
        top: 50%;
        transform: translateY(-50%);

        img.brew-method-icon{
          display: inline-block;
          margin-right: 10px;
          margin-left: 5px;
          width: 20px;
          max-height: 30px;
        }

        span.brew-method-name{
          display: inline-block;
          width: calc( 100% - 40px);
          vertical-align: middle;
        }
      }
    }

    div.drink-option{
      font-size: 16px;
      color: #666666;
      font-family: "Lato", sans-serif;
      border-radius: 4px;
      background-color: #F9F9FA;
      width: 150px;
      height: 57px;
      float: left;
      margin-right: 10px;
      margin-bottom: 10px;
      padding: 5px;
      cursor: pointer;
      position: relative;

      &.active{
        color: white;
        background-color: $secondary-color;
      }

      div.drink-option-container{
        position: absolute;
        top: 50%;
        transform: translateY(-50%);

        img.drink-option-icon{
          display: inline-block;
          margin-right: 10px;
          margin-left: 5px;
          width: 20px;
          max-height: 30px;
        }

        span.drink-option-name{
          display: inline-block;
          width: calc( 100% - 40px);
          vertical-align: middle;
        }
      }
    }

    span.liked-location-label{
      color: #666666;
      font-size: 16px;
      font-family: "Lato", sans-serif;
      margin-left: 10px;
    }

    div.cafe-grid-container{
      overflow: auto;
      padding-bottom: 10px;
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

      div.cafe-grid-container{
        height: inherit;
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
            <div class="brew-method" v-on:click="toggleBrewMethodFilter( method.id )" v-for="method in brewMethods" v-if="method.cafes_count > 0" v-bind:class="{'active': brewMethodsFilter.indexOf( method.id ) >= 0 }">
              <div class="brew-method-container">
                <img v-bind:src="method.icon+'.svg'" class="brew-method-icon"/> <span class="brew-method-name">{{ method.method }}</span>
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
            <div class="drink-option" v-on:click="toggleMatchaFilter()" v-bind:class="{'active':hasMatcha}">
              <div class="drink-option-container">
                <img src="/img/icons/matcha-latte.svg" class="drink-option-icon"/> <span class="drink-option-name">Matcha</span>
              </div>
            </div>
            <div class="drink-option" v-on:click="toggleTeaFilter()" v-bind:class="{'active':hasTea}">
              <div class="drink-option-container">
                <img src="/img/icons/tea-bag.svg" class="drink-option-icon"/> <span class="drink-option-name">Tea</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="grid-x grid-padding-x cafe-grid-container" id="cafe-grid">
        <cafe-card v-for="cafe in cafes" :key="cafe.id" :cafe="cafe"></cafe-card>
        <div class="large-12 medium-12 small-12 cell">
          <span class="no-results" v-if="shownCount == 0">No Results</span>
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

  import CafeCard from '../../components/cafes/CafeCard.vue';

  export default {
    /*

    */
    data(){
      return {
        textSearch: '',
        activeLocationFilter: 'all',
        onlyLiked: false,
        brewMethodsFilter: [],
        shownCount: 1,
        hasMatcha: false,
        hasTea: false
      }
    },

    watch: {
      textSearch(){
        this.updateFilterDisplay();
      },

      activeLocationFilter(){
        this.updateFilterDisplay();
      },

      onlyLiked(){
        this.updateFilterDisplay();
      },

      brewMethodsFilter(){
        this.updateFilterDisplay();
      },

      hasMatcha(){
        this.updateFilterDisplay();
      },

      hasTea(){
        this.updateFilterDisplay();
      },

      showFilters(){
        this.computeHeight();
      }
    },

    components: {
      CafeCard
    },


    mounted(){
      EventBus.$on('show-filters', function(){
        this.show = true;
      }.bind(this));

      EventBus.$on('clear-filters', function(){
        this.clearFilters();
      }.bind(this));
    },

    computed: {
      showFilters(){
        return this.$store.getters.getShowFilters;
      },

      brewMethods(){
        return this.$store.getters.getBrewMethods;
      },

      cafes(){
        return this.$store.getters.getCafes;
      },

      user(){
        return this.$store.getters.getUser;
      },

      userLoadStatus(){
        return this.$store.getters.getUserLoadStatus();
      }
    },

    methods: {
      setActiveLocationFilter( filter ){
        this.activeLocationFilter = filter;
      },

      toggleBrewMethodFilter( id ){
        if( this.brewMethodsFilter.indexOf( id ) >= 0 ){
          this.brewMethodsFilter.splice( this.brewMethodsFilter.indexOf( id ), 1 );
        }else{
          this.brewMethodsFilter.push( id );
        }
      },

      updateFilterDisplay(){
        EventBus.$emit('filters-updated', {
          text: this.textSearch,
          type: this.activeLocationFilter,
          liked: this.onlyLiked,
          brewMethods: this.brewMethodsFilter,
          matcha: this.hasMatcha,
          tea: this.hasTea
        });

        this.$nextTick(function(){
          this.computeShown();
        });
      },

      computeShown(){
        this.shownCount = $('.cafe-card-container').filter(function() {
              return $(this).css('display') !== 'none';
          }).length;
      },

      computeHeight(){
        let filtersHeight = $('#filters-container').height();

        $('#cafe-grid').css('height', ( filtersHeight - 460 ) + 'px' );
      },

      toggleShowFilters(){
        this.$store.dispatch( 'toggleShowFilters', { showFilters : !this.showFilters } );
      },

      toggleMatchaFilter(){
        this.hasMatcha = !this.hasMatcha;
      },

      toggleTeaFilter(){
        this.hasTea = !this.hasTea;
      },

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
