<template>
    <tr class="bg-gray-50 border-b border-gray-200">
        <th class="px-4 py-2 text-left" v-if="title !== null" v-html="title"></th>
        <td class="px-4 py-2"><slot></slot></td>
    </tr>
</template>

<script>
export default {
    props: {
        title: String
    }
}
</script>
