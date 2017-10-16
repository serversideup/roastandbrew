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
  }
}
