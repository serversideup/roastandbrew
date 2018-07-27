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
		cafeEditText: '',

		cafeLiked: false,

		cafeAdded: {},
		cafeAddStatus: 0,
		cafeAddText: '',

		cafeLikeActionStatus: 0,
		cafeUnlikeActionStatus: 0,

		cafeDeletedStatus: 0,
		cafeDeleteText: '',

		cafesView: 'map'
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

      CafeAPI.getCafe( data.slug )
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

			CafeAPI.getCafeEdit( data.slug )
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

			CafeAPI.putEditCafe( data.slug, data.company_name, data.company_id, data.company_type, data.website, data.location_name, data.address, data.city, data.state, data.zip, data.lat, data.lng, data.brew_methods, data.matcha, data.tea )
					.then( function( response ){
						if( typeof response.data.cafe_updates_pending !== 'undefined' ){
							commit( 'setCafeEditText', response.data.cafe_updates_pending +' updates are pending!');
						}else{
							commit( 'setCafeEditText', response.company.name+' has been successfully updated!');
						}

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
			CafeAPI.postAddNewCafe( data.company_name, data.company_id, data.company_type, data.website, data.location_name, data.address, data.city, data.state, data.zip, data.lat, data.lng, data.brew_methods, data.matcha, data.tea )
					.then( function( response ){
						if( typeof response.data.cafe_add_pending !== 'undefined' ){
							commit( 'setCafeAddedText', response.data.cafe_add_pending +' is pending approval!');
						}else{
							commit( 'setCafeAddedText', response.data.name +' has been added!');
						}

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

			CafeAPI.postLikeCafe( data.slug )
				.then( function( response ){
					commit( 'setCafeLikedStatus', true );
					commit( 'setCafeLikeActionStatus', 2 );
					dispatch( 'loadCafe', { slug: data.slug } );

					commit( 'updateCafeLikedStatus', { slug: data.slug, count: 1 });
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

			CafeAPI.deleteLikeCafe( data.slug )
				.then( function( response ){
					commit( 'setCafeLikedStatus', false );
					commit( 'setCafeUnlikeActionStatus', 2 );
					dispatch( 'loadCafe', { slug: data.slug } );

					commit( 'updateCafeLikedStatus', { slug: data.slug, count: 0 });
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

			CafeAPI.deleteCafe( data.slug )
				.then( function( response ){
					if( typeof response.data.cafe_delete_pending !== 'undefined' ){
						commit( 'setCafeDeletedText', response.data.cafe_delete_pending +' is pending deletion!');
					}else{
						commit( 'setCafeDeletedText', 'Cafe has been successfully deleted!');
					}

					commit( 'setCafeDeleteStatus', 2 );
					dispatch( 'loadCafes' );
				})
				.catch( function(){
					commit( 'setCafeDeleteStatus', 3 );
				});
		},

		changeCafesView( { commit, state, dispatch }, view ){
			commit( 'setCafesView', view );
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
			Sets the cafe edit text
		*/
		setCafeEditText( state, text ){
			state.cafeEditText = text;
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
			Set the cafe add text
		*/
		setCafeAddedText( state, text ){
			state.cafeAddText = text;
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
				if( state.cafes[i].slug == data.slug ){
					state.cafes[i].user_like_count = data.count;
				}
			}
		},

		setCafeDeleteStatus( state, status ){
			state.cafeDeletedStatus = status;
		},

		setCafeDeletedText( state, text ){
			state.cafeDeleteText = text;
		},

		setCafesView( state, view ){
			state.cafesView = view
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
			Gets the cafe edit text
		*/
		getCafeEditText( state ){
			return state.cafeEditText;
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
			Gets the cafe add text
		*/
		getCafeAddText( state ){
			return state.cafeAddText;
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
		},

		getCafeDeletedText( state ){
			return state.cafeDeleteText;
		},

		getCafesView( state ){
			return state.cafesView;
		}
	}
}
