/*
|-------------------------------------------------------------------------------
| VUEX modules/admin/cafes.js
|-------------------------------------------------------------------------------
| The Vuex data store for the admin companies
*/
import CafesAPI from '../../api/admin/cafes.js';

export const cafes = {
  /*
    Defines the state monitored for the module.
  */
  state: {
    cafe: {},
    cafeLoadStatus: 0,

    cafeEditStatus: 0
  },

  /*
    Defines the actions that can mutate the state.
  */
  actions: {
    /*
      Loads the cafe from the admin side
    */
    loadAdminCafe( { commit }, data ){
      commit( 'setAdminCafeLoadStatus', 1 );

      CafesAPI.getCafe( data.company_id, data.cafe_id )
              .then( function( response ){
                /*
                  Commits a successful response with the cafe.
                */
                commit( 'setAdminCafe', response.data );
                commit( 'setAdminCafeLoadStatus', 2 );
              })
              .catch( function(){
                /*
                  Commit a failed response and clear the data.
                */
                commit( 'setAdminCafe', {} );
                commit( 'setAdminCafeLoadStatus', 3 );
              });
    },

    /*
      Updates an admin cafe
    */
    updateAdminCafe( { commit }, data ){
      commit( 'setAdminCafeEditStatus', 1 );

      /*
        Calls the API to update an admin cafe.
      */
      CafesAPI.putUpdateCafe( data.company_id, data.id, data.city_id, data.location_name, data.address, data.city, data.state, data.zip, data.tea, data.matcha, data.brew_methods, data.deleted )
              .then( function( response ){
                commit( 'setAdminCafe', response.data );
                commit( 'setAdminCafeEditStatus', 2 );
              })
              .catch( function(){
                commit( 'setAdminCafeEditStatus', 3 );
              });
    }
  },

  /*
    Defines the mutations used by the Vuex module.
  */
  mutations: {
    /*
      Sets the cafe load status.
    */
    setAdminCafeLoadStatus( state, status ){
      state.cafeLoadStatus = status;
    },

    /*
      Sets the cafe.
    */
    setAdminCafe( state, cafe ){
      state.cafe = cafe;
    },

    /*
      Sets the cafe edit status
    */
    setAdminCafeEditStatus( state, status ){
      state.cafeEditStatus = status;
    }
  },

  /*
    Defines the getters used by the Vuex module.
  */
  getters: {
    /*
      Returns the cafe load status.
    */
    getAdminCafeLoadStatus( state ){
      return state.cafeLoadStatus;
    },

    /*
      Returns the cafe
    */
    getAdminCafe( state ){
      return state.cafe;
    },

    /*
      Returns the edit status.
    */
    getAdminCafeEditStatus( state ){
      return state.cafeEditStatus;
    }
  }
}
