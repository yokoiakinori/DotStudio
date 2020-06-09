<template>
  <div class="Home">
    <div class="productsList" :style="style">
      <Product
        v-for="product in products"
        :key="product.id"
        :product="product"
        :appearLike="true"
        @like="onLikeClick"
        :productstyle="productStyle"
      />
    </div>
    <Pagination :current-page="currentPage" :last-page="lastPage" />
  </div>
</template>

<script>
import Pagination from '../components/Pagination.vue';
import Product from '../components/index/Product.vue';
import Axios from 'axios';
import { OK } from '../util';
export default {
  components: {
    Product,
    Pagination,
  },
  data() {
    return {
      products: [],
      currentPage: 0,
      lastPage: 0,
      maxwidth: 900,
      style: {
        width: '900px',
        height: '1500px',
      },
    };
  },
  computed: {
    productStyle() {
      const product = `${this.maxwidth / 3}px`;
      return {
        width: product,
        height: product,
      };
    },
  },
  methods: {
    async showProducts() {
      const response = await axios.get('/api/products/index/?page=${this.page}');
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }
      this.products = response.data.data;
      this.currentPage = response.data.current_page;
      this.lastPage = response.data.last_page;
    },
    onLikeClick({ id, liked }) {
      if (!this.$store.getters['auth/check']) {
        alert('いいね機能を使うにはログインしてください。');
        return false;
      }

      if (liked) {
        this.unlike(id);
      } else {
        this.like(id);
      }
    },
    async like(id) {
      const response = await axios.put(`/api/products/${id}/like`);

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.products = this.products.map(product => {
        if (product.id == response.data.product_id) {
          product.likes_count += 1;
          product.liked_by_user = true;
        }
        return product;
      });
    },
    async unlike(id) {
      const response = await axios.delete(`/api/products/${id}/like`);

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }

      this.products = this.products.map(product => {
        if (product.id == response.data.product_id) {
          product.likes_count -= 1;
          product.liked_by_user = false;
        }
        return product;
      });
    },
  },
  watch: {
    $route: {
      async handler() {
        await this.showProducts();
      },
      immediate: true,
    },
  },
  props: {
    page: {
      type: Number,
      required: false,
      default: 1,
    },
  },
};
</script>

<style lang="scss" scoped>
@import '../../sass/common.scss';
.Home {
  margin: 0 auto;
  margin-top: 0;
  display: flex;
  width: 100%;
  justify-content: center;
}
.productsList {
  margin-top: 30px;
  display: flex;
  flex-flow: row wrap;
  align-content: flex-start;
}
</style>
