import Axios from 'axios';
const state = {
	drawingColor: 'black',
	drawingTool: 'brush',
	allDotVolume: 900,
	lineDotVolume: 30,
	currentProduct: 0,
	saveStatus: false,
	currentMaterial: {},
	usedMaterial: String,
	saveMaterial: [],
};

const getters = {};

const mutations = {
	drawingColor(state, color) {
		state.drawingColor = color;
	},
	drawingTool(state, tool) {
		state.drawingTool = tool;
	},
	setCurrentProduct(state, current) {
		state.allDotVolume = current.alldot;
		state.lineDotVolume = current.linedot;
		state.currentProduct = current.id;
		state.usedMaterial = current.usedmaterial;
	},
	productSave(state) {
		state.saveStatus = !state.saveStatus;
	},
	resetProduct(state) {
		state.currentProduct = 0;
	},
	setCurrentMaterial(state, current) {
		state.currentMaterial = current;
	},
	setSaveMaterial(state, current) {
		state.saveMaterial.push(current);
	},
};

const actions = {};

export default {
	namespaced: true,
	state,
	getters,
	mutations,
	actions,
};
