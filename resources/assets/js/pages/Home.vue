<style>

</style>

<template>
  <div id="home" class="page">

    <div class="grid-container">
      <div class="grid-x">
        <div class="large-12 medium-12 small-12 columns">
          <router-link :to="{ name: 'newcafe' }">+ Add Cafe</router-link>
        </div>
      </div>
    </div>
    <div class="grid-container">
      <div class="grid-x grid-padding-x">
        <loader v-show="cafesLoadStatus == 1" :width="100" :height="100"></loader>
        <cafe-card v-for="cafe in cafes" :key="cafe.id" :cafe="cafe"></cafe-card>
      </div>
    </div>

  </div>
</template>

<script>

  import CafeCard from '../components/cafes/CafeCard.vue';
  import Loader from '../components/global/Loader.vue';

  export default {
    components: {
      CafeCard,
      Loader
    },

    /*
      Defines the computed properties on the component.
    */
    computed: {
      /*
        Gets the cafes load status
      */
      cafesLoadStatus(){
        return this.$store.getters.getCafesLoadStatus;
      },

      /*
        Gets the cafes
      */
      cafes(){
        return this.$store.getters.getCafes;
      }
    }
  }
</script>
