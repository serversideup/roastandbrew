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

/*
	Extends Vue to use Vue Router
*/
Vue.use( VueRouter )

/*
	Makes a new VueRouter that we will use to run all of the routes
	for the app.
*/
export default new VueRouter({
	routes: [
		{
			path: '/',
			name: 'layout',
			component: Vue.component( 'Layout', require( './pages/Layout.vue' ) ),
			children: [
				{
					path: 'home',
					name: 'home',
					component: Vue.component( 'Home', require( './pages/Home.vue' ) )
				},
				{
					path: 'cafes',
					name: 'cafes',
					component: Vue.component( 'Cafes', require( './pages/Cafes.vue' ) ),
				},
				{
					path: 'cafes/new',
					name: 'newcafe',
					component: Vue.component( 'NewCafe', require( './pages/NewCafe.vue' ) )
				},
				{
					path: 'cafes/:id',
					name: 'cafe',
					component: Vue.component( 'Cafe', require( './pages/Cafe.vue' ) )
				}
			]
		}
	]
});
