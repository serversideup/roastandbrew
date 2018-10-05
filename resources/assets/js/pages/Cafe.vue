<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#cafe-page{
    position: absolute;
    right: 30px;
    top: 125px;
    background: #FFFFFF;
    box-shadow: 0 2px 4px 0 rgba(3,27,78,0.10);
    width: 100%;
    max-width: 480px;
    padding: 20px;
    padding-top: 10px;

    img.close-icon{
      float: right;
      cursor: pointer;
      margin-top: 10px;
    }

    h2.cafe-title{
      color: #342C0C;
      font-size: 36px;
      line-height: 44px;
      font-family: "Lato", sans-serif;
      font-weight: bolder;
    }

    span.location-number{
      display: inline-block;
      color: #8E8E8E;
      font-size: 18px;

      span.location-image-container{
        width: 35px;
        text-align: center;
        display: inline-block;
      }
    }

    label.cafe-label{
      font-family: "Lato", sans-serif;
      text-transform: uppercase;
      font-weight: bold;
      color: black;
      margin-top: 20px;
      margin-bottom: 10px;
    }

    div.address-container{
      color: #666666;
      font-size: 18px;
      line-height: 23px;
      font-family: "Lato", sans-serif;
      margin-bottom: 5px;

      span.address{
        display: block;
      }

      span.city-state{
        display: block;
      }

      span.zip{
        display: block;
      }
    }

    a.cafe-website{
      font-family: "Lato", sans-serif;
      color: #543729;
      font-size: 18px;
    }

    img.social-icon{
      margin-top: 10px;
      margin-right: 10px;
    }

    a.suggest-cafe-edit{
      font-family: "Lato", sans-serif;
      color: #054E7A;
      font-size: 14px;
      display: inline-block;
      margin-top: 30px;
      text-decoration: underline;
    }
  }

  /* Small only */
  @media screen and (max-width: 39.9375em) {
    div#cafe-page{
      position: fixed;
      right: 0px;
      left: 0px;
      top: 0px;
      bottom: 0px;
      z-index: 99999;
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
  <div id="cafe-page" v-if="cafeLoadStatus == 2 || ( cafeLoadStatus != 2 && ( cafeLikeActionStatus == 1 || cafeLikeActionStatus == 2 || cafeUnlikeActionStatus == 1 || cafeUnlikeActionStatus == 2 ) )">
    <a v-on:click="leaveCafe()">
      <img class="close-icon" src="/img/close-icon.svg"/>
    </a>
    <h2 class="cafe-title">{{ cafe.company.name }}</h2>
    <div class="grid-x">
      <div class="large-12 medium-12 small-12 cell">
        <toggle-like></toggle-like>
      </div>
    </div>
    <div class="grid-x" v-if="cafe.company.cafes_count > 1">
      <div class="large-12 medium-12 small-12 cell">
        <span class="location-number">
          <span class="location-image-container">
            <img src="/img/location.svg"/>
          </span> {{ cafe.company.cafes_count }} other locations
        </span>
      </div>
    </div>
    <div class="grid-x">
      <div class="large-12 medium-12 small-12 cell">
        <label class="cafe-label">Location Type</label>
        <div class="location-type roaster" v-if="cafe.company.roaster == 1">
          <img src="/img/roaster-logo.svg"/> Roaster
        </div>
        <div class="location-type cafe" v-if="cafe.company.roaster == 0">
          <img src="/img/cafe-logo.svg"/> Cafe
        </div>
      </div>
    </div>
    <div class="grid-x" v-if="cafe.company.subscription == 1">
      <div class="large-12 medium-12 small-12 cell centered">
        <label class="cafe-label">Offers Coffee Subscription</label>
        <div class="subscription-option option">
          <div class="option-container">
            <img src="/img/icons/coffee-pack.svg" class="option-icon"/> <span class="option-name">Coffee Subscription</span>
          </div>
        </div>
      </div>
    </div>
    <div class="grid-x">
      <div class="large-12 medium-12 small-12 cell">
        <label class="cafe-label">Brew Methods</label>
        <div class="brew-method option" v-for="method in cafe.brew_methods">
          <div class="option-container">
            <img v-bind:src="method.icon+'.svg'" class="option-icon"/> <span class="option-name">{{ method.method }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="grid-x" v-if="cafe.matcha == 1 || cafe.tea == 1">
      <div class="large-12 medium-12 small-12 cell">
        <label class="cafe-label">Drink Options</label>
        <div class="drink-option option" v-if="cafe.matcha == 1">
          <div class="option-container">
            <img v-bind:src="'/img/icons/matcha-latte.svg'" class="option-icon"/> <span class="option-name">Matcha</span>
          </div>
        </div>
        <div class="drink-option option" v-if="cafe.tea == 1">
          <div class="option-container">
            <img v-bind:src="'/img/icons/tea-bag.svg'" class="option-icon"/> <span class="option-name">Tea</span>
          </div>
        </div>
      </div>
    </div>
    <div class="grid-x" v-if="cafe.company.instagram_url != null || cafe.company.facebook_url != null || cafe.company.twitter_url != null">
      <div class="large-12 medium-12 small-12 cell">
        <a v-bind:href="cafe.company.instagram_url" v-if="cafe.company.instagram_url != null" target="_blank">
          <img src="/img/instagram-logo.svg" class="social-icon"/>
        </a>
        <a v-bind:href="cafe.company.facebook_url" v-if="cafe.company.facebook_url != null" target="_blank">
          <img src="/img/facebook-logo.svg" class="social-icon"/>
        </a>
        <a v-bind:href="cafe.company.twitter_url" v-if="cafe.company.twitter_url != null" target="_blank">
          <img src="/img/twitter-logo.svg" class="social-icon"/>
        </a>
      </div>
    </div>
    <div class="grid-x">
      <div class="large-12 medium-12 small-12 cell">
        <label class="cafe-label">Location And Information</label>
        <div class="address-container">
          <span class="address">{{ cafe.address }}</span>
          <span class="city-state">{{ cafe.city }}, {{ cafe.state }}</span>
          <span class="zip">{{ cafe.zip }}</span>
        </div>

        <a class="cafe-website" target="_blank" v-bind:href="cafe.company.website">{{ cafe.company.website }}</a>
        <br>
        <router-link :to="{ name: 'editcafe', params: { slug: cafe.slug } }" v-show="userLoadStatus == 2 && user != ''" class="suggest-cafe-edit">
          Suggest an edit
        </router-link>
        <a class="suggest-cafe-edit" v-if="userLoadStatus == 2 && user == ''" v-on:click="loginToEdit()">
          Sign in to make an edit
        </a>
      </div>
    </div>
  </div>
</template>

<script>
  /*
    Imports the event bus
  */
  import { EventBus } from '../event-bus.js';

  /*
    Import the loader and cafe map for use in the component.
  */
  import Loader from '../components/global/Loader.vue';
  import IndividualCafeMap from '../components/cafes/IndividualCafeMap.vue';
  import ToggleLike from '../components/cafes/ToggleLike.vue';

  export default {
    /*
      Defines the components used by the page.
    */
    components: {
      Loader,
      IndividualCafeMap,
      ToggleLike
    },

    /*
      When created, load the cafe based on the ID in the
      route parameter.
    */
    created(){
      this.$store.dispatch( 'toggleShowFilters', { showFilters : false } );
      this.$store.dispatch( 'changeCafesView', 'map' );
      this.$store.dispatch( 'loadCafe', {
        slug: this.$route.params.slug
      });
    },

    /*
      Defines what to watch in the component.
    */
    watch: {
      /*
        When the route changes, we clear the like and unlike
        status and load the new cafe.
      */
      '$route.params.slug': function(){
        this.$store.dispatch( 'clearLikeAndUnlikeStatus' );
        this.$store.dispatch( 'loadCafe', {
          slug: this.$route.params.slug
        });
			},

      /*
        Watch for when the cafe has been loaded successfully.
      */
      'cafeLoadStatus': function(){
        /*
          If the cafe has been loaded successfully, zoom to the location.
        */
        if( this.cafeLoadStatus == 2 ){
          EventBus.$emit('location-selected', { lat: parseFloat( this.cafe.latitude ), lng: parseFloat( this.cafe.longitude ) });
        }

        /*
          If the cafe has been loaded unsuccessfully, show an error and go
          back to the cafes page.
        */
        if( this.cafeLoadStatus == 3 ){
          EventBus.$emit('show-error', { notification: 'Cafe Not Found!'} );
          this.$router.push({ name: 'cafes' });
        }
      }
    },


    /*
      Defines the computed variables on the cafe.
    */
    computed: {
      /*
        Gets the cities from the Vuex data store.
      */
      cities(){
        return this.$store.getters.getCities;
      },

      /*
        Gets the cities filter from the Vuex data store.
      */
      cityFilter(){
        return this.$store.getters.getCityFilter;
      },

      /*
        Grabs the cafe load status from the Vuex state.
      */
      cafeLoadStatus(){
        return this.$store.getters.getCafeLoadStatus;
      },

      /*
        Gets the like cafe action status.
      */
      cafeLikeActionStatus(){
        return this.$store.getters.getCafeLikeActionStatus;
      },

      /*
        Gets the unlike cafe action status
      */
      cafeUnlikeActionStatus(){
        return this.$store.getters.getCafeUnlikeActionStatus;
      },

      /*
        Grabs the cafe from the Vuex state.
      */
      cafe(){
        return this.$store.getters.getCafe;
      },

      /*
        Gets the authenticated user.
      */
      user(){
        return this.$store.getters.getUser;
      },

      /*
        Gets the user's load status.
      */
      userLoadStatus(){
        return this.$store.getters.getUserLoadStatus();
      }
    },

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Requires the user be logged in to edit.
      */
      loginToEdit(){
        EventBus.$emit('prompt-login');
      },

      /*
        When leaving a cafe, we determine which page to go to.
      */
      leaveCafe(){
        /*
          If the city filter is set, we go back to the city, otherwise,
          we load all cafes.
        */
        if( this.cityFilter != '' ){
          let slug = '';

          for( let i = 0; i < this.cities.length; i++ ){
            if( this.cities[i].id == this.cityFilter ){
              slug = this.cities[i].slug;
            }
          }

          this.$router.push( { name: 'city', params: { slug: slug } } );
        }else{
          this.$router.push({ name: 'cafes' });
        }
      }
    }
  }
</script>
