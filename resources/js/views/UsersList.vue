<template>
  <div>
    <ul>
      <li v-for="user in users" :key="user.id">
        <router-link :to="`/users/${user.id}`">
          <img :src="user.userthumbnail.url" :alt="`${user.name}のサムネイル`" class="thumbnail" />
        </router-link>
        <div class="userInformation">
          <h1>{{user.name}}</h1>
          <p>{{user.introduction}}</p>
          <div v-if="isLogin">フォローする</div>
        </div>
        <div class="productsList">
          <Product
            v-for="product in user.products"
            :key="product.id"
            :product="product"
            :productstyle="productStyle"
          />
        </div>
      </li>
    </ul>
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
    Pagination,
    Product,
  },
  data() {
    return {
      users: [],
      currentPage: 0,
      lastPage: 0,
      maxwidth: 400,
    };
  },
  computed: {
    isLogin() {
      return this.$store.getters['auth/check'];
    },
    productStyle() {
      const product = `${this.maxwidth / 3}px`;
      return {
        width: product,
        height: product,
      };
    },
  },
  methods: {
    async showUsers() {
      const response = await axios.get('/api/users/list/?page=${this.page}');
      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status);
        return false;
      }
      this.users = response.data.data;
      this.currentPage = response.data.current_page;
      this.lastPage = response.data.last_page;
    },
  },
  watch: {
    $route: {
      async handler() {
        await this.showUsers();
      },
      immediate: true,
    },
  },
};
</script>

<style lang="scss" scoped>
ul {
  margin: 0;
  padding: 40px 0;
  display: flex;
  flex-flow: column;
  align-items: center;
}
li {
  width: 810px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 30px;
  &:first-child {
    margin-top: 0;
  }
  a {
    width: 130px;
    height: 130px;
  }
}
.thumbnail {
  width: 100%;
  height: 100%;
}
.userInformation {
  margin-left: 3%;
  width: 230px;
  display: flex;
  flex-flow: column;
  padding: 10px 0;
  align-items: flex-start;
}
.productsList {
  width: 400px;
  display: flex;
  flex-flow: row wrap;
  align-items: center;
}
</style>
