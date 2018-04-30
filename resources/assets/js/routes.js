/*
|-------------------------------------------------------------------------------
| routes.js
|-------------------------------------------------------------------------------
| Contains all of the routes for the application
*/

/*
	Imports Vue and VueRouter to extend with the routes.
*/
import Vue from 'vue'
import VueRouter from 'vue-router'

import store from './store.js';

/*
	Extends Vue to use Vue Router
*/
Vue.use( VueRouter )

/*
	This will cehck to see if the user is authenticated or not.
*/
function requireAuth (to, from, next) {
	/*
		Determines where we should send the user.
	*/
	function proceed () {
		/*
			If the user has been loaded determine where we should
			send the user.
		*/
    if ( store.getters.getUserLoadStatus() == 2 ) {
			/*
				If the user is not empty, that means there's a user
				authenticated we allow them to continue. Otherwise, we
				send the user back to the home page.
			*/
			if( store.getters.getUser != '' ){
      	next();
			}else{
				next('/cafes');
			}
    }
	}

	/*
		Confirms the user has been loaded
	*/
	if ( store.getters.getUserLoadStatus != 2 ) {
		/*
			If not, load the user
		*/
		store.dispatch( 'loadUser' );

		/*
			Watch for the user to be loaded. When it's finished, then
			we proceed.
		*/
		store.watch( store.getters.getUserLoadStatus, function(){
			if( store.getters.getUserLoadStatus() == 2 ){
				proceed();
			}
		});
	} else {
		/*
			User call completed, so we proceed
		*/
		proceed()
	}
}

/*
	Makes a new VueRouter that we will use to run all of the routes
	for the app.
*/
export default new VueRouter({
	routes: [
		{
			path: '/',
			redirect: { name: 'cafes' },
			name: 'layout',
			component: Vue.component( 'Layout', require( './pages/Layout.vue' ) ),
			children: [
				{
					path: 'cafes',
					name: 'cafes',
					component: Vue.component( 'Home', require( './pages/Home.vue' ) ),
					children: [
						{
							path: 'new',
							name: 'newcafe',
							component: Vue.component( 'NewCafe', require( './pages/NewCafe.vue' ) ),
							beforeEnter: requireAuth
						},
						{
							path: ':id',
							name: 'cafe',
							component: Vue.component( 'Cafe', require( './pages/Cafe.vue' ) )
						},
					]
				},
				{
					path: 'cafes/:id/edit',
					name: 'editcafe',
					component: Vue.component( 'EditCafe', require( './pages/EditCafe.vue' ) ),
					beforeEnter: requireAuth
				},
				{
					path: 'profile',
					name: 'profile',
					component: Vue.component( 'Profile', require( './pages/Profile.vue' ) ),
					beforeEnter: requireAuth
				},
				{
					path: 'users',
					name: 'users',
					component: Vue.component( 'Users', require( './pages/Users.vue' ) ),
					beforeEnter: requireAuth
				}
			]
		}
	]
});
