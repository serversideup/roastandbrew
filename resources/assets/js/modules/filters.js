/*
|-------------------------------------------------------------------------------
| VUEX modules/filters.js
|-------------------------------------------------------------------------------
| The Vuex data store for the filters state
*/
export const filters = {
  /*
    Defines the state used by the module
  */
  state: {
    cityFilter: '',
    textSearch: '',
    activeLocationFilter: 'all',
    onlyLiked: false,
    brewMethodsFilter: [],
    hasMatcha: false,
    hasTea: false,
    hasSubscription: false,
    orderBy: 'name',
    orderDirection: 'asc'
  },

  /*
    Defines the actions that can be performed on the state.
  */
  actions: {
    /*
      Updates the city filter.
    */
    updateCityFilter( { commit }, data ){
      commit( 'setCityFilter', data );
    },

    /*
      Updates the text search filter
    */
    updateSetTextSearch( { commit }, data ){
      commit( 'setTextSearch', data );
    },

    /*
      Updates the active location filter.
    */
    updateActiveLocationFilter( { commit }, data ){
      commit( 'setActiveLocationFilter', data );
    },

    /*
      Updates the only liked filter.
    */
    updateOnlyLiked( { commit }, data ){
      commit( 'setOnlyLiked', data );
    },

    /*
      Updates the brew methods filter.
    */
    updateBrewMethodsFilter( { commit }, data ){
      commit( 'setBrewMethodsFilter', data );
    },

    /*
      Updates the has matcha filter.
    */
    updateHasMatcha( { commit }, data ){
      commit( 'setHasMatcha', data );
    },

    /*
      Updates the has tea filter.
    */
    updateHasTea( { commit }, data ){
      commit( 'setHasTea', data );
    },

    /*
      Updates the has subscription filter.
    */
    updateHasSubscription( { commit }, data ){
      commit( 'setHasSubscription', data );
    },

    /*
      Updates the order by setting and sorts the cafes.
    */
    updateOrderBy( { commit, state, dispatch }, data ){
      commit( 'setOrderBy', data );
      dispatch( 'orderCafes', { order: state.orderBy, direction: state.orderDirection } );
    },

    /*
      Updates the order direction and sorts the cafes.
    */
    updateOrderDirection( { commit, state, dispatch }, data ){
      commit( 'setOrderDirection', data );
      dispatch( 'orderCafes', { order: state.orderBy, direction: state.orderDirection } );
    },

    /*
      Resets the filters
    */
    resetFilters( { commit }, data ){
      commit( 'resetFilters' );
    }
  },

  /*
    Defines the mutations used by the state.
  */
  mutations: {
    /*
      Sets the city filter.
    */
    setCityFilter( state, city ){
      state.cityFilter = city;
    },

    /*
      Sets the text search filter.
    */
    setTextSearch( state, search ){
      state.textSearch = search;
    },

    /*
      Sets the active location filter.
    */
    setActiveLocationFilter( state, activeLocationFilter ){
      state.activeLocationFilter = activeLocationFilter;
    },

    /*
      Sets the only liked filter.
    */
    setOnlyLiked( state, onlyLiked ){
      state.onlyLiked = onlyLiked;
    },

    /*
      Sets the brew methods filter.
    */
    setBrewMethodsFilter( state, brewMethods ){
      state.brewMethodsFilter = brewMethods;
    },

    /*
      Sets the has matcha filter.
    */
    setHasMatcha( state, matcha ){
      state.hasMatcha = matcha;
    },

    /*
      Sets the has tea filter.
    */
    setHasTea( state, tea ){
      state.hasTea = tea;
    },

    /*
      Sets the has subscription filter.
    */
    setHasSubscription( state, subscription ){
      state.hasSubscription = subscription;
    },

    /*
      Sets the order by filter.
    */
    setOrderBy( state, orderBy ){
      state.orderBy = orderBy;
    },

    /*
      Sets the order direction filter.
    */
    setOrderDirection( state, orderDirection ){
      state.orderDirection = orderDirection;
    },

    /*
      Resets the active filters.
    */
    resetFilters( state ){
      state.cityFilter = '';
      state.textSearch = '';
      state.activeLocationFilter = 'all';
      state.onlyLiked = false;
      state.brewMethodsFilter = [];
      state.hasMatcha = false;
      state.hasTea = false;
      state.hasSubscription = false;
      state.orderBy = 'name';
      state.orderDirection = 'desc';
    }
  },

  /*
    Defines the getters on the Vuex module.
  */
  getters: {
    /*
      Gets the city fitler.
    */
    getCityFilter( state ){
      return state.cityFilter;
    },

    /*
      Gets the text search filter.
    */
    getTextSearch( state ){
      return state.textSearch;
    },

    /*
      Gets the active location filter.
    */
    getActiveLocationFilter( state ){
      return state.activeLocationFilter;
    },

    /*
      Gets the only liked filter.
    */
    getOnlyLiked( state ){
      return state.onlyLiked;
    },

    /*
      Gets the brew methods filter.
    */
    getBrewMethodsFilter( state ){
      return state.brewMethodsFilter;
    },

    /*
      Gets the has matcha filter.
    */
    getHasMatcha( state ){
      return state.hasMatcha;
    },

    /*
      Gets the has tea filter.
    */
    getHasTea( state ){
      return state.hasTea;
    },

    /*
      Gets the has subscription filter.
    */
    getHasSubscription( state ){
      return state.hasSubscription;
    },

    /*
      Gets the order by filter.
    */
    getOrderBy( state ){
      return state.orderBy;
    },

    /*
      Gets the order direction filter.
    */
    getOrderDirection( state ){
      return state.orderDirection;
    }
  }
}
