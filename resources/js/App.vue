<template>
  <div class="app">
    <header>
      <Navber />
    </header>
    <main>
      <router-view />
    </main>
    <Footer />
  </div>
</template>

<script>
import Navber from './components/HeaderFooter/Navber.vue'
import Footer from './components/HeaderFooter/Footer.vue'
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from './util'
import Axios from 'axios'

export default {
  components:{
    Navber,
    Footer
  },
  computed:{
    errorCode (){
      return this.$store.state.error.code
    }
  },
  watch:{
    errorCode:{
      async handler(val){
        if(val == INTERNAL_SERVER_ERROR){
          this.$router.push('/500')
        }else if(val == UNAUTHORIZED){
          await axios.get('/api/refresh-token')
          this.$store.commit('auth/setUser',null)
          this.$router.push('/login')
        }else if(val == NOT_FOUND){
          this.$router.push('/not-found')
        }
      },
      immediate:true
    },
    $route(){
      this.$store.commit('error/setCode',null)
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../sass/common.scss";
main{
  width: 1050px;
  margin: 0 auto;
  background-color: rgba($maincolor,0.07);
}
</style>
