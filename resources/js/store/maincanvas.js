import Axios from "axios";
const state = {
    drawingColor:'black',
    allDotVolume:900,
    lineDotVolume:30,
    currentProduct:0,
    saveStatus:false
}

const getters = {}

const mutations = {
    drawingColor(state,color){
        state.drawingColor=color;
    },
    setCurrentProduct(state,current){
        state.allDotVolume=current.alldot;
        state.lineDotVolume=current.linedot;
        state.currentProduct=current.id;
    },
    productSave(state){
        state.saveStatus=!state.saveStatus;
    },
    resetProduct(state){
        state.currentProduct=0;
    },
}

const actions = {
}

export default {
    namespaced:true,
    state,
    getters,
    mutations,
    actions
}