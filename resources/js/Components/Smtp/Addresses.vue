<template>
    <div class="smtp-address">
       <div class="smtp-address__item" v-for="user in addresses">
           <span class="smtp-address__item-text" v-if="user.name">{{ user.name }}</span> <span>{{ user.email }}</span>
       </div>
    </div>
</template>

<script>
export default {
    props: {
        addresses: Array
    }
}
</script>
