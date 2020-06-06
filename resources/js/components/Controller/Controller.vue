<template>
  <div id="controller">
    <UserProduction></UserProduction>
    <ColorPalet></ColorPalet>
    <DrawTool></DrawTool>
  </div>
</template>

<script>
import ColorPalet from './Colorpalet/ColorPalet.vue';
import UserProduction from '../ProdauctionsList/UserProductions.vue';
import DrawTool from './DrawTool/DrawTool.vue';

export default {
  components: {
    ColorPalet,
    UserProduction,
    DrawTool,
  },
};
</script>

<style lang="scss" scoped>
#controller {
  width: 30%;
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>
