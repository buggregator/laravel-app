<template>
    <section class="sentry-tags">
        <h3 class="sentry-tags__title">tags</h3>

        <div class="sentry-tags__type-wrap">
            <div v-if="event.runtime" class="sentry-tags__type-event">
                <span class="sentry-tags__event-title">runtime</span>
                <h4 class="font-bold">{{ event.runtime.name }}</h4>
                <p class="text-sm">Version: {{ event.runtime.version }}</p>
            </div>

            <div v-if="event.os" class="sentry-tags__type-event">
                <span class="sentry-tags__event-title">os</span>
                <h4 class="font-bold">{{ event.os.name }}</h4>
                <p class="text-sm">Version: {{ event.os.version }}</p>
            </div>

            <div v-if="event.sdk" class="sentry-tags__type-event">
                <span class="sentry-tags__event-title">sdk</span>
                <h4 class="font-bold">{{ event.sdk.name }}</h4>
                <p class="text-sm">Version: {{ event.sdk.version }}</p>
            </div>
        </div>

        <div class="sentry-tags__list">
            <div class="sentry-tags__item">
                <div class="sentry-tags__item-name">env</div>
                <div class="sentry-tags__item-value">{{ event.environment }}</div>
            </div>
            <div class="sentry-tags__item" v-if="event.logger">
                <div class="sentry-tags__item-name">logger</div>
                <div class="sentry-tags__item-value">{{ event.logger }}</div>
            </div>
            <div class="sentry-tags__item">
                <div class="sentry-tags__item-name">os</div>
                <div class="sentry-tags__item-value">{{ event.os.name }} {{ event.os.version }}</div>
            </div>
            <div class="sentry-tags__item">
                <div class="sentry-tags__item-name">runtime</div>
                <div class="sentry-tags__item-value">{{ event.runtime.name }} {{ event.runtime.version }}</div>
            </div>
            <div class="sentry-tags__item">
                <div class="sentry-tags__item-name">server name</div>
                <div class="sentry-tags__item-value">{{ event.serverName }}</div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    props: {
        event: Object
    }
}
</script>
