<template>
	<div>
		<transition>
			<modalWindow v-if="modalWindow" @closeModal="modalToggle" @formEnter="createProduction">
				<label for="productname">作品名</label>
				<input type="text" v-model="productname" />
				<label for="linedot">1列に配置するドット数（合計：{{ alldot }}ドット）</label>
				<input type="number" v-model="linedot" />
				<label for="tags">タグ（カテゴリー、イメージ）</label>
				<input type="text" v-model="producttagstring" />
				<ul class="tagList">
					<li v-for="(productTag, index) in productTags" :key="index">
						<ProductTag :message="productTag"></ProductTag>
					</li>
				</ul>
			</modalWindow>
		</transition>
		<ul class="UserProductions">
			<li
				v-for="product in productionList"
				:key="product.myproductid"
				@click="currentProduct = product.myproductid"
				:class="{ active: currentProduct === product.myproductid }"
			>
				<span>{{ product.myproductid }}</span>
				<div class="product-name">{{ product.productname }}</div>
				<button
					v-show="currentProduct === product.myproductid"
					class="save-button"
					@click="productSave"
				>
					保存
				</button>
				<i
					class="fas fa-trash-alt"
					v-show="currentProduct === product.myproductid"
					@click="productDelete"
				></i>
			</li>
			<li id="createProduction" @click="modalToggle" v-if="this.currentPage == this.lastPage">
				<span>＋</span>ここをクリックで新規作成
			</li>
			<li class="pagination">
				<Pagination :current-page="currentPage" :last-page="lastPage" routerPath="/drawing" />
			</li>
		</ul>
	</div>
</template>

<script>
import ModalWindow from '../ModalWindow.vue';
import Pagination from '../Pagination.vue';
import ProductTag from '../ProductTag.vue';
import Axios from 'axios';
import { OK, CREATED } from '../../util';
export default {
	components: {
		ModalWindow,
		Pagination,
		ProductTag,
	},
	data() {
		return {
			currentPage: 0,
			currentProduct: 0,
			id: 0,
			lastPage: 0,
			linedot: 0,
			modalWindow: false,
			productionList: [],
			productname: '',
			productTags: [],
			producttagstring: '',
		};
	},
	computed: {
		alldot: function() {
			return this.linedot ** 2;
		},
		defaultProduct: function() {
			return this.productionList[0];
		},
	},
	watch: {
		$route: {
			async handler() {
				await this.reset();
				await this.showProductsList();
				this.$store.commit('randing/loadingSwitch', false);
			},
			immediate: true,
		},
		currentPage: function(val) {
			this.id = (val - 1) * 3 + this.productionList.length + 1;
		},
		currentProduct(val) {
			const currentProductNumber = val - (this.$route.query.page - 1) * 3;
			const current = this.productionList[currentProductNumber - 1];
			this.$store.commit('maincanvas/setCurrentProduct', {
				alldot: current.alldot,
				linedot: current.linedot,
				id: current.id,
				usedmaterial: current.usedmaterial,
			});
		},
		producttagstring: function(val) {
			const productTags = val.split(/\s+/);
			this.productTags = productTags;
		},
	},
	methods: {
		async createProduction() {
			const createProduct = {
				productname: this.productname,
				alldot: Number(this.alldot),
				linedot: Number(this.linedot),
				tags: this.productTags,
			};

			const response = await axios.post('/api/products', createProduct);
			this.productionList.push(response.data);

			if (response.status !== CREATED) {
				this.$store.commit('error/setCode', response.status);
				return false;
			}

			if (!this.id == 0) {
				this.productionList[this.id - (this.currentPage - 1) * 3 - 1].id = response.data.id;
				this.productionList[this.id - (this.currentPage - 1) * 3 - 1].myproductid = this.id;
			} else {
				this.productionList[0].id = response.data.id;
				this.productionList[0].myproductid = 1;
				this.id++;
			}

			this.modalToggle(); //Line:146
			this.currentProduct = this.id;

			if (this.id % 3 == 1) {
				const pageId = Math.floor(this.id / 3) + 1;
				this.$router.push(`drawing?page=${pageId}`);
			}
			this.id++;
		},
		modalToggle() {
			this.reset();
			this.modalWindow = !this.modalWindow;
		},
		productSave() {
			this.$store.commit('maincanvas/productSave');
		},
		async productDelete() {
			const productid = this.currentProduct - (this.currentPage - 1) * 3 - 1;
			const response = await axios.get(`/api/products/delete/${this.productionList[productid].id}`);
			this.productionList.splice(productid, 1);
			this.id -= 1;
		},
		reset() {
			this.linedot = 0;
			this.productname = null;
			this.productTags.length = 0;
			this.producttagstring = '';
		},
		async showProductsList() {
			this.productionList.length = 0;
			const response = await axios.get(`/api/products?page=${this.$route.query.page}`);

			if (!response.data.data.length == 0) {
				this.currentProduct = (this.$route.query.page - 1) * 3 + 1;
				for (let i = 0; i < response.data.data.length; i++) {
					this.productionList.push(response.data.data[i]);
					this.productionList[i].myproductid = i + (this.$route.query.page - 1) * 3 + 1;
				}

				this.id = this.productionList.length + 1;

				this.$store.commit('maincanvas/setCurrentProduct', {
					alldot: this.defaultProduct.alldot,
					linedot: this.defaultProduct.linedot,
					id: this.productionList[0].id,
				});
			}

			this.currentPage = response.data.current_page;
			this.lastPage = response.data.last_page;
		},
	},
};
</script>

<style lang="scss" scoped>
@import '../../../sass/common.scss';
div {
	width: 100%;
}
.UserProductions {
	width: 100%;
	height: 220px;
	margin: 0;
	padding: 0;
	display: flex;
	justify-content: start;
	flex-direction: column;
	align-items: center;
	li {
		font-size: 18px;
		width: 100%;
		height: 20%;
		list-style: none;
		display: flex;
		justify-content: flex-start;
		align-items: center;
		border-top: solid 1.2px $maincolor;
		transition-duration: 0.3s;

		span {
			margin: 0 20px;
		}
	}
}
#createProduction {
	color: rgba($maincolor, 0.6);
}
.v-enter-active,
.v-leave-active {
	transition: opacity 0.2s;
}
.v-enter,
.v-leave-to {
	opacity: 0;
}
.active {
	transition-duration: 0.3s;
	background-color: $maincolor;
	color: white;
	i {
		color: white !important;
		margin-left: 10px;
	}
}
.product-name {
	width: 52%;
}
.save-button {
	color: white;
	font-size: 15px;
	width: 16%;
	border-right: solid 1px white;
}
label {
	width: 100%;
	font-size: 16px;
}
input {
	width: 100%;
	height: 45px;
	border-bottom: solid 1px $maincolor;
	margin-bottom: 30px;
}
.tagList {
	display: flex;
	height: 100px;
	padding: 0;
	flex-flow: row;
	justify-content: flex-start;
	align-items: flex-start;
	li {
		border: none;
		display: inline-block;
		margin-right: 20px;
	}
}
</style>
