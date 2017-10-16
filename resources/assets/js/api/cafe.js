/*
	Imports the Roast API URL from the config.
*/
import { ROAST_CONFIG } from '../config.js';

export default {
	/*
		GET 	/api/v1/cafes
	*/
	getCafes: function(){
		return axios.get( ROAST_CONFIG.API_URL + '/cafes' );
	},

	/*
		GET 	/api/v1/cafes/{cafeID}
	*/
	getCafe: function( cafeID ){
		return axios.get( ROAST_CONFIG.API_URL + '/cafes/' + cafeID );
	},

	/*
		POST 	/api/v1/cafes
	*/
	postAddNewCafe: function( name, address, city, state, zip ){
		return axios.post( ROAST_CONFIG.API_URL + '/cafes',
			{
				name: name,
				address: address,
        city: city,
        state: state,
        zip: zip
			}
		);
	}
}
