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

    div.location-type{
      color: white;
      font-family: "Lato", sans-serif;
      font-size: 16px;
      width: 105px;
      height: 45px;
      text-align: center;
      line-height: 45px;
      border-radius: 3px;

      img{
        margin-right: 5px;
      }

      &.roaster{
        background-color: $secondary-color;
      }

      &.cafe{
        background-color: black;

        img{
          margin-top: -6px;
        }
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

      div.brew-method-container{
        position: absolute;
        top: 50%;
        transform: translateY(-50%);

        img.brew-method-icon{
          display: inline-block;
          margin-right: 10px;
          margin-left: 5px;
        }

        span.brew-method-name{
          display: inline-block;
          width: calc( 100% - 40px);
          vertical-align: middle;
        }
      }
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
    <router-link :to="{ name: 'cafes' }">
      <img class="close-icon" src="/img/close-icon.svg"/>
    </router-link>
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
    <div class="grid-x">
      <div class="large-12 medium-12 small-12 cell">
        <label class="cafe-label">Brew Methods</label>
        <div class="brew-method" v-for="method in cafe.brew_methods">
          <div class="brew-method-container">
            <img v-bind:src="method.icon+'.svg'" class="brew-method-icon"/> <span class="brew-method-name">{{ method.method }}</span>
          </div>
        </div>
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
        <router-link :to="{ name: 'editcafe', params: { id: cafe.id } }" v-show="userLoadStatus == 2 && user != ''" class="suggest-cafe-edit">
          Suggest an edit
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
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
      this.$store.dispatch( 'loadCafe', {
        id: this.$route.params.id
      });
    },

    watch: {
      '$route.params.id': function(){
        this.$store.dispatch( 'clearLikeAndUnlikeStatus' );
        this.$store.dispatch( 'loadCafe', {
          id: this.$route.params.id
        });
			},
    },


    /*
      Defines the computed variables on the cafe.
    */
    computed: {
      /*
        Grabs the cafe load status from the Vuex state.
      */
      cafeLoadStatus(){
        return this.$store.getters.getCafeLoadStatus;
      },

      cafeLikeActionStatus(){
        return this.$store.getters.getCafeLikeActionStatus;
      },

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
      login(){
        EventBus.$emit('prompt-login');
      }
    }
  }
</script>
