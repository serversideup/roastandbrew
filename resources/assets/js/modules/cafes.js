/*
|-------------------------------------------------------------------------------
| VUEX modules/cafes.js
|-------------------------------------------------------------------------------
| The Vuex data store for the cafes
*/

import CafeAPI from '../api/cafe.js';

export const cafes = {
  /*
    Defines the state being monitored for the module.
  */
	state: {
		cafes: [],
    cafesLoadStatus: 0,

    cafe: {},
    cafeLoadStatus: 0
	},

  /*
    Defines the actions used to retrieve the data.
  */
	actions: {
    /*
      Loads the cafes from the API
    */
		loadCafes( { commit } ){
      commit( 'setCafesLoadStatus', 1 );

      CafeAPI.loadCafes()
        .then( function( response ){
          commit( 'setCafes', response.data );
          commit( 'setCafesLoadStatus', 2 );
        })
        .catch( function(){
          commit( 'setCafes', [] );
          commit( 'setCafesLoadStatus', 3 );
        });
    },

    /*
      Loads an individual cafe from the API
    */
    loadCafe( { commit }, data ){
      commit( 'setCafeLoadStatus', 1 );

      CafeAPI.loadCafe( data.id )
        .then( function( response ){
          commit( 'setCafe', response.data );
          commit( 'setCafeLoadStatus', 2 );
        })
        .catch( function(){
          commit( 'setCafe', {} );
          commit( 'setCafeLoadStatus', 3 );
        });

    }
	},

  /*
    Defines the mutations used
  */
	mutations: {
    /*
      Sets the cafes load status
    */
    setCafesLoadStatus( state, status ){
      state.cafesLoadStatus = status;
    },

    /*
      Sets the cafes
    */
    setCafes( state, cafes ){
      state.cafes = cafes;
    },

    /*
      Set the cafe load status
    */
    setCafeLoadStatus( state, status ){
      state.cafeLoadStatus = status;
    },

    /*
      Set the cafe
    */
    setCafe( state, cafe ){
      state.cafe = cafe;
    }
	},

  /*
    Defines the getters used by the module
  */
	getters: {
    /*
      Returns the cafes load status.
    */
    getCafesLoadStatus( state ){
      return state.cafesLoadStatus;
    },

    /*
      Returns the cafes.
    */
    getCafes( state ){
      return state.cafes;
    },

    /*
      Returns the cafes load status
    */
    getCafeLoadStatus( state ){
      return state.cafeLoadStatus;
    },

    /*
      Returns the cafe
    */
    getCafe( state ){
      return state.cafe;
    }
	}
}
