/*
  Imports the Roast API URL from the config.
*/
import { ROAST_CONFIG } from '../../config.js';

export default {
  /*
    GET   /api/v1/admin/cities
  */
  getCities: function(){
    return axios.get( ROAST_CONFIG.API_URL + '/admin/cities' );
  },

  /*
    GET   /api/v1/admin/cities/{id}
  */
  getCity: function( id ){
    return axios.get( ROAST_CONFIG.API_URL + '/admin/cities/'+id );
  },

  /*
    POST  /api/v1/admin/cities
  */
  postAddCity: function( name, state, country, latitude, longitude, radius ){
    return axios.post( ROAST_CONFIG.API_URL + '/admin/cities', {
      name: name,
      state: state,
      country: country,
      latitude: latitude,
      longitude: longitude,
      radius: radius
    });
  },

  /*
    PUT   /api/v1/admin/cities/{id}
  */
  putUpdateCity( id, name, state, country, latitude, longitude, radius ){
    return axios.put( ROAST_CONFIG.API_URL + '/admin/cities/'+id, {
      name: name,
      state: state,
      country: country,
      latitude: latitude,
      longitude: longitude,
      radius: radius
    });
  },

  /*
    DELETE /api/v1/admin/cities/{id}
  */
  deleteCity( id ){
    return axios.delete( ROAST_CONFIG.API_URL + '/admin/cities/'+id );
  }
}
