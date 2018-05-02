<style>

</style>

<template>
  <div id="cafes" class="page">
    <cafe-map></cafe-map>

    <add-cafe-button></add-cafe-button>

    <router-view></router-view>
  </div>
</template>

<script>
  import CafeMap from '../components/cafes/CafeMap.vue';
  import AddCafeButton from '../components/cafes/AddCafeButton.vue';

  export default {
    components: {
      CafeMap,
      AddCafeButton
    }
  }
</script>
