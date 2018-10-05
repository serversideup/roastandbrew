/*
|-------------------------------------------------------------------------------
| VUEX modules/admin/cities.js
|-------------------------------------------------------------------------------
| The Vuex data store for the admin cities
*/
import CitiesAPI from '../../api/admin/cities.js';

export const cities = {
  /*
    Defines the state monitored for the module.
  */
  state: {
    cities: [],
    citiesLoadStatus: 0,

    city: {},
    cityLoadStatus: 0,

    cityEditStatus: 0,
    cityAddStatus: 0,
    cityDeleteStatus: 0
  },

  /*
    Defines the actions that can mutate the state.
  */
  actions: {
    /*
      Loads all of the cities.
    */
    loadAdminCities( { commit } ){
      commit( 'setAdminCitiesLoadStatus', 1 );

      /*
        Call the admin cities API route.
      */
      CitiesAPI.getCities()
               .then( function( response ){
                  /*
                    Commits a successful response with the cities.
                  */
                  commit( 'setAdminCities', response.data );
                  commit( 'setAdminCitiesLoadStatus', 2 );
               })
               .catch( function(){
                  /*
                    Commits a failed response and clear the data.
                  */
                  commit( 'setAdminCities', [] );
                  commit( 'setAdminCitiesLoadStatus', 3 );
               });
    },

    /*
      Load an individual city.
    */
    loadAdminCity( { commit }, data ){
      commit( 'setAdminCityLoadStatus', 1 );

      /*
        Calls the API to load an individual city.
      */
      CitiesAPI.getCity( data.id )
               .then( function( response ){
                 commit( 'setAdminCity', response.data );
                 commit( 'setAdminCityLoadStatus', 2 );
               })
               .catch( function(){
                 commit( 'setAdminCity', {} );
                 commit( 'setAdminCityLoadStatus', 3 );
               });
    },

    /*
      Submits a request to add a city.
    */
    addAdminCity( { commit, state, dispatch }, data ){
      commit( 'setAdminCityAddStatus', 1 );

      /*
        Calls the API to add a city.
      */
      CitiesAPI.postAddCity( data.name, data.state, data.country, data.latitude, data.longitude, data.radius )
               .then( function( response ){
                 commit( 'setAdminCityAddStatus', 2 );

                 dispatch( 'loadAdminCities' );
               })
               .catch( function( response ){
                 commit( 'setAdminCityAddStatus', 3 );
               });
    },

    /*
      Update an individual admin city.
    */
    updateAdminCity( { commit, state, dispatch }, data ){
      commit( 'setAdminCityEditStatus', 1 );

      /*
        Calls the API to update an individual city.
      */
      CitiesAPI.putUpdateCity( data.id, data.name, data.state, data.country, data.latitude, data.longitude, data.radius )
              .then( function( response ){
                commit( 'setAdminCityEditStatus', 2 );
              })
              .catch( function( response ){
                commit( 'setAdminCityEditStatus', 3 );
              });
    },

    /*
      Deletes a city.
    */
    deleteAdminCity( {commit, state, dispatch}, data ){
      commit( 'setAdminCityDeleteStatus', 1 );

      CitiesAPI.deleteCity( data.id )
               .then( function( response ){
                 commit( 'setAdminCityDeleteStatus', 2 );
               })
               .catch( function( response ){
                 commit( 'setAdminCityDeleteStatus', 3 );
               });
    }
  },

  /*
    Defines the mutations used by the Vuex module.
  */
  mutations: {
    /*
      Set the admin cities load status.
    */
    setAdminCitiesLoadStatus( state, status ){
      state.citiesLoadStatus = status;
    },

    /*
      Sets the admin cities.
    */
    setAdminCities( state, cities ){
      state.cities = cities;
    },

    /*
      Set the admin city load status.
    */
    setAdminCityLoadStatus( state, status ){
      state.cityLoadStatus = status;
    },

    /*
      Sets the admin city.
    */
    setAdminCity( state, city ){
      state.city = city;
    },

    /*
      Sets the admin city add status.
    */
    setAdminCityAddStatus( state, status ){
      state.cityAddStatus = status;
    },

    /*
      Sets the admin city edit status.
    */
    setAdminCityEditStatus( state, status ){
      state.cityEditStatus = status;
    },

    /*
      Sets the admin city delete status.
    */
    setAdminCityDeleteStatus( state, status ){
      state.cityDeleteStatus = status;
    }
  },

  /*
    Defines the getters used by the Vuex module.
  */
  getters: {
    /*
      Get all admin cities.
    */
    getAdminCities( state ){
      return state.cities;
    },

    /*
      Gets the admin cities load status.
    */
    getAdminCitiesLoadStatus( state ){
      return state.citiesLoadStatus;
    },

    /*
      Gets the admin city.
    */
    getAdminCity( state ){
      return state.city;
    },

    /*
      Gets the admin city load status.
    */
    getAdminCityLoadStatus( state ){
      return state.cityLoadStatus;
    },

    /*
      Gets the admin city edit status.
    */
    getAdminCityEditStatus( state ){
      return state.cityEditStatus;
    },

    /*
      Gets the admin city add status.
    */
    getAdminCityAddStatus( state ){
      return state.cityAddStatus;
    },

    /*
      Gets the admin city delete status.
    */
    getAdminCityDeleteStatus( state ){
      return state.cityDeleteStatus;
    }
  }
}
