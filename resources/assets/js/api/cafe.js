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
		GET 	/api/v1/cafes/{slug}
	*/
	getCafe: function( slug ){
		return axios.get( ROAST_CONFIG.API_URL + '/cafes/' + slug );
	},

	/*
		GET 	/api/v1/cafes/{slug}/edit
	*/
	getCafeEdit: function( slug ){
		return axios.get( ROAST_CONFIG.API_URL + '/cafes/' + slug + '/edit' );
	},

	/*
		POST 	/api/v1/cafes
	*/
	postAddNewCafe: function( companyName, companyID, companyType, subscription, website, instagramURL, facebookURL, twitterURL, locationName, address, city, state, zip, lat, lng, brewMethods, matcha, tea ){
		/*
			Initialize the form data
		*/
		let formData = new FormData();

		/*
			Add the form data we need to submit
		*/
		formData.append('company_name', companyName);
		formData.append('company_id', companyID);
		formData.append('company_type', companyType);
		formData.append('subscription', subscription);
		formData.append('website', website);
		formData.append('instagram_url', instagramURL);
		formData.append('twitter_url', twitterURL);
		formData.append('facebook_url', facebookURL);
		formData.append('location_name', locationName);
		formData.append('address', address);
		formData.append('city', city);
		formData.append('state', state);
		formData.append('zip', zip);
		formData.append('lat', lat);
		formData.append('lng', lng);
		formData.append('brew_methods', JSON.stringify( brewMethods ) );
		formData.append('matcha', matcha);
		formData.append('tea', tea);

		return axios.post( ROAST_CONFIG.API_URL + '/cafes',
			formData,
			{
		    headers: {
		        'Content-Type': 'multipart/form-data'
		    }
		  }
		);
	},

	/*
	  PUT 	/api/v1/cafes/{slug}
	*/
	putEditCafe: function( slug, companyName, companyID, companyType, subscription, website, instagramURL, facebookURL, twitterURL, locationName, address, city, state, zip, lat, lng, brewMethods, matcha, tea ){
		/*
			Initialize the form data
		*/
		let formData = new FormData();

		/*
			Add the form data we need to submit
		*/
		formData.append('company_name', companyName);
		formData.append('company_id', companyID);
		formData.append('company_type', companyType);
		formData.append('subscription', subscription);
		formData.append('website', website);
		formData.append('instagram_url', instagramURL);
		formData.append('twitter_url', twitterURL);
		formData.append('facebook_url', facebookURL);
		formData.append('location_name', locationName);
		formData.append('address', address);
		formData.append('city', city);
		formData.append('state', state);
		formData.append('zip', zip);
		formData.append('lat', lat);
		formData.append('lng', lng);
		formData.append('brew_methods', JSON.stringify( brewMethods ) );
		formData.append('matcha', matcha);
		formData.append('tea', tea);
		formData.append('_method', 'PUT');

		return axios.post( ROAST_CONFIG.API_URL + '/cafes/'+slug,
			formData
	  );
	},

	/*
		POST 	/api/v1/cafes/{slug}/like
	*/
	postLikeCafe: function( slug ){
		return axios.post( ROAST_CONFIG.API_URL + '/cafes/' + slug + '/like' );
	},

	/*
		DELETE /api/v1/cafes/{slug}/like
	*/
	deleteLikeCafe: function( slug ){
		return axios.delete( ROAST_CONFIG.API_URL + '/cafes/' + slug + '/like' );
	},

	deleteCafe: function( slug ){
		return axios.delete( ROAST_CONFIG.API_URL + '/cafes/' + slug );
	}
}
