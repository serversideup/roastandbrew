<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#admin-layout{
    div#page-container{
      margin-top: 75px;
    }
  }
</style>

<template>
  <div id="admin-layout">
    <admin-header></admin-header>

    <success-notification></success-notification>
    <error-notification></error-notification>

    <div class="grid-container" id="page-container">
      <div class="grid-x grid-padding-x">
        <div class="large-3 medium-4 cell">
          <navigation></navigation>
        </div>
        <div class="large-9 medium-8 cell">
          <router-view></router-view>
        </div>
      </div>
    </div>

    <pop-out></pop-out>
  </div>
</template>

<script>
  /*
    Imports the layout components used by the page.
  */
  import AdminHeader from '../components/admin/AdminHeader.vue';
  import Navigation from '../components/admin/Navigation.vue';

  /*
    Imports the components used by the page.
  */
  import PopOut from '../components/global/PopOut.vue';
  import SuccessNotification from '../components/global/SuccessNotification.vue';
  import ErrorNotification from '../components/global/ErrorNotification.vue';

  /*
    Import admin Vuex modules
  */
  import { actions } from '../modules/admin/actions.js'
  import { companies } from '../modules/admin/companies.js';
  import { cafes } from '../modules/admin/cafes.js';
  import { users } from '../modules/admin/users.js';
  import { brewMethods } from '../modules/admin/brewMethods.js';
  import { cities } from '../modules/admin/cities.js';

  export default {
    /*
      Registers the components with the page.
    */
    components: {
      AdminHeader,
      Navigation,
      PopOut,
      SuccessNotification,
      ErrorNotification
    },

    /*
      Sets up the page on the created lifecycle hook.
    */
    created(){
      /*
        Loads the brew methods on the admin side.
      */
      this.$store.dispatch( 'loadBrewMethods' );

      /*
        Checks to see if the admin Vuex module is active. If not,
        register the admin module.
      */
      if( !this.$store._modules.get(['admin'] ) ){
        this.$store.registerModule( 'admin', {} );
      }

      /*
        Checks to see if the admin actions module is active. If not,
        register the admin actions module.
      */
      if( !this.$store._modules.get(['admin', 'actions']) ){
        this.$store.registerModule( ['admin', 'actions'], actions );
      }

      /*
        Checks to see if the admin companies module is active. If not,
        register the admin companies module.
      */
      if( !this.$store._modules.get(['admin', 'companies']) ){
        this.$store.registerModule( ['admin', 'companies'], companies );
      }

      /*
        Checks to see if the admin cafes module is active. If not,
        register the admin cafes module.
      */
      if( !this.$store._modules.get(['admin', 'cafes']) ){
        this.$store.registerModule( ['admin', 'cafes'], cafes );
      }

      /*
        Checks to see if the user has permissions and if the
        Vuex users module is loaded.
      */
      if( !this.$store._modules.get(['admin', 'users'] ) && this.user.permission >= 2 ){
        this.$store.registerModule( ['admin', 'users'], users );
      }

      /*
        Checks to see if the user has permissions and if the
        Vuex brew methods module is loaded.
      */
      if( !this.$store._modules.get(['admin', 'brewMethods'] ) && this.user.permission == 3 ){
        this.$store.registerModule( ['admin', 'brewMethods'], brewMethods );
      }

      /*
        Checks to see if the user has permissions and if the
        Vuex cities module is loaded.
      */
      if( !this.$store._modules.get(['admin', 'cities'] ) && this.user.permission == 3 ){
        this.$store.registerModule( ['admin', 'cities'], cities );
      }
    },

    /*
      Defines the computed properties on the page.
    */
    computed: {
      /*
        Grabs the user from the Vuex store.
      */
      user(){
        return this.$store.getters.getUser;
      }
    }
  }
</script>
