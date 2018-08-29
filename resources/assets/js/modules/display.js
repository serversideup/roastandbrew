/*
|-------------------------------------------------------------------------------
| VUEX modules/display.js
|-------------------------------------------------------------------------------
| The Vuex data store for the display state
*/
export const display = {
  /*
    Defines the state being monitored for the module
  */
  state: {
    showFilters: true,
    showPopOut: false,
    zoomLevel: '',
    lat: 0.0,
    lng: 0.0
  },

  /*
    Defines the actions that can be performed on the state.
  */
  actions: {
    /*
      Toggles the showing and hiding of filters.
    */
    toggleShowFilters( { commit }, data ){
      commit( 'setShowFilters', data.showFilters );
    },

    /*
      Toggles the showing and hiding of the popout.
    */
    toggleShowPopOut( { commit }, data ){
      commit( 'setShowPopOut', data.showPopOut );
    },

    /*
      Applies the zoom level.
    */
    applyZoomLevel( { commit }, data ){
      commit( 'setZoomLevel', data );
    },

    /*
      Applies the latitude.
    */
    applyLat( { commit }, data ){
      commit( 'setLat', data );
    },

    /*
      Applies the longitude.
    */
    applyLng( { commit }, data ){
      commit( 'setLng', data );
    }
  },

  /*
    Defines the mutations used by the state.
  */
  mutations: {
    /*
      Sets the state to show or hide the filters.
    */
    setShowFilters( state, show ){
      state.showFilters = show;
    },

    /*
      Sets the state to show or hide the pop out.
    */
    setShowPopOut( state, show ){
      state.showPopOut = show;
    },

    /*
      Sets the zoom level
    */
    setZoomLevel( state, level ){
      state.zoomLevel = level;
    },

    /*
      Sets the lat
    */
    setLat( state, lat ){
      state.lat = lat;
    },

    /*
      Sets the lng
    */
    setLng( state, lng ){
      state.lng = lng;
    }
  },

  /*
    Defines the getters on the Vuex module.
  */
  getters: {
    /*
      Returns whether or not the filters are shown or hidden.
    */
    getShowFilters( state ){
      return state.showFilters;
    },

    /*
      Returns whether or not the pop out is shown or hidden.
    */
    getShowPopOut( state ){
      return state.showPopOut;
    },

    /*
      Gets the zoom level
    */
    getZoomLevel( state ){
      return state.zoomLevel;
    },

    /*
      Gets the latitude
    */
    getLat( state ){
      return state.lat;
    },

    /*
      Gets the longitude
    */
    getLng( state ){
      return state.lng;
    }
  }
}
