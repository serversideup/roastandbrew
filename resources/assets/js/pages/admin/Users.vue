<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div.users-header{
    font-family: "Lato", sans-serif;
    border-bottom: 1px solid black;
    font-weight: bold;
    padding-bottom: 10px;
  }
</style>

<template>
  <div id="admin-users">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-12 medium-12 cell">
          <h3 class="page-header">Users</h3>
        </div>
      </div>
    </div>

    <div class="grid-container">
      <div class="grid-x users-header">
        <div class="large-3 medium-3 cell">
          Name
        </div>
        <div class="large-4 medium-4 cell">
          Email
        </div>
        <div class="large-3 medium-3 cell">
          Type
        </div>
        <div class="large-2 medium-2 cell">

        </div>
      </div>

      <user v-for="(user, key) in users"
        :key="user.id"
        :user="user"
        ></user>
    </div>
  </div>
</template>

<script>
  /*
    Imports the components used by the page.
  */
  import User from '../../components/admin/users/User.vue';

  export default {
    /*
      Defines the components used by the page.
    */
    components: {
      User
    },

    /*
      Sets up the page on the created lifecycle hook.
    */
    created(){
      /*
        Loads the application's users
      */
      this.$store.dispatch( 'loadAdminUsers' );
    },

    computed: {
      /*
        Gets the users from the API
      */
      users(){
        return this.$store.getters.getAdminUsers;
      }
    }
  }
</script>
