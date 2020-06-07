<template>
  <nav class="navber">
    <router-link class="navber_logo" to="/" exact>Dotstudio</router-link>
    <form action class="searchbox">
      <input type="text" placeholder="タグ、作品名" />
      <i class="fas fa-search"></i>
    </form>
    <div class="navbar__menu">
      <div v-if="isLogin" class="navbar__item">
        <router-link to="/notification" @click="reset">通知</router-link>
      </div>
      <div class="navbar__item">
        <router-link to="/userslist" @click="reset">ユーザー</router-link>
      </div>
      <div v-if="isLogin" class="navbar__item">
        <router-link to="/drawing" @click="reset">
          <div class="drawing_link">
            <i class="fas fa-paint-brush"></i>イラストを描く
          </div>
        </router-link>
      </div>
      <router-link v-if="isLogin" class="navbar__item" :to="`/users/${userid}`">{{username}}</router-link>
      <figure v-if="isLogin" class="navbar__item">
        <img class="thumbnail" :src="thumbnail" alt="${username}のサムネイル" />
      </figure>
      <div v-else class="navbar__item">
        <RouterLink class="button button--link" to="/login">ログイン / 会員登録</RouterLink>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  computed: {
    isLogin() {
      return this.$store.getters['auth/check'];
    },
    username() {
      return this.$store.getters['auth/username'];
    },
    userid() {
      return this.$store.getters['auth/userid'];
    },
    thumbnail() {
      return this.$store.getters['auth/thumbnail'];
    },
  },
  methods: {
    reset() {
      this.$store.commit('maincanvas/resetProduct');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '../../../sass/common.scss';
.navber {
  width: 100vw;
  height: 75px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 2px 5px rgba($color: $maincolor, $alpha: 0.25);
}
.navber_logo {
  font-family: Arial, Helvetica, sans-serif;
  font-weight: bold;
  font-size: 32px;
  margin-left: 30px;
}
.searchbox {
  background-color: rgba($maincolor, 0.3);
  width: 200px;
  height: 30px;
  margin-left: 40px;
  border-radius: 30px;
  i {
    display: inline-block;
    text-align: center;
    vertical-align: bottom;
    line-height: 20px;
    font-size: 17px;
    height: 20px;
    width: 20px;
    transform: translateY(4%);
  }
  input {
    margin-left: 15px;
    width: 145px;
  }
  ::-webkit-input-placeholder {
    color: rgba($maincolor, 0.6);
    font-size: 12px;
  }
}
.navbar__item {
  margin: 10px;
  a {
    font-size: 14px;
  }
}
.drawing_link {
  font-size: 14px;
  margin-right: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 135px;
  height: 34px;
  border: solid 1px rgba($maincolor, 0.8);
  border-radius: 5px;
  i {
    margin-right: 5px;
  }
}
.navbar__menu {
  margin-right: 30px;
  flex-grow: 2;
  display: flex;
  align-items: center;
  justify-content: flex-end;
}
.thumbnail {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}
</style>
