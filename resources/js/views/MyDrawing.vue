<template>
  <div class="MyDrawing">
    <Controller></Controller>
    <MainCanvas></MainCanvas>
  </div>
</template>

<script>
import Controller from "../components/Controller/Controller.vue"
import MainCanvas from "../components/MainCanvas/MainCanvas.vue"

  export default{
    components:{
      Controller,
      MainCanvas
    },
    props:{name:String},
  }
</script>

<style lang="scss" scoped>
.MyDrawing{
  display: flex;
  justify-content: space-between;
  box-sizing:border-box;
  width: 100%;
  height: 1050px;
  padding: 30px 0;
}
</style>
