<template>
    <div class="container ">
        <input v-on:change="check_user" class="car_input" v-model="user_id" placeholder="введите номер юзера"  >
        <div class="car_input">{{ isset }}</div>
<span class="car_input">
        <input type="radio"  value="1" v-model="picked">
        <label for="one">Питер</label>
        <br>
        <input type="radio"  value="2" v-model="picked">
        <label for="two">Москва</label>
        <br>
        <input type="radio"  value="3" v-model="picked">
        <label for="two">Новосиб</label>
        <br>
        <input type="radio"  value="4" v-model="picked">
        <label for="two">Казань</label>
        <br>
        <input type="radio"  value="5" v-model="picked">
        <label for="two">Екатеринбург</label>
        <br>
</span>
    </div>
</template>

<script>

export default {
    data() {
        return {
            user_id:'',
            isset:'',
            picked:1

        };
    },
    mounted() {

    },
    methods: {
        check_user()
        {

            axios.post('/check_user', {
                user_id:this.user_id,
                picked:this.picked,
                }
            )
                .then(({ data }) => (
                        this.isset=data.isset,
                        this.user_id='',
                        navigator.clipboard.writeText(data.text)

        ),

                );

        },
    }
}
</script>

