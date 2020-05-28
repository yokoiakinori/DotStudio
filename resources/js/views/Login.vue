<template>
    <div class="login">
        <ul class="tab">
            <li @click="tab = 1"><p :class="{'tab_item-active':tab === 1,'tab_item-nonactive':tab !== 1}">ログイン</p></li>
            <li @click="tab = 2"><p :class="{'tab_item-active':tab === 2,'tab_item-nonactive':tab !== 2}">会員登録</p></li>
        </ul>
        <div class="panel" v-show="tab === 1">
            <p>ログイン情報を入力してください。</p>
            <form class="form" @submit.prevent="login">
                <div v-if="loginErrors" class="errors">
                    <ul v-if="loginErrors.email">
                        <li v-for="msg in loginErrors.email" :key='msg'>{{ msg }}</li>
                    </ul>
                    <ul v-if="loginErrors.password">
                        <li v-for="msg in loginErrors.password" :key='msg'>{{ msg }}</li>
                    </ul>
                </div>
                <label for="login-email">Email</label>
                <input type="text" class="form__item" id="login-email" v-model="loginForm.email">
                <label for="login-password">パスワード</label>
                <input type="password" class="form__item" id="login-password" v-model="loginForm.password">
                <div class="form__button">
                <button type="submit" class="button button--inverse">ログイン</button>
                </div>
            </form>
        </div>
        <div class="panel" v-show="tab === 2">
            <p>アカウント作成情報を入力してください。</p>
            <form class="form" @submit.prevent="register">
                <div v-if="registerErrors" class="errors">
                    <ul v-if="registerErrors.name">
                        <li v-for="msg in registerErrors.name" :key='msg'>{{ msg }}</li>
                    </ul>
                    <ul v-if="registerErrors.email">
                        <li v-for="msg in registerErrors.email" :key='msg'>{{ msg }}</li>
                    </ul>
                    <ul v-if="registerErrors.password">
                        <li v-for="msg in registerErrors.password" :key='msg'>{{ msg }}</li>
                    </ul>
                </div>
                <label for="username">名前</label>
                <input type="text" class="form__item" id="username" v-model="registerForm.name">
                <label for="email">Email</label>
                <input type="text" class="form__item" id="email" v-model="registerForm.email">
                <label for="password">パスワード</label>
                <input type="password" class="form__item" id="password" v-model="registerForm.password">
                <label for="password-confirmation">パスワード（確認）</label>
                <input type="password" class="form__item" id="password-confirmation" v-model="registerForm.password_confirmation">
                <div class="form__button">
                <button type="submit" class="button button--inverse">アカウント作成</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
    data(){
        return{
            tab: 1,
            loginForm:{
                email:'',
                password:''
            },
            registerForm:{
                name:'',
                email:'',
                password:'',
                password_confirmation:''
            }
        }
    },
    methods:{
        async login(){
            await this.$store.dispatch('auth/login', this.loginForm)

            if (this.apiStatus) {
                this.$router.push('/')
            }
        },
        async register () {
            await this.$store.dispatch('auth/register', this.registerForm)

            if (this.apiStatus) {
                this.$router.push('/')
            }
        },
        clearError(){
            this.$store.commit('auth/setLoginErrorMessages',null)
            this.$store.commit('auth/setRegisterErrorMessages',null)
        }
    },
    created(){
        this.clearError()
    },
    computed:mapState({
            apiStatus: state => state.auth.apiStatus,
            loginErrors: state => state.auth.loginErrorMessages,
            registerErrors: state => state.auth.registerErrorMessages
        })
}
</script>

<style lang="scss" scoped>
@import "../../sass/common.scss";
.login{
    margin: 0 auto;
    width: 500px;
    padding: 30px 0;
}
.tab{
    width: 100%;
    height: 85px;
    border-bottom: solid 2px $maincolor;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-align: center;
    li{
        font-size: 18px;
        border-left: solid 1px $maincolor;
        width: 50%;
        height: 65%;
        display: flex;
        justify-content: center;
        align-items: center;
        &:first-child{
        border-left: none;
        }
        p{
            display: inline-block;
            margin: 0;
        }
    }
}
.tab_item-nonactive{
    transition-duration: 0.4s;
    border-bottom:solid 1.2px rgba($maincolor,0) ;
}
.tab_item-active{
    transition-duration: 0.4s;
    border-bottom:solid 1.2px $maincolor ;
}
.panel p{
    text-align: center;
    font-size: 18px;
    margin: 20px 0 0 0;
}
.form{
    display: flex;
    flex-flow: nowrap column;
    align-items: center;
    label{
        margin-top: 23px;
        width: 100%;
        font-size: 16px;
    }
    input{
        width: 100%;
        height: 32px;
        border-bottom: solid 1px $maincolor;
    }
    button{
        transition-duration: 0.3s;
        width: 200px;
        height: 50px;
        border: solid 1.5px $maincolor;
        border-radius: 30px;
        margin-top: 30px;
        font-size: 20px;
        font-weight: bold;
        color: $maincolor;
        &:hover{
        transition-duration: 0.3s;
        background-color: $maincolor;
        color: white;
    }
    }
}
</style>