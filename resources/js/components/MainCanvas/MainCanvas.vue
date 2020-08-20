<template>
	<div id="MainCanvas">
		<ul>
			<Dot
				v-for="item in allCanvasDot"
				:key="item"
				:dotid="item"
				:drawingJudgement="drawingJudgement"
				:lineDotVolume="lineDotVolume"
				:color="color[item - 1]"
				@mousedown.native="dragStart(item)"
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
			allCanvasDot: 0,
			lineDotVolume: 0,
			dots: [],
			color: [],
			firstClick: null,
			secondClick: null,
			fillColor: null,
			materialColor: null,
		};
	},
	computed: mapState({
		saveStatus: state => state.maincanvas.saveStatus,
		currentProduct: state => state.maincanvas.currentProduct,
		drawingTool: state => state.maincanvas.drawingTool,
		drawingColor: state => state.maincanvas.drawingColor,
		currentMaterial: state => state.maincanvas.currentMaterial,
		saveMaterial: state => state.maincanvas.saveMaterial,
	}),
	watch: {
		saveStatus() {
			this.$nextTick(function() {
				this.saveConnect();
			});
		},
		async currentProduct(val) {
			//メニューからプロダクトが選択された時実行
			await this.beforeCurrentReset();
			this.allCanvasDot = this.$store.state.maincanvas.allDotVolume;
			this.$nextTick(function() {
				this.lineDotVolume = this.$store.state.maincanvas.lineDotVolume;
			});
			await this.deploymentDot(val);
		},
		drawingTool(val) {
			if (val == 'reset') {
				this.drawReset();
			}
		},
		secondClick() {
			if (this.drawingTool == 'line') {
				this.lineDraw();
			} else if (this.drawingTool == 'square') {
				this.squareDraw();
			} else if (this.drawingTool == 'squareline') {
				this.squarelineDraw();
			}
		},
		fillColor() {
			this.fillDraw();
		},
		currentMaterial() {
			this.setMaterialColor();
		},
	},
	methods: {
		dragStart(val) {
			this.drawingJudgement = true;
			if (['line', 'square', 'squareline'].includes(this.drawingTool) && this.firstClick == null) {
				this.firstClick = val;
			} else if (
				['line', 'square', 'squareline'].includes(this.drawingTool) &&
				this.secondClick == null
			) {
				this.secondClick = val; //check [watch:secondClick()]
			} else if (this.drawingTool == 'fill') {
				this.fillColor = this.color[val - 1]; //check [watch:fillColor()]
			} else if (this.drawingTool == 'stamp') {
				this.drawStamp(val);
			}
		},
		dragEnd() {
			this.drawingJudgement = false;
		},
		saveProduct(data) {
			this.dots.push(data);
		},
		async saveConnect() {
			const response = await axios.post('/api/products/save', {
				currentProduct: this.currentProduct,
				dots: this.dots,
			});
			this.dots.length = 0;
		},
		async deploymentDot(val) {
			const response = await axios.post('/api/products/current', { id: val });
			for (let i = 1; i <= this.allCanvasDot; i++) {
				const currentcolor = response.data[i - 1];
				this.color.push(currentcolor);
			}
		},
		async beforeCurrentReset() {
			this.color.length = 0;
			for (let i = 1; i <= this.allCanvasDot; i++) {
				this.color.push('white');
			}
			this.$nextTick(function() {
				this.color.length = 0;
			});
			await this.sleep();
		},
		sleep() {
			return new Promise(resolve => {
				setTimeout(() => {
					resolve();
				}, 250);
			});
		},
		lineDraw() {
			const startNumber = Math.min(this.firstClick, this.secondClick);
			const lastNumber = Math.max(this.firstClick, this.secondClick);
			const differenceNumber = lastNumber - startNumber;
			//横
			if (differenceNumber < this.lineDotVolume) {
				for (let i = startNumber; i <= lastNumber; i++) {
					this.color[i - 1] = this.drawingColor;
				}
			}
			//縦
			else if (differenceNumber % this.lineDotVolume == 0) {
				const count = differenceNumber / this.lineDotVolume;
				for (let i = 1; i <= count; i++) {
					this.color[startNumber + i * this.lineDotVolume - 1] = this.drawingColor;
				}
			}
			//斜め（45度）
			else if (differenceNumber % (this.lineDotVolume + 1) == 0) {
				const count = differenceNumber / this.lineDotVolume;
				for (let i = 1; i <= count; i++) {
					this.color[startNumber + i * (this.lineDotVolume + 1) - 1] = this.drawingColor;
				}
			}
			this.firstClick = null;
			this.secondClick = null;
		},
		squareDraw() {
			const startNumber = Math.min(this.firstClick, this.secondClick); //変数定義が重複しているがよりよい書き方がわからないため放置
			const lastNumber = Math.max(this.firstClick, this.secondClick);
			const differenceNumber = lastNumber - startNumber;
			const count = differenceNumber / this.lineDotVolume;
			const rowEndNumber = startNumber + (differenceNumber % this.lineDotVolume);
			for (let i = 0; i <= count; i++) {
				for (let j = startNumber; j <= rowEndNumber; j++) {
					this.color[j + i * this.lineDotVolume - 1] = this.drawingColor;
				}
			}
			this.firstClick = null;
			this.secondClick = null;
		},
		squarelineDraw() {
			const startNumber = Math.min(this.firstClick, this.secondClick); //変数定義が重複しているがよりよい書き方がわからないため放置
			const lastNumber = Math.max(this.firstClick, this.secondClick);
			const differenceNumber = lastNumber - startNumber;
			const count = differenceNumber / this.lineDotVolume;
			const rowEndNumber = startNumber + (differenceNumber % this.lineDotVolume);
			const columnEndNumber = lastNumber - (differenceNumber % this.lineDotVolume);
			for (let i = startNumber; i <= rowEndNumber; i++) {
				this.color[i - 1] = this.drawingColor;
			}
			for (let i = 1; i <= count; i++) {
				this.color[startNumber + i * this.lineDotVolume - 1] = this.drawingColor;
			}
			for (let i = columnEndNumber; i <= lastNumber; i++) {
				this.color[i - 1] = this.drawingColor;
			}
			for (let i = 1; i <= count; i++) {
				this.color[rowEndNumber + i * this.lineDotVolume - 1] = this.drawingColor;
			}
			this.firstClick = null;
			this.secondClick = null;
		},
		fillDraw() {
			let checkInvalid = true;
			for (let i = 0; i <= this.lineDotVolume; i++) {
				for (let j = 1; j <= this.lineDotVolume; j++) {
					if (this.color[j + i * this.lineDotVolume - 1] == this.fillColor) {
						this.color[j + i * this.lineDotVolume - 1] = this.drawingColor;
					}
				}
			}
			this.fillColor = null;
		},
		drawStamp(start) {
			if (this.currentMaterial.alldot > this.allCanvasDot) {
				alert('スタンプが描画領域よりも大きいです！');
			} else if (
				start % this.lineDotVolume > this.lineDotVolume - this.currentMaterial.linedot + 1 ||
				start / this.lineDotVolume > this.lineDotVolume - this.currentMaterial.linedot + 1
			) {
				alert('この位置では描画領域よりはみ出してしまいます！');
			} else {
				const lineEnd = start + this.currentMaterial.linedot;
				for (let i = 0; i < this.currentMaterial.linedot; i++) {
					for (let j = start; j <= lineEnd; j++) {
						this.color[j + i * this.lineDotVolume - 1] = this.materialColor[
							j - start + i * this.currentMaterial.linedot
						];
					}
				}
				if (!this.saveMaterial.includes(this.currentMaterial.id)) {
					this.$store.commit('maincanvas/setSaveMaterial', this.currentMaterial.id);
				}
			}
		},
		drawReset() {
			const answer = confirm('初期化してもよろしいですか？');
			if (answer) {
				this.color.length = 0;
				for (let i = 1; i <= this.allCanvasDot; i++) {
					this.color.push('white');
					this.$nextTick(function() {
						this.color.length = 0;
					});
				}
			}
		},
		setMaterialColor() {
			const colors = this.currentMaterial.colors.split('_');
			colors.pop();
			this.materialColor = colors;
		},
	},
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
@import '../../../sass/common.scss';
#MainCanvas {
	width: 60%;
	height: 60vw;
	max-height: 630px;
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
