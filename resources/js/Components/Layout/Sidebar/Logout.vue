<template>
    <form v-if="isLoggedIn" :action="route('logout')" method="post" class="logout">
        <button type="submit" title="Logout" class="logout__btn-submit">
            <span>
                <LogoutIcon />
            </span>
        </button>
    </form>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3'
import LogoutIcon from "../../UI/Icons/LogoutIcon";

export default {
    components: {LogoutIcon, Link},
    data() {
        return {
            user: null
        }
    },
    mounted() {
        this.user = this.$page.props.user.data || null
    },
    computed: {
        isLoggedIn() {
            return this.user !== null
        }
    }
}
</script>
