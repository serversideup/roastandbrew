/*
|-------------------------------------------------------------------------------
| VUEX modules/users.js
|-------------------------------------------------------------------------------
| The Vuex data store for the users
*/

import UserAPI from '../api/user.js';

export const users = {
  /*
    Defines the state being monitored for the module.
  */
  state: {
    user: {},
    userLoadStatus: 0
  },

  /*
    Defines the actions used to retrieve the data.
  */
  actions: {
    loadUser( { commit } ){
      commit( 'setUserLoadStatus', 1 );

      UserAPI.getUser()
        .then( function( response ){
          commit( 'setUser', response.data );
          commit( 'setUserLoadStatus', 2 );
        })
        .catch( function(){
          commit( 'setUser', {} );
          commit( 'setUserLoadStatus', 3 );
        });
    },

    /*
      Logs out a user and clears the status and user pieces of
      state.
    */
    logoutUser( { commit } ){
      commit( 'setUserLoadStatus', 0 );
      commit( 'setUser', {} );
    }
  },

  /*
    Defines the mutations used
  */
  mutations: {
    /*
      Sets the user load status
    */
    setUserLoadStatus( state, status ){
      state.userLoadStatus = status;
    },

    /*
      Sets the user
    */
    setUser( state, user ){
      state.user = user;
    }
  },

  /*
    Defines the getters used by the module.
  */
  getters: {
    /*
      Returns the user load status.
    */
    getUserLoadStatus( state ){
      return function(){
        return state.userLoadStatus;
      }
    },

    /*
      Returns the user.
    */
    getUser( state ){
      return state.user;
    }
  }
}
