<template>
<div id="controller">
    <UserProduction></UserProduction>
    <ColorPalet></ColorPalet>
</div>
</template>

<script>
import ColorPalet from "./Colorpalet/ColorPalet.vue"
import UserProduction from "../ProdauctionsList/UserProductions.vue"

export default {
    components:{
        ColorPalet,
        UserProduction
    }
}
</script>

<style lang="scss" scoped>
#controller{
    width: 30%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>