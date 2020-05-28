<template>
    <div id="overlay" @click="closeModal">
        <form id="window" @click="stopClose" @submit.prevent="formEnter">
            <slot></slot>
            <button @click="closeModal">キャンセル</button>
            <button type="submit">OK</button>
        </form>
    </div>
</template>

<script>
export default{
    methods:{
        closeModal(){
            this.$emit('closeModal')
        },
        stopClose(){
            event.stopPropagation()
        },
        formEnter(){
            this.$emit('formEnter')
        }
    }
}
</script>

<style lang="scss" scoped>
#overlay{
    z-index: 1;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    display: flex;
    justify-content: center;
    align-items: center;
}
#window{
        z-index: 901;
        width: 40%;
        height: 70%;
        padding: 40px;
        background: white;
        border-radius:20px;
        input{
            width:100%;
            height:20px;
        }
}
</style>