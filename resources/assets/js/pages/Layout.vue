<style>

</style>

<template>
  <div id="app-layout">
    <navigation></navigation>

    <router-view></router-view>

    <app-footer></app-footer>
  </div>
</template>

<script>
  import Navigation from '../components/global/Navigation.vue';
  import AppFooter from '../components/global/AppFooter.vue';

  export default {
    components: {
      Navigation,
      AppFooter
    },

    created(){
      this.$store.dispatch( 'loadCafes' );
      this.$store.dispatch( 'loadUser' );
      this.$store.dispatch( 'loadBrewMethods' );
    }
  }
</script>
