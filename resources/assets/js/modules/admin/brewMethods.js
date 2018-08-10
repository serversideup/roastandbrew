/*
|-------------------------------------------------------------------------------
| VUEX modules/admin/brewMethods.js
|-------------------------------------------------------------------------------
| The Vuex data store for the admin brew methods
*/
import BrewMethodsAPI from '../../api/admin/brewMethods.js';

export const brewMethods = {
  /*
    Defines the state monitored for the module.
  */
  state: {
    brewMethods: [],
    brewMethodsLoadStatus: 0,

    brewMethod: {},
    brewMethodLoadStatus: 0,

    brewMethodAddStatus: 0,
    brewMethodUpdateStatus: 0
  },

  /*
    Defines the actions that can mutate the state.
  */
  actions: {
    /*
      Loads the brew methods.
    */
    loadAdminBrewMethods( { commit } ){
      commit( 'setAdminBrewMethodsLoadStatus', 1 );

      /*
        Calls the API to load the admin brew methods.
      */
      BrewMethodsAPI.getBrewMethods()
        .then( function( response ){
          /*
            Commit a successful response with the brew methods.
          */
          commit( 'setAdminBrewMethods', response.data );
          commit( 'setAdminBrewMethodsLoadStatus', 2 );
        })
        .catch( function(){
          /*
            Commit a failed response and clear the data.
          */
          commit( 'setAdminBrewMethods', [] );
          commit( 'setAdminBrewMethodsLoadStatus', 3 );
        });
    },

    /*
      Loads a brew method.
    */
    loadAdminBrewMethod( { commit }, data ){
      commit( 'setAdminBrewMethodLoadStatus', 1 );

      /*
        Calls the API to load the brew method.
      */
      BrewMethodsAPI.getBrewMethod( data.id )
        .then( function( response ){
          /*
            Commits a successful response with the brew method.
          */
          commit( 'setAdminBrewMethod', response.data );
          commit( 'setAdminBrewMethodLoadStatus', 2 );
        })
        .catch( function(){
          /*
            Commits a failed response and clear the data.
          */
          commit( 'setAdminBrewMethod', {} );
          commit( 'setAdminBrewMethodLoadStatus', 3 );
        });
    },

    /*
      Updates a brew method.
    */
    updateAdminBrewMethod( { commit }, data ){
      commit( 'setAdminBrewMethodUpdateStatus', 1 );

      /*
        Calls the API to update a brew method.
      */
      BrewMethodsAPI.putUpdateBrewMethod( data.id, data.method, data.icon )
        .then( function( response ){
          /*
            Commits a successful response.
          */
          commit( 'setAdminBrewMethod', response.data );
          commit( 'setAdminBrewMethodUpdateStatus', 2 );
        })
        .catch( function(){
          /*
            Commits a failed response.
          */
          commit( 'setAdminBrewMethod', {} );
          commit( 'setAdminBrewMethodUpdateStatus', 3 );
        });
    },

    /*
      Adds a brew method.
    */
    addAdminBrewMethod( { commit, state, dispatch }, data ){
      commit( 'setAdminBrewMethodAddedStatus', 1 );

      /*
        Calls the API to add a brew method.
      */
      BrewMethodsAPI.postAddBrewMethod( data.method, data.icon )
        .then( function( response ){
          commit( 'setAdminBrewMethodAddedStatus', 2 );
          dispatch( 'loadAdminBrewMethods');
        })
        .catch( function(){
          commit( 'setAdminBrewMethodAddedStatus', 3 );
        });
    }
  },

  /*
    Defines the mutations used by the Vuex module.
  */
  mutations: {
    /*
      Sets the admin brew methods load status.
    */
    setAdminBrewMethodsLoadStatus( state, status ){
      state.brewMethodsLoadStatus = status;
    },

    /*
      Sets the admin brew methods.
    */
    setAdminBrewMethods( state, methods ){
      state.brewMethods = methods;
    },

    /*
      Set the brew method load status.
    */
    setAdminBrewMethodLoadStatus( state, status ){
      state.brewMethodLoadStatus = status;
    },

    /*
      Sets the admin brew method.
    */
    setAdminBrewMethod( state, method ){
      state.brewMethod = method;
    },

    /*
      Sets the admin brew method update status.
    */
    setAdminBrewMethodUpdateStatus( state, status ){
      state.brewMethodUpdateStatus = status;
    },

    /*
      Sets the admin brew method add status.
    */
    setAdminBrewMethodAddedStatus( state, status ){
      state.brewMethodAddStatus = status;
    }
  },

  /*
    Defines the getters used by the Vuex module.
  */
  getters: {
    /*
      Gets the admin brew method load status
    */
    getAdminBrewMethodLoadStatus( state ){
      return state.brewMethodsLoadStatus;
    },

    /*
      Gets the admin brew methods
    */
    getAdminBrewMethods( state ){
      return state.brewMethods;
    },

    /*
      Gets the admin brew method load status.
    */
    getAdminBrewMethodLoadStatus( state ){
      return state.brewMethodLoadStatus;
    },

    /*
      Gets the admin brew method.
    */
    getAdminBrewMethod( state ){
      return state.brewMethod;
    },

    /*
      Gets the admin brew method update status.
    */
    getAdminBrewMethodUpdateStatus( state ){
      return state.brewMethodUpdateStatus;
    },

    /*
      Gets the admin brew method added status.
    */
    getAdminBrewMethodAddedStatus( state ){
      return state.brewMethodAddStatus;
    }
  }
}
