<template>
  <ul id="UserProductions">
    <transition>
      <modalWindow v-if="modalWindow" @closeModal="modalToggle" @formEnter="createProduction">
        <label for="productname">作品名</label>
        <input type="text" v-model="productname" />
        <label for="linedot">1列に配置するドット数</label>
        <input type="number" v-model="linedot" />
        <p>{{alldot}}</p>
      </modalWindow>
    </transition>
    <li
      v-for="product in productionList"
      :key="product.myproductid"
      @click="currentProduct = product.myproductid"
      :class="{active:currentProduct === product.myproductid}"
    >
      <span>{{product.myproductid}}</span>
      <div class="product-name">{{product.productname}}</div>
      <button
        v-show="currentProduct === product.myproductid"
        class="save-button"
        @click="productSave"
      >保存</button>
      <i
        class="fas fa-trash-alt"
        v-show="currentProduct === product.myproductid"
        @click="productDelete"
      ></i>
    </li>
    <li id="createProduction" @click="modalToggle">
      <span>＋</span>ここをクリックで新規作成
    </li>
  </ul>
</template>

<script>
import ModalWindow from '../ModalWindow.vue';
import Axios from 'axios';
export default {
  components: {
    ModalWindow,
  },
  computed: {
    alldot: function() {
      return this.linedot ** 2;
    },
    defaultProduct: function() {
      return this.productionList[0];
    },
  },
  data() {
    return {
      modalWindow: false,
      id: 0,
      productname: '',
      linedot: 0,
      productionList: [],
      currentProduct: 0,
    };
  },
  created: function() {
    this.showProductsList();
  },
  watch: {
    currentProduct: function(val) {
      const current = this.productionList[val - 1];
      this.$store.commit('maincanvas/setCurrentProduct', {
        alldot: current.alldot,
        linedot: current.linedot,
        id: current.myproductid,
      });
    },
    $route: {
      async handler() {
        this.id = 0;
        this.$store.commit('maincanvas/setCurrentProduct', {
          alldot: 900,
          linedot: 30,
          id: 0,
        });
        await this.reset();
      },
      immediate: true,
    },
  },
  methods: {
    reset() {
      this.linedot = 0;
      this.productname = null;
    },
    async createProduction() {
      if (!this.id == 0) {
        this.productionList.push({
          productname: this.productname,
          alldot: Number(this.alldot),
          linedot: Number(this.linedot),
        });
        const response = await axios.post('/api/products', this.productionList[this.id - 1]);
        this.productionList[this.id - 1].id = response.data.id;
        this.productionList[this.id - 1].myproductid = this.id;
      } else {
        this.productionList.push({
          productname: this.productname,
          alldot: Number(this.alldot),
          linedot: Number(this.linedot),
        });
        const response = await axios.post('/api/products', this.productionList[this.id]);
        this.productionList[0].id = response.data.id;
        this.productionList[0].myproductid = 1;
        this.id++;
      }
      this.modalToggle();
      this.currentProduct = this.id;
      this.id++;
    },
    modalToggle() {
      this.reset();
      this.modalWindow = !this.modalWindow;
    },
    async showProductsList() {
      const response = await axios.get('/api/products');
      if (!response.data.products.length == 0) {
        this.currentProduct = 1;
        for (let i = 0; i < response.data.products.length; i++) {
          this.productionList.push(response.data.product[i]);
          this.productionList[i].myproductid = i + 1;
        }
        this.id = this.productionList.length + 1;
        this.$store.commit('maincanvas/setCurrentProduct', {
          alldot: this.defaultProduct.alldot,
          linedot: this.defaultProduct.linedot,
          id: this.productionList[0].id,
        });
      }
    },
    productSave() {
      this.$store.commit('maincanvas/productSave');
    },
    async productDelete() {
      const productid = this.currentProduct - 1;
      const response = await axios.get(`/api/products/delete/${this.productionList[productid].id}`);
      this.productionList.splice(productid, 1);
    },
  },
};
</script>

<style lang="scss" scoped>
@import '../../../sass/common.scss';
ul {
  width: 100%;
  height: 220px;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: start;
  flex-direction: column;
  align-items: center;
}
li {
  font-size: 18px;
  width: 100%;
  height: 25%;
  list-style: none;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  border-bottom: solid 1.2px $maincolor;
  transition-duration: 0.3s;
  &:first-child {
    border-top: solid 1.2px $maincolor;
  }
  span {
    margin: 0 20px;
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
</style>
