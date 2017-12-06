<style>

</style>

<template>
  <div id="app-layout">
    <navigation></navigation>

    <router-view></router-view>

    <login-modal></login-modal>

    <app-footer></app-footer>
  </div>
</template>

<script>
  import Navigation from '../components/global/Navigation.vue';
  import AppFooter from '../components/global/AppFooter.vue';
  import LoginModal from '../components/global/LoginModal.vue';

  export default {
    components: {
      Navigation,
      AppFooter,
      LoginModal
    },

    created(){
      this.$store.dispatch( 'loadCafes' );
      this.$store.dispatch( 'loadUser' );
      this.$store.dispatch( 'loadBrewMethods' );
    }
  }
</script>
