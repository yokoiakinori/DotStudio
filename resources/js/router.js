import Vue from 'vue'
import VueRouter from 'vue-router'

import MyDrawing from './views/MyDrawing.vue'
import Home from './views/Home.vue'
import Search from './views/Search.vue'
import TagSearch from './views/TagSearch.vue'
import Login from './views/Login.vue'
import ProductDetail from './views/ProductDetail.vue'
import UsersList from './views/UsersList.vue'
import UserDetail from './views/UserDetail.vue'
import FollowList from './views/FollowList.vue'
import Notification from './views/Notification.vue'
import UserSettings from './views/UserSettings.vue'
import System from './views/errors/System.vue'
import NotFound from './views/errors/NotFound.vue'

import store from './store'

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',
	routes: [{
			path: '/',
			component: Home,
			props: route => {
				const page = route.query.page
				return {
					page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
				}
			}
		},
		{
			path: '/search',
			component: Search,
			props: route => {
				const page = route.query.page
				return {
					page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
				}
			}

		},
		{
			path: '/tagsearch',
			component: TagSearch,
			props: route => {
				const page = route.query.page
				return {
					page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
				}
			}

		},
		{
			path: '/products/:id',
			component: ProductDetail,
			props: true
		},
		{
			path: '/drawing',
			component: MyDrawing,
			beforeEnter(to, from, next) {
				if (store.getters['auth/check']) {
					next()
				} else {
					next('/login')
				}
			},
			props: route => {
				const page = route.query.page
				return {
					page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
				}
			},
			props: true,
		},
		{
			path: '/login',
			component: Login,
			beforeEnter(to, from, next) {
				if (store.getters['auth/check']) {
					next('/')
				} else {
					next()
				}
			}
		},
		{
			path: '/userslist',
			component: UsersList,
			props: route => {
				const page = route.query.page
				return {
					page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1
				}
			}
		},
		{
			path: '/users/:id',
			component: UserDetail,
			props: true,
		},
		{
			path: '/follow/:id',
			component: FollowList,
			props: true,
			name: 'follow'
		},
		{
			path: '/follower/:id',
			component: FollowList,
			props: true,
			name: 'follower'
		},
		{
			path: '/notification',
			component: Notification,
			beforeEnter(to, from, next) {
				if (store.getters['auth/check']) {
					next()
				} else {
					next('/login')
				}
			},
			props: true,
		},
		{
			path: '/settings/:id',
			component: UserSettings,
			beforeEnter(to, from, next) {
				if (store.getters['auth/check']) {
					next()
				} else {
					next('/login')
				}
			},
			props: true,
		},
		{
			path: '/500',
			component: System
		},
		{
			path: '*',
			component: NotFound
		}
	]
})

export default router
