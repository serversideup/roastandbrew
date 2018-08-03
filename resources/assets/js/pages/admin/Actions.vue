<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#admin-actions{
    div.actions-header{
      font-family: "Lato", sans-serif;
      border-bottom: 1px solid black;
      font-weight: bold;
      padding-bottom: 10px;
    }

    div.no-actions-available{
      text-align: center;
      font-family: "Lato", sans-serif;
      font-size: 20px;
      padding-top: 20px;
      padding-bottom: 20px;
    }
  }
</style>

<template>
  <div id="admin-actions">
    <div class="grid-container">
      <div class="grid-x">
        <div class="large-12 medium-12 cell">
          <h3 class="page-header">Actions</h3>
        </div>
      </div>
    </div>


    <div class="grid-container">
      <div class="grid-x actions-header">
        <div class="large-3 medium-3 cell">
          Company
        </div>
        <div class="large-3 medium-3 cell">
          Cafe
        </div>
        <div class="large-3 medium-3 cell">
          Type
        </div>
        <div class="large-3 medium-3 cell">
          Actions
        </div>
      </div>
      <action v-for="action in actions"
        :key="action.id"
        :action="action">
      </action>
      <div class="large-12 medium-12 cell no-actions-available" v-show="actions.length == 0">
        All outstanding actions have been processed!
      </div>
    </div>
  </div>
</template>

<script>
  /*
    Imports the action component.
  */
  import Action from '../../components/admin/actions/Action.vue';

  export default {
    /*
      Register the component with the page.
    */
    components: {
      Action
    },

    /*
      Load all of the admin actions on the created lifecycle hook.
    */
    created(){
      this.$store.dispatch( 'loadAdminActions' );
    },

    /*
      Define the computed properties on the page.
    */
    computed: {
      /*
        Gets the actions from the Vuex data store.
      */
      actions(){
        return this.$store.getters.getActions;
      }
    }
  }
</script>
