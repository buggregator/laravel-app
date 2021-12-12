<template>
    <section class="sentry-breadcrumbs">
        <h3 class="sentry-breadcrumbs__title">breadcrumbs</h3>

        <div style="height: 400px;" class="sentry-breadcrumbs__wrap">
            <nav style="grid-template-columns: 1fr 100px 200px 17px" class="sentry-breadcrumbs__nav">
                <div class="sentry-breadcrumbs__nav-item">description</div>
                <div class="sentry-breadcrumbs__nav-item">level</div>
                <div class="sentry-breadcrumbs__nav-item">time</div>
            </nav>
            <div class="sentry-breadcrumbs__list">
                <div style="grid-template-columns: 1fr 100px 200px" class="sentry-breadcrumbs__item" v-for="b in event.breadcrumbs">
                    <div class="p-3">
                        <p class="font-bold">{{ b.message }}</p>

                        <div class="sentry-breadcrumbs__item-info-wrap">
                            <div class="sentry-breadcrumbs__item-info">
                                <div class="sentry-breadcrumbs__item-name">type</div>
                                <div class="sentry-breadcrumbs__item-value">{{ b.type }}</div>
                            </div>
                            <div class="sentry-breadcrumbs__item-info">
                                <div class="sentry-breadcrumbs__item-name">category</div>
                                <div class="sentry-breadcrumbs__item-value">{{ b.category }}</div>
                            </div>
                        </div>

                        <CodeSnippet
                            v-if="b.data"
                            class="mt-3 sentry-breadcrumbs__snippet"
                            language="json">{{ b.data }}
                        </CodeSnippet>
                    </div>
                    <div class="p-3">
                        <Label class="sentry-breadcrumbs__label">{{ b.level }}</Label>
                    </div>
                    <div class="p-3">{{ date(b.timestamp).fromNow() }}</div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import Dump from "../../UI/Dump";
import CodeSnippet from "../../UI/CodeSnippet";
import Label from "../../UI/Label";
import moment from "moment";
export default {
    components: {CodeSnippet, Dump, Label},
    props: {
        event: Object
    },
    methods: {
        date(timestamp) {
            return moment.unix(timestamp)
        }
    }
}
</script>
