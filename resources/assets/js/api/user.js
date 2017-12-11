/*
	Imports the Roast API URL from the config.
*/
import { ROAST_CONFIG } from '../config.js';

export default {
  /*
    GET   /api/v1/user
  */
  getUser: function(){
    return axios.get( ROAST_CONFIG.API_URL + '/user' );
  },

  /*
    PUT  /api/v1/user
  */
  putUpdateUser: function( public_visibility, favorite_coffee, flavor_notes, city, state ){
    return axios.put( ROAST_CONFIG.API_URL + '/user',
      {
        public_visibility: public_visibility,
        favorite_coffee: favorite_coffee,
        flavor_notes: flavor_notes,
        city: city,
        state: state
      }
    );
  }
}
