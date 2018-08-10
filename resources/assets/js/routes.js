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
				switch( to.meta.permission ){
					/*
						If the route that requires authentication is a user, then we continue.
						All users can access these routes
					*/
					case 'user':
						next();
					break;
					/*
						If the route that requires authentication is an owner and the permission
						the user has is greater than or equal to 1 (an owner or higher), we allow
						access. Otherwise we redirect back to the cafes.
					*/
					case 'owner':
						if( store.getters.getUser.permission >= 1 ){
							next();
						}else{
							next('/cafes');
						}
					break;
					/*
						If the route that requires authentication is an admin and the permission
						the user has is greater than or equal to 2 (an owner or higher), we allow
						access. Otherwise we redirect back to the cafes.
					*/
					case 'admin':
						if( store.getters.getUser.permission >= 2 ){
							next();
						}else{
							next('/cafes');
						}
					break;
					/*
						If the route that requires authentication is a super admin and the permission
						the user has is equal to 3 (a super admin), we allow
						access. Otherwise we redirect back to the cafes.
					*/
					case 'super-admin':
						if( store.getters.getUser.permission == 3 ){
							next();
						}else{
							next('/cafes');
						}
					break;
				}
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
			component: Vue.component( 'Layout', require( './layouts/Layout.vue' ) ),
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
							beforeEnter: requireAuth,
							meta: {
								permission: 'user'
							}
						},
						{
							path: ':slug',
							name: 'cafe',
							component: Vue.component( 'Cafe', require( './pages/Cafe.vue' ) )
						},
					]
				},
				{
					path: 'cafes/:slug/edit',
					name: 'editcafe',
					component: Vue.component( 'EditCafe', require( './pages/EditCafe.vue' ) ),
					beforeEnter: requireAuth,
					meta: {
						permission: 'user'
					}
				},
				{
					path: 'profile',
					name: 'profile',
					component: Vue.component( 'Profile', require( './pages/Profile.vue' ) ),
					beforeEnter: requireAuth,
					meta: {
						permission: 'user'
					}
				},
				/*
					Catch Alls
				*/
				{ path: '_=_', redirect: '/' }
			]
		},
		{
			path: '/admin',
			name: 'admin',
			redirect: { name: 'admin-actions' },
			component: Vue.component( 'Admin', require('./layouts/Admin.vue' ) ),
			beforeEnter: requireAuth,
			meta: {
				permission: 'owner'
			},
			children: [
				{
					path: 'actions',
					name: 'admin-actions',
					component: Vue.component( 'AdminActions', require( './pages/admin/Actions.vue' ) ),
					meta: {
						permission: 'owner'
					}
				},
				{
					path: 'companies',
					name: 'admin-companies',
					component: Vue.component( 'AdminCompanies', require( './pages/admin/Companies.vue' ) ),
					meta: {
						permission: 'owner'
					}
				},
				{
					path: 'companies/:id',
					name: 'admin-company',
					component: Vue.component( 'AdminCompany', require( './pages/admin/Company.vue' ) ),
					meta: {
						permission: 'owner'
					}
				},
				{
					path: 'companies/:id/cafe/:cafeID',
					name: 'admin-cafe',
					component: Vue.component( 'AdminCafe', require( './pages/admin/Cafe.vue' ) ),
					meta: {
						permission: 'owner'
					}
				},
				{
					path: 'users',
					name: 'admin-users',
					component: Vue.component( 'AdminUsers', require( './pages/admin/Users.vue' ) ),
					meta: {
						permission: 'admin'
					}
				},
				{
					path: 'users/:id',
					name: 'admin-user',
					component: Vue.component( 'AdminUser', require( './pages/admin/User.vue' ) ),
					meta: {
						permission: 'admin'
					}
				},
				{
					path: 'brew-methods',
					name: 'admin-brew-methods',
					component: Vue.component( 'AdminBrewMethods', require( './pages/admin/BrewMethods.vue' ) ),
					meta: {
						permission: 'super-admin'
					}
				},
				{
					path: 'brew-methods/:id',
					name: 'admin-brew-method',
					component: Vue.component( 'AdminBrewMethod', require( './pages/admin/BrewMethod.vue' ) ),
					meta: {
						permission: 'super-admin'
					}
				},

				/*
					Catch Alls
				*/
				{ path: '_=_', redirect: '/' }
			]
		},
	]
});
