/*
  Imports the Roast API URL from the config.
*/
import { ROAST_CONFIG } from '../../config.js';

export default {
  /*
    GET   /api/v1/admin/actions
  */
  getActions: function(){
    return axios.get( ROAST_CONFIG.API_URL + '/admin/actions' );
  },

  /*
    PUT   /admin/v1/admin/actions/{actionID}/approve
  */
  putApproveAction: function( id ){
    return axios.put( ROAST_CONFIG.API_URL + '/admin/actions/'+id+'/approve' );
  },

  /*
    PUT   /admin/v1/admin/actions/{actionID}/deny
  */
  putDenyAction: function( id ){
    return axios.put( ROAST_CONFIG.API_URL + '/admin/actions/'+id+'/deny' );
  }
}
