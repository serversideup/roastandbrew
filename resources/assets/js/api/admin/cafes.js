/*
  Imports the Roast API URL from the config.
*/
import { ROAST_CONFIG } from '../../config.js';

export default {
  /*
    GET   /api/v1/admin/companies/{companyID}/cafes/{cafeID}
  */
  getCafe: function( companyID, cafeID ){
    return axios.get( ROAST_CONFIG.API_URL + '/admin/companies/' + companyID + '/cafes/' + cafeID );
  },

  /*
	  PUT 	/api/v1/admin/companies/{companyID/cafes/{cafeID}
	*/
	putUpdateCafe: function( companyID, cafeID, cityID, locationName, address, city, state, zip, tea, matcha, brewMethods, deleted ){
		/*
			Initialize the form data
		*/
		let formData = new FormData();

		/*
			Add the form data we need to submit
		*/
		formData.append('company_id', companyID);
    formData.append('city_id', cityID);
		formData.append('location_name', locationName);
		formData.append('address', address);
		formData.append('city', city);
		formData.append('state', state);
		formData.append('zip', zip);
		formData.append('brew_methods', JSON.stringify( brewMethods ) );
		formData.append('matcha', matcha);
		formData.append('tea', tea);
    formData.append('deleted', deleted);
		formData.append('_method', 'PUT');

		return axios.post( ROAST_CONFIG.API_URL + '/admin/companies/'+ companyID + '/cafes/' + cafeID,
			formData
	  );
	},
}
