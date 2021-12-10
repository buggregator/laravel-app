<template>
    <div class="auth">
        <div class="auth__wrap">
            <div class="auth__icon-wrap">
                <Logo class="auth__icon" />
                <h2 class="auth__icon-text">Sign-in</h2>
            </div>
            <form @submit.prevent="submit" class="auth__form">
                <div v-if="hasErrors" class="auth__error-wrap">
                    <div class="auth__error-text">Whoops! Something went wrong.</div>

                    <ul class="auth__error-list">
                        <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
                    </ul>
                </div>

                <div>
                    <label for="username" class="sr-only">Username</label>
                    <input id="username"
                           v-model="form.name"
                           type="text"
                           required
                           class="auth__input"
                           placeholder="Username">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password"
                           v-model="form.password"
                           type="password"
                           autocomplete="current-password"
                           required
                           class="auth__input"
                           placeholder="Password">
                </div>

                <div class="auth__submit-wrap">
                    <button type="submit"
                            class="auth__submit-btn">
                        Sign in
                    </button>

                    <div class="auth__checkbox-wrap">
                        <input id="remember-me" name="remember-me" v-model="form.remember" type="checkbox"
                               class="auth__checkbox">
                        <label for="remember-me" class="auth__checkbox-label">Remember me</label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {computed, defineComponent} from 'vue'
import {usePage} from "@inertiajs/inertia-vue3";
import Logo from "../../Components/UI/Icons/Logo";

export default defineComponent({
    components: {Logo},
    setup() {
        const version = computed(() => usePage().props.value.version)
        const name = computed(() => usePage().props.value.name)

        return {version, name}
    },
    data() {
        return {
            form: this.$inertia.form({
                name: '',
                password: '',
                remember: false
            })
        }
    },

    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    remember: this.form.remember ? 'on' : ''
                }))
                .post(this.route('login'), {
                    onFinish: () => this.form.reset('password'),
                })
        }
    },
    computed: {
        errors() {
            return this.$page.props.errors
        },

        hasErrors() {
            return Object.keys(this.errors).length > 0;
        },
    }
})
</script>
