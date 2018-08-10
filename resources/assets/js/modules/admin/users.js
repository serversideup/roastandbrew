/*
|-------------------------------------------------------------------------------
| VUEX modules/admin/users.js
|-------------------------------------------------------------------------------
| The Vuex data store for the admin users
*/
import UsersAPI from '../../api/admin/users.js';

export const users = {
  /*
    Defines the state monitored for the module.
  */
  state: {
    users: [],
    usersLoadStatus: 0,

    user: {},
    userLoadStatus: 0,

    userUpdateStatus: 0
  },

  /*
    Define the actions that can mutate the state.
  */
  actions: {
    /*
      Loads the users.
    */
    loadAdminUsers( { commit } ){
      commit( 'setAdminUsersLoadStatus', 1 );

      /*
        Calls the API to load the admin users.
      */
      UsersAPI.getUsers()
        .then( function( response ){
          /*
            Commit a successful response with the users.
          */
          commit( 'setAdminUsers', response.data );
          commit( 'setAdminUsersLoadStatus', 2 );
        })
        .catch( function(){
          /*
            Commit a failed response and clear the data.
          */
          commit( 'setAdminUsers', [] );
          commit( 'setAdminUsersLoadStatus', 3 );
        });
    },

    /*
      Loads a user.
    */
    loadAdminUser( { commit }, data ){
      commit( 'setAdminUserLoadStatus', 1 );

      /*
        Calls the API to load the admin user.
      */
      UsersAPI.getUser( data.id )
        .then( function( response ){
          /*
            Commits a successful response with the user.
          */
          commit( 'setAdminUser', response.data );
          commit( 'setAdminUserLoadStatus', 2 );
        })
        .catch( function(){
          /*
            Commit a failed response and clear the data.
          */
          commit( 'setAdminUser', {} );
          commit( 'setAdminUserLoadStatus', 3 );
        });
    },

    /*
      Updates a user.
    */
    updateAdminUser( { commit }, data ){
      commit( 'setAdminUserUpdateStatus', 1 );

      /*
        Calls the API to update the admin user.
      */
      UsersAPI.putUpdateUser( data.id, data.permission, data.companies )
        .then( function( response ){
          commit( 'setAdminUser', response.data );
          commit( 'setAdminUserUpdateStatus', 2 );
        })
        .catch( function(){
          /*
            Commit a failed response.
          */
          commit( 'setAdminUserUpdateStatus', 3 );
        });
    }
  },

  /*
    Defines the mutations used by the Vuex module.
  */
  mutations: {
    /*
      Sets the users load status.
    */
    setAdminUsersLoadStatus( state, status ){
      state.usersLoadStatus = status;
    },

    /*
      Sets the users
    */
    setAdminUsers( state, users ){
      state.users = users;
    },

    /*
      Sets the user load status.
    */
    setAdminUserLoadStatus( state, status ){
      state.userLoadStatus = status;
    },

    /*
      Sets the user.
    */
    setAdminUser( state, user ){
      state.user = user;
    },

    /*
      Sets the admin user update status.
    */
    setAdminUserUpdateStatus( state, status ){
      state.userUpdateStatus = status;
    }
  },

  /*
    Defines the getters used by the Vuex module.
  */
  getters: {
    /*
      Returns the users.
    */
    getAdminUsers( state ){
      return state.users;
    },

    /*
      Return the users load status.
    */
    getAdminUsersLoadStatus( state ){
      return state.usersLoadStatus;
    },

    /*
      Return the user.
    */
    getAdminUser( state ){
      return state.user;
    },

    /*
      Return the user load status.
    */
    getAdminUserLoadStatus( state ){
      return state.userLoadStatus;
    },

    /*
      Return the user update status.
    */
    getAdminUserUpdateStatus( state ){
      return state.userUpdateStatus;
    }
  }
}
