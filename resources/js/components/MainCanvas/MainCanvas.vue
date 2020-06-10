<template>
  <div id="MainCanvas">
    <ul>
      <Dot
        v-for="item in allCanvasDot"
        v-bind:key="item"
        v-bind:dotid="item"
        v-bind:drawingJudgement="drawingJudgement"
        v-bind:lineDotVolume="lineDotVolume"
        v-bind:color="color[item-1]"
        @mousedown.native="dragStart"
        @mouseup.native="dragEnd"
        @saveProduct="saveProduct"
      ></Dot>
    </ul>
  </div>
</template>

<script>
import Dot from './Dot.vue';
import { mapState } from 'vuex';
export default {
  components: {
    Dot,
  },
  data() {
    return {
      drawingJudgement: false,
      lineDotVolume: 0,
      dots: [],
      color: [],
    };
  },
  computed: mapState({
    allCanvasDot: state => state.maincanvas.allDotVolume,
    saveStatus: state => state.maincanvas.saveStatus,
    currentProduct: state => state.maincanvas.currentProduct,
  }),
  watch: {
    allCanvasDot() {
      this.$nextTick(function() {
        this.lineDotVolume = this.$store.state.maincanvas.lineDotVolume;
      });
    },
    saveStatus() {
      this.$nextTick(function() {
        this.saveConnect();
      });
    },
    async currentProduct(val) {
      const response = await axios.post('/api/products/current', { id: val });
      for (let i = 1; i <= this.allCanvasDot; i++) {
        const currentcolor = response.data[i - 1];
        this.color.push(currentcolor);
        this.$nextTick(function() {
          this.color.length = 0;
        });
      }
    },
  },
  methods: {
    dragStart() {
      this.drawingJudgement = true;
    },
    dragEnd() {
      this.drawingJudgement = false;
    },
    saveProduct(data) {
      this.dots.push(data);
    },
    async saveConnect() {
      // console.log(this.dots)
      const response = await axios.post('/api/products/save', {
        currentProduct: this.currentProduct,
        dots: this.dots,
      });
      this.dots.length = 0;
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
@import '../../../sass/common.scss';
#MainCanvas {
  width: 60%;
  height: 60%;
  margin-right: 5%;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: white;
  box-shadow: 2px 2px 3px rgba($maincolor, 0.15);
  ul {
    width: 90%;
    height: 90%;
    padding: 0;
    display: flex;
    list-style-type: none;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-around;
    align-items: center;
    margin: 0;
  }
}
</style>
