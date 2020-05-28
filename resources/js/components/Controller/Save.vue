<template>
    <div id="save" @click="productSave">保存</div>
</template>

<script>
export default{
    methods:{
        productSave(){
            this.$store.commit('maincanvas/productSave')
        }
    }
}
</script>

<style lang="scss" scoped>
#save{
    height: 60px;
    width: 100%;
    background-color: aqua;
}
</style>