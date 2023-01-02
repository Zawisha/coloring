<template>
        <div class="container-fluid front_coloring_list_cont">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <b-modal id="modal-xl" size="lg" ref="my-modal"  hide-footer hide-header>
                        <span class="container" v-if="final_success_message">
                            <div class="col-12 success_marg">
                                Спасибо! Раскраска успешно загружена и отправлена на модерацию.
                            </div>
                            <button type="button" class="btn btn-info" v-on:click="new_coloring()">Загрузить еще раскраску</button>
                            <button type="button" class="btn btn-warning" v-on:click="hide_modal()">Закрыть</button>
                        </span>
                        <span v-else>
                        <preloader-component v-if="preload"></preloader-component>
                        <div>Добавление разукрашенной раскраски: {{ name }}</div>
                        <span v-if="!preload">
                    <div v-if="alert" class="alert alert-danger" role="alert">
                        <ul id="error_list">
                            <li v-for="item in alert_arr" >
                                {{ item }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-12"></div>
                    <div class="add_coloring_title">Имя автора</div>
                    <input class="input_coloring_name search-control form-control" v-model="user_name" placeholder="Кто раскрасил?"  :maxlength="40"  ><div>Укажите пожалуйста возраст</div>
                    <input class="input_coloring_name search-control form-control" v-model="age" placeholder="А сколько вам годиков?" :maxlength="20" >
                    <div class="add_coloring_title">Добавьте свою раскраску</div>
                    <form @submit="formSubmit" enctype="multipart/form-data">
                        <input type="file" class="form-control" v-on:change="imgPreview" name="avatar">
                        <button class="btn btn-light add_coloring_title add_colored_button" :disabled='isDisabled_button'>Загрузить раскраску</button>
                        <div class="col-12 avatar img-fluid img-circle add_c_image justify-content-center" style="margin-top:10px">
                            <img class="col-6" :src="imagepreview"/>
                        </div>
                    </form>
                        </span>
                        </span>

                    </b-modal>
                </div>
            </div>
        </div>
</template>

<script>
import {eventBusColoring} from "../app";
import {eventSearch} from "../app";

export default {
    props: ['auth_user','slug','name', 'slugok'],
    data() {
        return {
            // coloring_list:[],
            // published:1,
            // //текущая страница
            // current_page:1,
            // //по сколько записей на странице выбирать результат
            // offset:0,
            // types_count:0,
            // // ниже сколько всего записей в БД с такими параметрами
            // pagination_all:0,
            // //ниже массив в который добавляем страницы пагинации 1 2 3 и т.д.
            // pagination_numb:[],
            // search_id:'',
            // menu_size:'',
            // menu_data_width:'',
            // menu_data_height:'',
            // menu_data_width_arrow:'',
            // menu_data_height_arrow:'',
            // window_height:'',
            // isStick:true,
            // reklama_number:9999,
            // hide_on_mob:true,
            // like_status_arr:[{12: 'ant-design:like-outlined' },{13: 'ant-design:like-filled'}],
            // like_status:'ant-design:like-outlined',
            // like_status1:'ant-design:like-filled',
            // like1:false,
            // like2:false,


            imagepreview:null,
            chpu:'',
            coloring_name:'',
            alert_arr:[],
            alert:false,
            isDisabled_button: false,
            user_name:'',
            age:'',
            final_success_message:false,
            preload:false,


        };
    },
    mounted() {

    },
    created() {

    },
    methods:{
        hide_modal()
        {
            this.$refs['my-modal'].hide()
        },
        new_coloring()
        {
                this.imagepreview=null,
                this.chpu='',
                this.coloring_name='',
                this.alert_arr=[],
                this.alert=false,
                this.isDisabled_button=false,
                this.final_success_message=false
        },
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
            let colorSlug=''
            if( this.slugok )
            {
                colorSlug  =this.slugok
            }
            else
            {
                let adress=window.location.href;
                colorSlug = adress.split("/")[4];
            }
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
                this.alert_arr.push('Поле Имя не должно быть длиннее 40 символов"');
                this.isActive_name=true;
            }
            if(this.age=="")
            {
                this.alert=true;
                this.alert_arr.push('Заполните поле возраст');
                this.isActive_name=true;
            }
            if((this.age!=="")&&(this.age.length>20))
            {
                this.alert=true;
                this.alert_arr.push('Поле возраст не должно превышать 20 символов');
                this.isActive_name=true;
            }
            if(!this.file)
            {
                this.alert=true;
                this.alert_arr.push('Загрузите изображение');
            }
            if(this.alert==false)
            {
                this.preload=true
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                this.isDisabled_button=true;
                let data = new FormData();
                let user_name=this.user_name;
                let age=this.age;

                data.append('file', this.file);
                data.append('user_name', user_name);
                //нету слага, выделить из url
                data.append('slug', colorSlug);
                data.append('age', age);

                axios.post('/upload_img_user_option'
                    ,data,config)
                    .then(({ data }) => (
                        this.preload=false,
                        this.final_success_message=true
                    ))
                   // .catch((error) => {
                   //     this.isDisabled_button=false;
                  //      this.add_to_errors(error.response.data.errors);
                 //   })

            }
        },

    }
}




</script>
