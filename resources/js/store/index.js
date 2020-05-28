import Vue from 'vue'
import Vuex from 'vuex'

import maincanvas from './maincanvas'
import auth from './auth'
import error from './error'
import randing from './randing'


Vue.use(Vuex)

const store = new Vuex.Store({
    modules:{
        auth,
        maincanvas,
        error,
        randing
    }
})

export default store