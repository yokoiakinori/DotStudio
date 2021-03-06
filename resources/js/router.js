import Vue from 'vue'
import VueRouter from 'vue-router'

import MyDrawing from './views/MyDrawing.vue'
import Home from './views/Home.vue'
import Login from './views/Login.vue'
import System from './views/errors/System.vue'
import ProductDetail from './views/ProductDetail.vue'
import NotFound from './views/errors/NotFound.vue'

import store from './store'

Vue.use(VueRouter)

const router = new VueRouter({
    mode:'history',
    routes:[
        {
            path:'/',
            component: Home,
            props: route => {
                const page = route.query.page
                return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1}
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
            beforeEnter(to, from, next){
                if(store.getters['auth/check']){
                    next()
                }else{
                    next('/login')
                }
            },
            props:true,
        },
        {
            path: '/login',
            component: Login,
            beforeEnter(to, from, next){
                if(store.getters['auth/check']){
                    next('/')
                }else{
                    next()
                }
            }
        },
        {
            path: '/500',
            component: System
        },
        {
            path:'*',
            component:NotFound
        }
    ]
})

export default router