<template>
  <div>
    <ul class="palet">
      <li
        v-for="paletcolor in paletcolors"
        :key="paletcolor.id"
        :class="{active:currentpalet === paletcolor.id}"
        :style="activeStyle(paletcolor.color)"
      >
        <i
          class="fas fa-palette"
          :style="{color: paletcolor.color}"
          @click="drawingColorInput(paletcolor.color,paletcolor.id)"
        ></i>
      </li>
    </ul>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  data() {
    return {
      paletcolors: [
        { id: 1, color: 'black' },
        { id: 2, color: 'rgb(50%, 50%, 50%)' },
        { id: 3, color: 'white' },
        { id: 4, color: '#f45757' },
        { id: 5, color: '#27c227' },
        { id: 6, color: '#4587d4' },
        { id: 7, color: '#f3d54f' },
        { id: 8, color: '#f488e1' },
        { id: 9, color: '#57dcd5' },
        { id: 10, color: '#62c3ae' },
        { id: 11, color: '#e78f34' },
        { id: 12, color: '#a03ee4' },
      ],
      currentpalet: 1,
    };
  },
  methods: {
    drawingColorInput(color, id) {
      this.currentpalet = id;
      let drawColor = color;
      this.$store.commit('maincanvas/drawingColor', drawColor);
      // console.log(this.$store.state.drawingColor)
    },
    activeStyle(color) {
      return {
        '--back-paletcolor': color,
      };
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
@import '../../../../sass/common.scss';
div {
  width: 100%;
}
.palet {
  display: flex;
  flex-flow: row wrap;
  justify-content: space-between;
  height: 100px;
  padding: 20px;
  margin: 0;
}
li {
  transition-duration: 0.3s;
  border-radius: 30px;
  width: 45px;
  height: 45px;
  display: flex;
  justify-content: center;
  align-items: center;
  i {
    transition-duration: 0.3s;
    font-size: 24px;
  }
}
.active {
  transition-duration: 0.3s;
  background-color: var(--back-paletcolor);
  box-shadow: 2px 2px 3px rgba($maincolor, 0.15);
  i {
    transition-duration: 0.3s;
    color: white !important;
  }
}
</style>
