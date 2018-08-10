/*
  Imports the Roast API URL from the config.
*/
import { ROAST_CONFIG } from '../../config.js';

export default {
  /*
    GET   /api/v1/admin/users
  */
  getUsers: function(){
    return axios.get( ROAST_CONFIG.API_URL + '/admin/users' );
  },

  /*
    GET   /api/v1/admin/users/{id}
  */
  getUser: function( id ){
    return axios.get( ROAST_CONFIG.API_URL + '/admin/users/' + id );
  },

  /*
    PUT   /api/v1/admin/users/{id}
  */
  putUpdateUser: function( id, permission, companies ){
    return axios.put( ROAST_CONFIG.API_URL + '/admin/users/' + id, {
      permission: permission,
      companies: companies
    });
  }
}
