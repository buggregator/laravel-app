<template>
    <div ref="trace">
        <ssh-pre language="sql" class="text-sm w-2/3 mb-2">
            {{ formattedSql }}
        </ssh-pre>

        <ul>
            <li class="border-b text-gray-800">Connection name: <strong>{{ this.payload.content.connection_name }}</strong></li>
            <li class="border-b text-gray-800">Time: <strong>{{ this.payload.content.time }}ms</strong></li>
        </ul>
    </div>
</template>

<script>
import SshPre from 'simple-syntax-highlighter'
import 'simple-syntax-highlighter/dist/sshpre.css'

export default {
    components: {SshPre},
    props: {
        payload: Object
    },
    computed: {
        formattedSql() {
            return this.payload.content.bindings.reduce((sql, currentValue) => {
                return sql.replace(/\?/, `'${currentValue}'`)
            }, this.payload.content.sql)
        }

    }
}
</script>
