/*
|-------------------------------------------------------------------------------
| VUEX modules/brewmethods.js
|-------------------------------------------------------------------------------
| The Vuex data store for the brew methods
*/
import BrewMethodAPI from '../api/brewMethod.js';

export const brewMethods = {
  /*
    Defines the state being monitored for the module
  */
  state: {
    brewMethods: [],
    brewMethodsLoadStatus: 0
  },

  /*
    Defines the actions used by the Vuex module.
  */
  actions: {
    /*
      Loads all of the brew methods.
    */
    loadBrewMethods( { commit } ){
      commit( 'setBrewMethodsLoadStatus', 1 );

      /*
        Calls the API to load the brew methods.
      */
      BrewMethodAPI.getBrewMethods()
        .then( function( response ){
          /*
            Sets the brew methods on a successful response.
          */
          commit( 'setBrewMethods', response.data );
          commit( 'setBrewMethodsLoadStatus', 2 );
        })
        .catch( function(){
          /*
            Clears the brew methods on failure.
          */
          commit( 'setBrewMethods', [] );
          commit( 'setBrewMethodsLoadStatus', 3 );
        });
    }
  },

  /*
    Defines the mutations used by the module.
  */
  mutations: {
    /*
      Sets the brew method load status.
    */
    setBrewMethodsLoadStatus( state, status ){
      state.brewMethodsLoadStatus = status;
    },

    /*
      Sets the brew methods.
    */
    setBrewMethods( state, brewMethods ){
      state.brewMethods = brewMethods;
    }
  },

  /*
    Defines the getters used by the module.
  */
  getters: {
    /*
      Returns the brew methods.
    */
    getBrewMethods( state ){
      return state.brewMethods;
    },

    /*
      Returns the brew methods load status.
    */
    getBrewMethodsLoadStatus( state ){
      return state.brewMethodsLoadStatus;
    }
  }
}
