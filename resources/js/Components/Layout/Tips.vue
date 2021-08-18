<template>
<div class="mt-3 text-gray-600">
    <h3 class="text-xl font-bold">Settings</h3>
    <ul class="list-disc pl-4">
        <li>Ray host <span class="font-bold">{{ server.host }}</span>, port <span class="font-bold">{{ server.port }}</span></li>
        <li>Sentry DSN <span class="font-bold">http://sentry@{{ server.host }}:{{ server.port }}/1</span></li>
        <li>Log channel <span class="font-bold">Slack</span>, webhook url <span class="font-bold">http://{{ server.host }}:{{ server.port }}/slack</span></li>
    </ul>
</div>
</template>

<script>
export default {
    computed: {
        server() {
            const [host, port] = window.location.host.split(':')
            return {
                host, port
            }
        }
    }
}
</script>
