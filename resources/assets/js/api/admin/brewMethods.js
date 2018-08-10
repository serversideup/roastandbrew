/*
  Imports the Roast API URL from the config.
*/
import { ROAST_CONFIG } from '../../config.js';

export default {
  /*
    GET   /api/v1/admin/brew-methods
  */
  getBrewMethods: function(){
    return axios.get( ROAST_CONFIG.API_URL + '/admin/brew-methods' );
  },

  /*
    GET   /api/v1/admin/brew-methods/{method}
  */
  getBrewMethod: function( id ){
    return axios.get( ROAST_CONFIG.API_URL + '/admin/brew-methods/' + id );
  },

  /*
    POST  /api/v1/admin/brew-methods
  */
  postAddBrewMethod: function( method, icon ){
    return axios.post( ROAST_CONFIG.API_URL + '/admin/brew-methods', {
      method: method,
      icon: icon
    });
  },

  /*
    PUT   /api/v1/admin/brew-methods/{method}
  */
  putUpdateBrewMethod: function( id, method, icon ){
    return axios.put( ROAST_CONFIG.API_URL + '/admin/brew-methods/'+id, {
      method: method,
      icon: icon
    });
  }
}
