<template>
    <div v-if="product" class="productDetail">
        <Product :product="product" :productstyle="productStyle" />
        <div>
            <h2>Comments</h2>
            <ul v-if="product.comments.length > 0">
                <li
                v-for="comment in product.comments"
                :key="comment.content"
                >
                    <p>{{comment.content}}</p>
                    <p>{{comment.user.name}}</p>
                </li>
            </ul>
            <p v-else>まだコメントはありません。</p>
            <form v-if="isLogin" @submit.prevent="addComment" >
                <div v-if="commentErrors">
                    <ul v-if="commentErrors.content">
                        <li v-for="msg in commentErrors.content" :key="msg">{{msg}}</li>
                    </ul>
                </div>
                <textarea v-model="commentContent"></textarea>
                <div>
                    <button type="submit">コメントをつける</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import Axios from "axios"
import { OK,CREATED,UNPROCESSABLE_ENTITY } from '../util'
import Product from '../components/index/Product.vue'
export default {
    components:{
        Product
    },
    props:{
        id:{
            type:String,
            required: true
        }
    },
    data(){
        return{
            product: null,
            productStyle:{
                width:'500px',
                height:'500px'
            },
            commentContent: '',
            commentErrors: null
        }
    },
    computed:{
        isLogin(){
            return this.$store.getters['auth/check']
        }
    },
    methods:{
        async showProduct(){
            const response = await axios.get(`/api/products/${this.id}`)
            if(response.status !== OK){
                this.$store.commit('error/setCode', response.status)
                return false
            }
            this.product = response.data
        },
        async addComment(){
            const response = await axios.post(`/api/products/${this.id}/comments`,{
                content: this.commentContent
            })

            if(response.status === UNPROCESSABLE_ENTITY){
                this.commentErrors = response.data.errors
                return false
            }
            this.commentContent = ''
            this.commentErrors = null
            
            if(response.status !== CREATED){
                this.$store.commit('error/setCode',response.status)
                return false
            }

            this.product.comments = [
                response.data,
                ...this.product.comments
            ]
        },
        onLikeClick(){
            if(! this.isLogin){
                alert('いいね機能を使うにはログインしてください。')
                return false
            }

            if(this.product.liked_by_user){
                this.unlike()
            }else{
                this.like()
            }
        },
        async like(){
            const response = await axios.put(`/api/products/${this.id}/like`)

            if(response.status !== OK){
                this.$store.commit('error/setCode', response.status)
                return false
            }

            this.product.likes_count = this.product.likes_count + 1
            this.product.liked_by_user = true
        },
        async unlike(){
            const response = await axios.delete(`/api/products/${this.id}/like`)
            if(response.status !== OK){
                this.$store.commit('error/setCode',response.status)
                return false
            }

            this.product.likes_count = this.product.likes_count - 1
            this.product.liked_by_user = false
        }
    },
    watch:{
        $route:{
            async handler(){
                await this.showProduct()
             },
            immediate: true
        }
    }
}
</script>

<style lang="scss" scoped>
.productDetail{
    display: flex;
    width: 1000px;
    margin: 0 auto;
}
.button--liked{
    background-color: hotpink;
}
</style>