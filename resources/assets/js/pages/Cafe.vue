<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.cafe-page{
    h2{
      text-align: center;
      color: $primary-color;
      font-family: 'Josefin Sans', sans-serif;
    }

    h3{
      text-align: center;
      color: $secondary-color;
      font-family: 'Josefin Sans', sans-serif;
    }

    span.address{
      text-align: center;
      display: block;
      font-family: 'Lato', sans-serif;
      color: #A0A0A0;
      font-size: 20px;
      line-height: 30px;
      margin-top: 50px;
    }

    a.website{
      text-align: center;
      color: $dull-color;
      font-size: 30px;
      font-weight: bold;
      margin-top: 50px;
      display: block;
      font-family: 'Josefin Sans', sans-serif;
    }

    div.brew-methods-container{
      max-width: 700px;
      margin: auto;

      div.cell{
        text-align: center;
      }
    }

    div.tags-container{
      max-width: 700px;
      margin: auto;
      text-align: center;
      margin-top: 30px;
      
      span.tag{
        color: $dark-color;
        font-family: 'Josefin Sans', sans-serif;
        margin-right: 20px;
        display: inline-block;
        line-height: 20px;
      }
    }
  }
</style>

<template>
  <div id="cafe" class="page">

    <div class="grid-container">
      <div class="grid-x grid-padding-x">

        <div class="large-12 medium-12 small-12 cell">
          <loader v-show="cafeLoadStatus == 1"
                  :width="100"
                  :height="100"></loader>

          <div class="cafe-page" v-show="cafeLoadStatus == 2">
            <h2>{{ cafe.name }}</h2>
            <h3 v-if="cafe.location_name != ''">{{ cafe.location_name }}</h3>

            <span class="address">
              {{ cafe.address }}<br>
              {{ cafe.city }}, {{ cafe.state }}<br>
              {{ cafe.zip }}
            </span>

            <toggle-like></toggle-like>

            <div class="tags-container">
              <div class="grid-x grid-padding-x">
                <div class="large-12 medium-12 small-12 cell">
                  <span class="tag" v-for="tag in cafe.tags">#{{ tag.tag }}</span>
                </div>
              </div>
            </div>

            <a class="website" v-bind:href="cafe.website" target="_blank">{{ cafe.website }}</a>

            <div class="brew-methods-container">
              <div class="grid-x grid-padding-x">
                <div class="large-3 medium-4 small-12 cell" v-for="brewMethod in cafe.brew_methods">
                  {{ brewMethod.method }}
                </div>
              </div>
            </div>

            <br>

            <individual-cafe-map></individual-cafe-map>
          </div>
        </div>

      </div>
    </div>

  </div>
</template>

<script>
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

      /*
        Grabs the cafe from the Vuex state.
      */
      cafe(){
        return this.$store.getters.getCafe;
      }
    }
  }
</script>
