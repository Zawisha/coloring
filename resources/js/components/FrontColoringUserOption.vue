<template>
        <div class="container-fluid front_coloring_list_cont">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div v-if="alert" class="alert alert-danger" role="alert">
                        <ul id="error_list">
                            <li v-for="item in alert_arr" >
                                {{ item }}
                            </li>
                        </ul>
                    </div>

                    <div class="add_coloring_title">Имя автора</div>
                    <input class="input_coloring_name search-control form-control" v-model="user_name" placeholder="введите название"  :maxlength="70"  ><div>Укажите пожалуйста возраст</div>
                    <input class="input_coloring_name search-control form-control" v-model="age" placeholder="slug" :maxlength="70" >
                    <div class="add_coloring_title">Добавьте свою раскраску</div>
                    <form @submit="formSubmit" enctype="multipart/form-data">
                        <input type="file" class="form-control" v-on:change="imgPreview" name="avatar">
                        <button class="btn btn-light add_coloring_title" :disabled='isDisabled_button'>Загрузить раскраску</button>
                        <div class="col-12 avatar img-fluid img-circle add_c_image justify-content-center" style="margin-top:10px">
                            <img class="col-6" :src="imagepreview"/>
                        </div>
                    </form>
                </div>
            </div>

        </div>
</template>

<script>
import {eventBusColoring} from "../app";
import {eventSearch} from "../app";

export default {
    props: ['auth_user','slug'],
    data() {
        return {
            coloring_list:[],
            published:1,
            //текущая страница
            current_page:1,
            //по сколько записей на странице выбирать результат
            offset:0,
            types_count:0,
            // ниже сколько всего записей в БД с такими параметрами
            pagination_all:0,
            //ниже массив в который добавляем страницы пагинации 1 2 3 и т.д.
            pagination_numb:[],
            search_id:'',
            menu_size:'',
            menu_data_width:'',
            menu_data_height:'',
            menu_data_width_arrow:'',
            menu_data_height_arrow:'',
            window_height:'',
            isStick:true,
            reklama_number:9999,
            hide_on_mob:true,
            like_status_arr:[{12: 'ant-design:like-outlined' },{13: 'ant-design:like-filled'}],
            like_status:'ant-design:like-outlined',
            like_status1:'ant-design:like-filled',
            like1:false,
            like2:false,
            preload:false,

            imagepreview:null,
            chpu:'',
            coloring_name:'',
            alert_arr:[],
            alert:false,
            isDisabled_button: false,
            user_name:'',
            age:''

        };
    },
    mounted() {

    },
    created() {

    },
    methods:{
        imgPreview(e) {
            this.file = e.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(this.file);
            if (this.file.type.match('image.*')) {
                reader.onload = e => {
                    this.imagepreview=e.target.result;
                }
            }
            // console.log(this.file);
        },
        formSubmit(e) {

            e.preventDefault();
            this.alert_arr=[];
            this.alert=false;
            if(this.user_name=="")
            {
                this.alert=true;
                this.alert_arr.push('Заполните поле "Имя автора"');
                this.isActive_name=true;
            }
            if((this.user_name.length<2)&&(this.user_name!==""))
            {
                this.alert=true;
                this.alert_arr.push('Поле "Имя не должно быть короче 2 символов"');
                this.isActive_name=true;
            }
            if((this.user_name.length>70)&&(this.user_name!==""))
            {
                this.alert=true;
                this.alert_arr.push('Поле Имя не должно быть длиннее 70 символов"');
                this.isActive_name=true;
            }
            if(this.age=="")
            {
                this.alert=true;
                this.alert_arr.push('Заполните поле возраст');
                this.isActive_name=true;
            }
            if(!this.file)
            {
                this.alert=true;
                this.alert_arr.push('Загрузите изображение');
            }
            if(this.alert==false)
            {
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                this.isDisabled_button=true;
                let data = new FormData();
                let user_name=this.user_name;
                let age=this.age;
                let slug=this.slug;

                data.append('file', this.file);
                data.append('user_name', user_name);
                data.append('slug', slug);
                data.append('age', age);

                axios.post('/upload_img_user_option'
                    ,data,config)
                   // .then(function (res) {
                  //      window.location.href =('/admin/add_coloring_success')
                  //  })
                   // .catch((error) => {
                   //     this.isDisabled_button=false;
                  //      this.add_to_errors(error.response.data.errors);
                 //   })

            }
        },

    }
}




</script>
