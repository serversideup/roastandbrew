/*
  Imports the Roast API URL from the config.
*/
import { ROAST_CONFIG } from '../config.js';

export default {
  /*
    GET   /api/v1/cities
  */
  getCities: function(){
    return axios.get( ROAST_CONFIG.API_URL + '/cities' );
  },

  /*
    GET   /api/v1/cities/{slug}
  */
  getCity: function( slug ){
    return axios.get( ROAST_CONFIG.API_URL + '/cities/'+slug );
  }
}
