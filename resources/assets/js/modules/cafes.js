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

		cafeEdit: {},
		cafeEditLoadStatus: 0,
		cafeEditStatus: 0,

		cafeLiked: false,

		cafeAdded: {},
		cafeAddStatus: 0,
		cafeLikeActionStatus: 0,
		cafeUnlikeActionStatus: 0,

		cafeDeletedStatus: 0,
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
					if( response.data.user_like_count > 0 ){
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
			Loads a cafe to edit from the API
		*/
		loadCafeEdit( { commit }, data ){
			commit( 'setCafeEditLoadStatus', 1 );

			CafeAPI.getCafeEdit( data.id )
				.then( function( response ){
					commit( 'setCafeEdit', response.data );
					commit( 'setCafeEditLoadStatus', 2 );
				})
				.catch( function(){
					commit( 'setCafeEdit', {} );
					commit( 'setCafeEditLoadStatus', 3 );
				});
		},

		/*
			Edits a cafe
		*/
		editCafe( { commit, state, dispatch }, data ){
			commit( 'setCafeEditStatus', 1 );

			CafeAPI.putEditCafe( data.id, data.company_name, data.company_id, data.company_type, data.website, data.location_name, data.address, data.city, data.state, data.zip, data.lat, data.lng, data.brew_methods )
					.then( function( response ){
						commit( 'setCafeEditStatus', 2 );
						dispatch( 'loadCafes' );
					})
					.catch( function(){
						commit( 'setCafeEditStatus', 3 );
					});
		},

		/*
			Adds a cafe
		*/
		addCafe( { commit, state, dispatch }, data ){
			commit( 'setCafeAddedStatus', 1 );
			CafeAPI.postAddNewCafe( data.company_name, data.company_id, data.company_type, data.website, data.location_name, data.address, data.city, data.state, data.zip, data.lat, data.lng, data.brew_methods )
					.then( function( response ){
						commit( 'setCafeAddedStatus', 2 );
						commit( 'setCafeAdded', response.data );
						dispatch( 'loadCafes' );
					})
					.catch( function(){
						commit( 'setCafeAddedStatus', 3 );
					});
		},

		/*
			Likes a cafe
		*/
		likeCafe( { commit, state, dispatch }, data ){
			commit( 'setCafeLikeActionStatus', 1 );

			CafeAPI.postLikeCafe( data.id )
				.then( function( response ){
					commit( 'setCafeLikedStatus', true );
					commit( 'setCafeLikeActionStatus', 2 );
					dispatch( 'loadCafe', { id: data.id } );

					commit( 'updateCafeLikedStatus', { id: data.id, count: 1 });
				})
				.catch( function(){
					commit( 'setCafeLikeActionStatus', 3 );
				});
		},

		/*
			Unlikes a cafe
		*/
		unlikeCafe( { commit, state, dispatch }, data ){
			commit( 'setCafeUnlikeActionStatus', 1 );

			CafeAPI.deleteLikeCafe( data.id )
				.then( function( response ){
					commit( 'setCafeLikedStatus', false );
					commit( 'setCafeUnlikeActionStatus', 2 );
					dispatch( 'loadCafe', { id: data.id } );

					commit( 'updateCafeLikedStatus', { id: data.id, count: 0 });
				})
				.catch( function(){
					commit( 'setCafeUnlikeActionStatus', 3 );
				});
		},

		clearLikeAndUnlikeStatus( { commit }, data ){
			commit( 'setCafeLikeActionStatus', 0 );
			commit( 'setCafeUnlikeActionStatus', 0 );
		},

		deleteCafe( { commit, state, dispatch }, data ){
			commit( 'setCafeDeleteStatus', 1 );

			CafeAPI.deleteCafe( data.cafe_id )
				.then( function( response ){
					commit( 'setCafeDeleteStatus', 2 );

					dispatch( 'loadCafes' );
				})
				.catch( function(){
					commit( 'setCafeDeleteStatus', 3 );
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
			Sets the cafe to be edited
		*/
		setCafeEdit( state, cafe ){
			state.cafeEdit = cafe;
		},

		/*
			Sets the cafe edit status
		*/
		setCafeEditStatus( state, status ){
			state.cafeEditStatus = status;
		},

		/*
			Sets the cafe edit load status
		*/
		setCafeEditLoadStatus( state, status ){
			state.cafeEditLoadStatus = status;
		},

		/*
			Set the added cafe.
		*/
		setCafeAdded( state, cafe ){
			state.cafeAdded = cafe;
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
		},

		/*
			Update a loaded cafe's like status.
		*/
		updateCafeLikedStatus( state, data ){
			for( var i = 0; i < state.cafes.length; i++ ){
				if( state.cafes[i].id == data.id ){
					state.cafes[i].user_like_count = data.count;
				}
			}
		},

		setCafeDeleteStatus( state, status ){
			state.cafeDeletedStatus = status;
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
			Gets the cafe we are editing
		*/
		getCafeEdit( state ){
			return state.cafeEdit;
		},

		/*
			Gets the cafe edit status
		*/
		getCafeEditStatus( state ){
			return state.cafeEditStatus;
		},

		/*
			Gets the cafe edit load status
		*/
		getCafeEditLoadStatus( state ){
			return state.cafeEditLoadStatus;
		},

		/*
			Gets the added cafe.
		*/
		getAddedCafe( state ){
			return state.cafeAdded;
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
		},

		getCafeDeletedStatus( state ){
			return state.cafeDeletedStatus;
		}
	}
}
