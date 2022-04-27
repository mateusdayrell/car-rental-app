<template>
    <div :class="style" role="alert">
        {{ title }}
        <hr>
        <p>{{ alertMessage.message }}</p>
        <br>
        <ul v-if="alertMessage.data">
            <li v-for="e, key in alertMessage.data" :key="key"> {{ e[0] }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['type', 'title', 'alertMessage'],
        computed: {
            style() {
                return 'alert alert-'+this.type
            }
        }
    }
</script>
