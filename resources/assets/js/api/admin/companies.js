/*
  Imports the Roast API URL from the config.
*/
import { ROAST_CONFIG } from '../../config.js';

export default {
  /*
    GET   /api/v1/admin/companies
  */
  getCompanies: function(){
    return axios.get( ROAST_CONFIG.API_URL + '/admin/companies' );
  },

  /*
    GET   /api/v1/admin/companies/{id}
  */
  getCompany: function( id ){
    return axios.get( ROAST_CONFIG.API_URL + '/admin/companies/'+id );
  },

  /*
    PUT   /api/v1/admin/companies/{id}
  */
  putUpdateCompany: function( id, name, type, website, instagramURL, facebookURL, twitterURL, subscription, owners, deleted ){
    return axios.put( ROAST_CONFIG.API_URL + '/admin/companies/'+id, {
      name: name,
      type: type,
      website: website,
      instagram_url: instagramURL,
      facebook_url: facebookURL,
      twitter_url: twitterURL,
      subscription: subscription,
      owners: owners,
      deleted: deleted
    });
  }
}
