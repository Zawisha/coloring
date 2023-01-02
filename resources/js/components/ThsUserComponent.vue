<template>
    <span class="container">
        <div class="col-12 success_marg">
            Спасибо! Раскраска успешно загружена и отправлена на модерацию.
        </div>
        <button type="button" class="btn btn-info" v-on:click=" go_to()">Загрузить еще раскраску</button>
    </span>
</template>

<script>

export default {
    methods: {
        go_to()
        {
            window.location.href = '/'
        },
}
}
</script>

