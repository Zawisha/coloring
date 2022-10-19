<template>
    <div class="container no_padding_left">
        <div v-on:click="go_to_user_add_coloring" class="double-border-button col-10 no_padding_left">Добавить раскраску</div>
    </div>
</template>

<script>

export default {
    data() {
        return {

        };
    },
    mounted() {

    },
    methods: {
        go_to_user_add_coloring()
        {
            window.location.href = '/add_coloring_user'
        },
    }
}
</script>

