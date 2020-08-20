<template>
	<footer class="footer">
		<button v-if="isLogin" class="button button--link" @click="logout">ログアウト</button>
		<RouterLink v-else class="button button--link" to="/login">ログイン / 会員登録</RouterLink>
	</footer>
</template>

<script>
import { mapState, mapGetters } from 'vuex';

export default {
	methods: {
		async logout() {
			await this.$store.dispatch('auth/logout');

			if (this.apiStatus) {
				this.$router.push('/login');
			}
		},
	},
	computed: {
		...mapState({
			apiStatus: state => state.auth.apiStatus,
		}),
		...mapGetters({
			isLogin: 'auth/check',
		}),
	},
};
</script>

<style lang="scss" scoped>
footer {
	height: 100px;
	padding-top: 30px;
}
button {
	display: inline-block;
	width: 100%;
	text-align: center;
	margin: 0;
}
a {
	display: inline-block;
	width: 100%;
	text-align: center;
	margin: 0;
}
</style>
