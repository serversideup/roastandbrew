/*
|-------------------------------------------------------------------------------
| VUEX modules/cities.js
|-------------------------------------------------------------------------------
| The Vuex data store for the cities state
*/
import CitiesAPI from '../api/cities.js';

export const cities = {
  /*
    Defines the state being monitored for the module.
  */
  state: {
    cities: [],
    citiesLoadStatus: 0,

    city: {},
    cityLoadStatus: 0
  },

  /*
    Defines the actions available on the module.
  */
  actions: {
    /*
      Loads all cities.
    */
    loadCities( { commit } ){
      commit('setCitiesLoadStatus', 1);

      /*
        Calls the API to load the cities
      */
      CitiesAPI.getCities()
               .then( function( response ){
                 commit( 'setCities', response.data );
                 commit( 'setCitiesLoadStatus', 2 );
               })
               .catch( function(){
                 commit( 'setCities', [] );
                 commit( 'setCitiesLoadStatus', 3 );
               });
    },

    /*
      Loads an individual city.
    */
    loadCity( { commit }, data ){
      commit( 'setCityLoadStatus', 1 );

      /*
        Calls the API to load an individual city by slug.
      */
      CitiesAPI.getCity( data.slug )
               .then( function( response ){
                 commit( 'setCity', response.data );
                 commit( 'setCityLoadStatus', 2 );
               })
               .catch( function(){
                 commit( 'setCity', {} );
                 commit( 'setCityLoadStatus', 3 );
               });
    }
  },

  /*
    Defines the mutations based on the data store.
  */
  mutations: {
    /*
      Sets the cities in the state.
    */
    setCities( state, cities ){
      state.cities = cities;
    },

    /*
      Sets the cities load status.
    */
    setCitiesLoadStatus( state, status ){
      state.citiesLoadStatus = status;
    },

    /*
      Sets the city
    */
    setCity( state, city ){
      state.city = city;
    },

    /*
      Sets the city load status.
    */
    setCityLoadStatus( state, status ){
      state.cityLoadStatus = status;
    }
  },

  /*
    Defines the getters on the module.
  */
  getters: {
    /*
      Gets the cities
    */
    getCities( state ){
      return state.cities;
    },

    /*
      Gets the cities load status.
    */
    getCitiesLoadStatus( state ){
      return state.citiesLoadStatus;
    },

    /*
      Get the city
    */
    getCity( state ){
      return state.city;
    },

    /*
      Get the city load status.
    */
    getCityLoadStatus( state ){
      return state.cityLoadStatus;
    }
  }
}
