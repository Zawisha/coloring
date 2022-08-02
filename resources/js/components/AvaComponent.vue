<template>
        <div class="row">
<!--                <span v-if="auth_user">-->
            <img class="new_head_user_icon " :src="image"/>
            <div class="new_head_user_name d-none d-sm-block col-6 align-self-center" >{{ nickname }}</div>
<!--                </span>-->


        </div>
</template>

<script>
import "vue-advanced-cropper/dist/style.css";
import UploadAvatar from "./UploadAvatar";
export default {
    props: ['auth_user'],
    data() {
        return {
            image: "",
            nickname:'',
            is_not_login:true
        };
    },
    created() {
        this.get_user_params()
    },
    methods: {

        get_user_params()
        {
            if(this.auth_user)
            {
                axios
                    .post('/get_user_params_main',{
                    })
                    .then(({ data }) => (
                            this.image='/images/ava/'+data.ava[0].ava,
                                this.nickname=data.ava[0].nickname
                        )
                    )
            }


        },



        }
}
</script>

