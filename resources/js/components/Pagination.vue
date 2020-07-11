<template>
  <ul class="paginationList">
    <li
      v-for="number in paginateListNumber"
      :key="number"
      :class="{currentButton:currentPage==number}"
    >
      <router-link :to="`${routerPath}?page=${number}`">{{number}}</router-link>
    </li>
    <p v-if="lastPage>=6">…</p>
    <li v-if="lastPage>=6">
      <router-link :to="`${routerPath}?page=${lastPage}`">{{lastPage}}</router-link>
    </li>
  </ul>
</template>>


<script>
export default {
  props: {
    currentPage: {
      type: Number,
      required: true,
    },
    lastPage: {
      type: Number,
      required: true,
    },
    routerPath: String,
  },
  computed: {
    isFirstPage() {
      return this.currentPage === 1;
    },
    isLastPage() {
      return this.currentPage === this.lastPage;
    },
    paginateListNumber() {
      const numbers = [];
      if (this.lastPage <= 5 && this.currentPage < 4) {
        //currentPage:3以下 lastPage:5以下
        for (let i = 1; i <= this.lastPage; i++) {
          numbers.push(i);
        }
      } else if (this.lastPage > 5 && this.lastPage - this.currentPage > 3) {
        //lastPage:6以上
        for (let i = this.currentPage; i <= this.currentPage + 4; i++) {
          numbers.push(i);
        }
      }
      return numbers;
    },
  },
};
</script>>

<style lang="scss" scoped>
@import '../../sass/common.scss';
.paginationList {
  display: flex;
  flex-flow: row nowrap;
  justify-content: center;
  width: 100%;
  height: 32px;
  margin: 0;
  padding: 0;
  li {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    margin: 0 5px;
    transition-duration: 0.3s;
    a {
      font-size: 14px;
    }
  }
}

.currentButton {
  transition-duration: 0.3s;
  background-color: $maincolor;
  a {
    color: white;
  }
}
</style>
