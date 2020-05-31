<template>
  <div>
    <ul>
      <li v-for="user in users" :key="user.id">{{user.name}}</li>
    </ul>
    <Pagination :current-page="currentPage" :last-page="lastPage" />
  </div>
</template>

<script>
import Pagination from '../components/Pagination.vue';
import Axios from 'axios';
import { OK } from '../util';
export default {
  components: {
    Pagination,
  },
  data() {
    return {
      users: [],
      currentPage: 0,
      lastPage: 0,
    };
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
}
</style>
