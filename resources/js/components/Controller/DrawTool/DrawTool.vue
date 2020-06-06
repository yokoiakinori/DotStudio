<template>
  <div>
    <ul>
      <li>
        <i class="fas fa-paint-brush"></i>
      </li>
      <li>
        <i class="fas fa-eraser"></i>
      </li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
    </ul>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
@import '../../../../sass/common.scss';
div {
  width: 100%;
  ul {
    padding: 0;
    display: flex;
    flex-flow: row wrap;
    justify-content: space-between;
    padding: 20px;
  }
}
i {
  color: rgba($maincolor, 0.3);
  font-size: 30px;
}
</style>>
