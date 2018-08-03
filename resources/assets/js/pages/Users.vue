<style lang="scss">
  @import '~@/abstracts/_variables.scss';

  div#users{
    div.center{
      margin: auto;
    }
  }
</style>

<template>
  <div id="users" class="page">
    <div class="grid-x">
      <div class="large-8 medium-9 small-12 columns center">
        <h2 id="find-coffee-enthusiasts">Find Fellow Coffee Enthusiasts</h2>

        <input type="text" v-model="usersSearch" v-on:keyup="searchUsers"/>
      </div>
    </div>
  </div>
</template>

<script>
  /*
    Imports the Roast API URL from the config.
  */
  import { ROAST_CONFIG } from '../config.js';

  /*
    Imports lodash for debouncing
  */
  import _ from 'lodash';

  export default {
    /*
      Defines the data used by the component.
    */
    data(){
      return {
        usersSearch: '',
        userSearchResults: []
      }
    },

    /*
      Defines the methods used by the component.
    */
    methods: {
      /*
        Searches the API route for user autocomplete.
      */
      searchUsers: _.debounce( function(e) {
        /*
          Ensure more than two characters have been entered.
        */
        if( this.usersSearch.length > 2 ){
          /*
            Call the users endpoint with a search parameter that
            matches what the user has entered.
          */
          axios.get( ROAST_CONFIG.API_URL + '/users', {
            params: {
              search: this.usersSearch
            }
          }).then( function( response ){
            /*
              Set the user search results to the data returned from the call.
            */
            this.userSearchResults = response.data;
          }.bind(this));
        }
      }, 300),
    }
  }
</script>
