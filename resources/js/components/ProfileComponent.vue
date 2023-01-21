<template>
    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <img class="rounded-circle mt-5" v-if="!image&&postFontSize" width="150px" :src="ava_img">
<!--                    <upload-avatar v-if="!image" @submit="handleImageUpload"/>-->
                    <div>Текущий аватар</div>
                    <img class="flag"  :src="image"/>
                    <upload-avatar  @submit="handleImageUpload" v-on:enlarge-text="postFontSize = $event" />
                    <span class="font-weight-bold">{{ nickname }}</span>
                    <span class="text-black-50">{{ email }}</span>
                    <span>
                    </span>
                </div>
            </div>
            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div v-if="success_add_final" class="alert alert-success" role="alert">
                        Профиль отредактирован успешно
                    </div>
                    <div v-if="alert" class="alert alert-danger" role="alert">
                        <ul id="error_list">
                            <li v-for="item in alert_arr" >
                                {{ item }}
                            </li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="text-right prof_set_text">Настройки профиля</h1>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Почта</label><input v-on:click="frame_dis()" v-model="email" type="text" class="form-control" placeholder="ваша почта" value=""></div>
                        <div class="col-md-12"><label class="labels">Никнейм</label><input v-on:click="frame_dis()" v-model="nickname" type="text" class="form-control" placeholder="ваш ник" value=""></div>
                    </div>
                    <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="button" v-on:click="change_profile_data()">Сохранить профиль</button></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center experience" ><span v-on:click="go_to_liked()" class="liked_profile_button border col-12 px-3 p-1 add-experience"><i class="fa fa-plus"></i>&nbsp;Избранное</span></div><br>
                    <div class="d-flex justify-content-between align-items-center experience" ><span v-on:click="go_to_decorated()" class="liked_profile_button border col-12 px-3 p-1 add-experience"><i class="fa fa-plus"></i>&nbsp;Мои раскрашки</span></div><br>
                    <div class="col-md-12"><label class="labels profile_text" v-on:click="change_password()">Поменять пароль</label></div><br>
                    <div class="col-md-12"><label v-on:click="logout()" class="labels profile_text">Выйти</label></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import "vue-advanced-cropper/dist/style.css";
import UploadAvatar from "./UploadAvatar";
export default {
    // components: { VueCropper },
    components: {
        UploadAvatar,
    },
    props: ['auth_user'],
    data() {
        return {
            email:'',
            nickname:'',
            alert_arr:[],
            alert:false,
            success_add_final:false,
            image: "",
            ava_img:'',
            start_image:true,
            postFontSize:true
        };
    },
    created() {
        this.get_user_params()
    },
    methods: {

        handleImageUpload(image) {
            console.log(this.postFontSize)
            this.image = image;

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            // console.log(this.image)

            axios
                .post('/upload_crop',{
                    img:this.image
                })

        },


        frame_dis()
        {
          this.alert_arr=[];
          this.alert=false;
          this.success_add_final=false;
        },
        go_to_liked()
        {
            window.location.href =('/liked')
        },
        go_to_decorated()
        {
            window.location.href =('/decorated_user_list')
        },
        change_password()
        {
            window.location.href =('/new_password')
        },
        get_user_params()
        {
            this.email=this.auth_user.email
            this.nickname=this.auth_user.nickname
            axios
                .post('/get_user_params',{
                })
                .then(({ data }) => (
                        this.ava_img='/images/ava/'+data.ava[0].ava
                    )
                )

        },
        change_profile_data()
        {
            axios
                .post('/change_profile_data',{
                    email:this.email,
                    nickname:this.nickname
                })
                .then(({ data }) => (
                    this.success_add_final=true
                    )
                )
                .catch((error) => {
                    this.add_to_errors(error.response.data.errors);
                });
        },
        add_to_errors(inp_errors)
        {
            this.alert=true;
            let temp_arr=[];
            for (const [key, value] of Object.entries(inp_errors)) {
                value.forEach(function(number) {
                    temp_arr.push(number)
                });
            }
            this.alert_arr=temp_arr;
        },
        logout()
        {
            axios
                .post('/logout',{
                })
                .then(({ data }) => (
                    window.location.href =('/login')
                    )
                )

        }


        }
}
</script>
<style>
#app {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    margin-top: 60px;
}
</style>
