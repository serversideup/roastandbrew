<style>

</style>

<template>
  <div id="cafes" class="page">
    <cafe-map v-show="cafesView == 'map' "></cafe-map>
    <cafe-list v-show="cafesView == 'list' "></cafe-list>

    <add-cafe-button></add-cafe-button>
    <toggle-cafes-view></toggle-cafes-view>
    <map-legend></map-legend>

    <router-view></router-view>
  </div>
</template>

<script>
  /*
    Imports the components used by the home page.
  */
  import CafeMap from '../components/cafes/CafeMap.vue';
  import CafeList from '../components/cafes/CafeList.vue';
  import AddCafeButton from '../components/cafes/AddCafeButton.vue';
  import ToggleCafesView from '../components/cafes/ToggleCafesView.vue';
  import MapLegend from '../components/cafes/MapLegend.vue';

  export default {
    /*
      Register the components to be used by the home page.
    */
    components: {
      CafeMap,
      CafeList,
      AddCafeButton,
      ToggleCafesView,
      MapLegend
    },

    /*
      Define the computed properties.
    */
    computed: {
      /*
        Import the cafe view from the Vuex module.
      */
      cafesView(){
        return this.$store.getters.getCafesView;
      }
    }
  }
</script>
