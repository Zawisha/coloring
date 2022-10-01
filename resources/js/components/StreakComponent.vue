<template>
    <div class="container">
        <div v-on:click="go_to_user_add_coloring" class="double-border-button col-12">Добавить раскраску</div>
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

