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
    cafeLoadStatus: 0,

		cafeLiked: false,

		cafeAddStatus: 0,
		cafeLikeActionStatus: 0,
		cafeUnlikeActionStatus: 0
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

      CafeAPI.getCafes()
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
			commit( 'setCafeLikedStatus', false );
      commit( 'setCafeLoadStatus', 1 );

      CafeAPI.getCafe( data.id )
        .then( function( response ){
          commit( 'setCafe', response.data );
					if( response.data.user_like.length > 0 ){
						commit('setCafeLikedStatus', true);
					}
          commit( 'setCafeLoadStatus', 2 );
        })
        .catch( function(){
          commit( 'setCafe', {} );
          commit( 'setCafeLoadStatus', 3 );
        });
    },

		/*
			Adds a cafe
		*/
		addCafe( { commit, state, dispatch }, data ){
			commit( 'setCafeAddedStatus', 1 );

			CafeAPI.postAddNewCafe( data.name, data.locations, data.website, data.description, data.roaster )
					.then( function( response ){
						commit( 'setCafeAddedStatus', 2 );
						dispatch( 'loadCafes' );
					})
					.catch( function(){
						commit( 'setCafeAddedStatus', 3 );
					});
		},

		/*
			Likes a cafe
		*/
		likeCafe( { commit, state }, data ){
			commit( 'setCafeLikeActionStatus', 1 );

			CafeAPI.postLikeCafe( data.id )
				.then( function( response ){
					commit( 'setCafeLikedStatus', true );
					commit( 'setCafeLikeActionStatus', 2 );
				})
				.catch( function(){
					commit( 'setCafeLikeActionStatus', 3 );
				});
		},

		/*
			Unlikes a cafe
		*/
		unlikeCafe( { commit, state }, data ){
			commit( 'setCafeUnlikeActionStatus', 1 );

			CafeAPI.deleteLikeCafe( data.id )
				.then( function( response ){
					commit( 'setCafeLikedStatus', false );
					commit( 'setCafeUnlikeActionStatus', 2 );
				})
				.catch( function(){
					commit( 'setCafeUnlikeActionStatus', 3 );
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
    },

		/*
			Set the cafe add status
		*/
		setCafeAddedStatus( state, status ){
			state.cafeAddStatus = status;
		},

		/*
			Set the cafe liked status
		*/
		setCafeLikedStatus( state, status ){
			state.cafeLiked = status;
		},

		/*
			Set the cafe like action status
		*/
		setCafeLikeActionStatus( state, status ){
			state.cafeLikeActionStatus = status;
		},

		/*
			Set the cafe unlike action status
		*/
		setCafeUnlikeActionStatus( state, status ){
			state.cafeUnlikeActionStatus = status;
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
    },

		/*
			Gets the cafe add status
		*/
		getCafeAddStatus( state ){
			return state.cafeAddStatus;
		},

		/*
			Gets the cafe liked status
		*/
		getCafeLikedStatus( state ){
			return state.cafeLiked;
		},

		/*
			Gets the cafe liked action status
		*/
		getCafeLikeActionStatus( state ){
			return state.cafeLikeActionStatus;
		},

		/*
			Gets the cafe un-like action status
		*/
		getCafeUnlikeActionStatus( state ){
			return state.cafeUnlikeActionStatus;
		}
	}
}
