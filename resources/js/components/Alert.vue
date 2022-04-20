<template>
    <div :class="style" role="alert">
        {{ title }}
        <hr>
        <span v-if="message.data.message">{{ message.message}}</span>
        <span v-if="message.data.id">{{ 'ID do registro: '+message.data.id }}</span>
        <br>
        <ul v-if="message.content">
            <li v-for="e, key in message.content" :key="key">{{ e[0] }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['type', 'title', 'message'],
        computed: {
            style() {
                return 'alert alert-'+this.type
            }
        }
    }
</script>
