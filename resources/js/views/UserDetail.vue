<template>
  <div class="userDetail">
    <div class="userInformation">
      <img :src="user.thumbnail" :alt="`${user.name}のサムネイル`" class="thumbnail" />
      <div>
        <h2 class="username">{{user.name}}</h2>
        <p class="userintroduction">{{user.introduction}}</p>
      </div>
    </div>
    <h2>投稿一覧</h2>
    <div class="productsList" :style="style">
      <Product
        v-for="product in products"
        :key="product.id"
        :product="product"
        :appearLike="true"
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
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util';
export default {
  components: {
    Product,
    Pagination,
  },
  props: {
    id: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      user: {
        name: String,
        introduction: String,
        thumbnail: String,
      },
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
    async showUser() {
      const response = await axios.get(`/api/users/${this.id}`);
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }
      this.user.name = response.data[0].name;
      this.user.introduction = response.data[0].introduction;
      this.user.thumbnail = response.data[0].userthumbnail.url;
    },
    async showProducts() {
      const response = await axios.get(`/api/users/products/${this.id}/?page=${this.page}`);
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }
      this.products = response.data.data;
      this.currentPage = response.data.current_page;
      this.lastPage = response.data.last_page;
    },
  },

  watch: {
    $route: {
      async handler() {
        await this.showUser();
        await this.showProducts();
      },
      immediate: true,
    },
  },
};
</script>

<style lang="scss" scoped>
@import '../../sass/common.scss';
.userDetail {
  margin: 0 auto;
  margin-top: 0;
  display: flex;
  flex-flow: column;
  width: 100%;
  align-items: center;
}
.userInformation {
  width: 850px;
  padding-top: 30px;
  display: flex;
  div {
    margin-left: 20px;
  }
}
h2 {
  margin-bottom: 10px;
}
.productsList {
  display: flex;
  flex-flow: row wrap;
  align-content: flex-start;
}
.thumbnail {
  width: 130px;
  height: 130px;
}
</style>
