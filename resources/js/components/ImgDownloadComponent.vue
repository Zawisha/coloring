<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div v-if="alert" class="alert alert-danger" role="alert">
                    <ul id="error_list">
                        <li v-for="item in alert_arr" >
                            {{ item }}
                        </li>
                    </ul>
                </div>
                <div v-if="published">Опубликовано <input type="checkbox" id="checkbox" v-model="published"></div>
                <div v-else>Не опубликовано <input type="checkbox" id="checkbox1" v-model="published"></div>
                <div class="add_coloring_title">Название раскраски</div>
                <input class="input_coloring_name search-control form-control"v-on:change="slugCheck" v-model="coloring_name" placeholder="введите название" v-bind:class="{ red_border: isActive_name }" :maxlength="70" v-on:focus=delete_red_border_name() >
                <div>ЧПУ</div>
                <input class="input_coloring_name search-control form-control" v-model="chpu" placeholder="slug" :maxlength="70" >
                <div class="add_coloring_title">Описание раскраски</div>
                <textarea class="input_coloring_name search-control form-control" v-model="description" placeholder="введите описание" rows=5 v-bind:class="{ red_border: isActive_description }" :maxlength="300" v-on:focus=delete_red_border_desc()></textarea>
                <div class="add_coloring_title">Добавьте теги</div>
                <div class="col-md-8">
                    <autocomplete
                        :search="search"
                        :get-result-value="getResultValue"
                        :debounce-time="500"
                        @submit="handleSubmit"
                        v-on:focus=delete_bars()
                    ></autocomplete>
                </div>
                <div class="row float-left add_tag_in_row">
                <div v-if="search_result!=''" class="tag_title_coloring col-4">Выбран тег: {{ search_result }}</div>
                <button v-if="search_result!=''" v-on:click="add_tag_to_tag_list()" class="btn btn-light col-4" >Добавить тег</button>
                </div>
                <div v-for="tag in tag_list" class="col-4 colored_tags customers-list-item d-flex align-items-center border-top border-bottom _list borders_tag_list cursor-pointer">
                    <div class="col-12" v-on:click="delete_tag_from_tag_list(tag.id)">
                        <h6 class="font-14">{{ tag.name }}</h6>
                    </div>
                </div>

                <div class="add_coloring_title">Добавьте категории</div>
                <div class="col-md-8">
                    <autocomplete
                        :search="search_cat"
                        :get-result-value="getResultValue_cat"
                        :debounce-time="500"
                        @submit="handleSubmit_cat"
                        v-on:focus=delete_bars()
                    ></autocomplete>
                </div>
                <div class="row float-left add_tag_in_row">
                    <div v-if="search_result_cat!=''" class="tag_title_coloring col-4">Выбрана категория: {{ search_result_cat }}</div>
                    <button v-if="search_result_cat!=''" v-on:click="add_cat_to_cat_list()" class="btn btn-light col-4" >Добавить категорию</button>
                </div>
                <div v-for="tag in cat_list" class="col-4 colored_tags customers-list-item d-flex align-items-center border-top border-bottom _list borders_tag_list cursor-pointer">
                    <div class="col-12" v-on:click="delete_cat_from_cat_list(tag.id)">
                        <h6 class="font-14">{{ tag.name }}</h6>
                    </div>
                </div>

                <div class="add_coloring_title">Добавьте изображение</div>
                <div class="col-12 row reklama_row_main_list">
                    <div id="yandex_rtb_R-A-1785111-6"></div>
                </div>
                <form @submit="formSubmit" enctype="multipart/form-data">
                    <input type="file" class="form-control" v-on:change="imgPreview" name="avatar">
                    <button class="btn btn-light-img add_coloring_title" :disabled='isDisabled_button'>Загрузить раскраску</button>
                    <div class="col-12 avatar img-fluid img-circle add_c_image justify-content-center" style="margin-top:10px">
                        <img class="col-6" :src="imagepreview"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
let slug = require('slug')
export default {
    data() {
        return {
            alert:false,
            alert_arr:[],
            selected_category:'',
            categories:[],
            isActive_name:false,
            isActive_description:false,
            coloring_name:'',
            description:'',
            name: '',
            file: '',
            success: '',
            imagepreview:null,
            isDisabled_button: false,
            search_result:'',
            search_result_id:'',
            search_result_cat:'',
            search_result_id_cat:'',
            tag_list:[],
            success_added:false,
            published:false,
            cat_list:[],
            chpu:''
        };
    },
    mounted() {

    },
    updated() {
        this.show()
    },
    methods: {
        slugCheck(){
            this.chpu=slug(this.coloring_name)
        },
        get_categories(inp)
        {
            axios
                .post('/get_categories',{
                })
                .then(({ data }) => (
                        data.forEach(function(entry) {
                            inp.push({
                                id:entry.id,
                                name:entry.name
                            });
                        })
                    )
                );
        },
        delete_tag_from_tag_list(id)
        {
            let tag_temp_id=id
            this.tag_list.forEach(function(item,i,arr) {
                if(item.id==tag_temp_id)
                {
                    arr.splice(i, 1);                }
            });
        },
        delete_cat_from_cat_list(id)
        {
            let tag_temp_id=id
            this.cat_list.forEach(function(item,i,arr) {
                if(item.id==tag_temp_id)
                {
                    arr.splice(i, 1);                }
            });
        },
        add_tag_to_tag_list()
        {
            let tag_temp_id=this.search_result_id
            let flag=false
                this.tag_list.forEach(function(item,i,arr) {
                    if(item.id==tag_temp_id)
                    {
                            flag=true
                    }
                });
                if(!flag) {
                    this.tag_list.push({
                        id: this.search_result_id,
                        name: this.search_result,
                    });
                }
        },
        add_cat_to_cat_list()
        {
            let tag_temp_id=this.search_result_id_cat
            let flag=false
            this.cat_list.forEach(function(item,i,arr) {
                if(item.id==tag_temp_id)
                {
                    flag=true
                }
            });
            if(!flag) {
                this.cat_list.push({
                    id: this.search_result_id_cat,
                    name: this.search_result_cat,
                });
            }
        },
        show()
        {
            window.yaContextCb.push(()=>{
                Ya.Context.AdvManager.render({
                    renderTo: 'yandex_rtb_R-A-1785111-6',
                    blockId: 'R-A-1785111-6'
                })
            })
        },
        delete_bars()
        {
            this.alert=false;
            this.success_added=false
        },
        delete_red_border_name()
        {
            this.isActive_name=false;
        },
        delete_red_border_desc()
        {
            this.isActive_description=false;
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
            e.preventDefault();
            this.alert_arr=[];
            this.alert=false;
            if(this.coloring_name=="")
            {
                this.alert=true;
                this.alert_arr.push('Заполните поле "Название раскраски"');
                this.isActive_name=true;
            }
            if((this.coloring_name.length<5)&&(this.coloring_name!==""))
            {
                this.alert=true;
                this.alert_arr.push('Поле "Название раскраски не должно быть короче 5 символов"');
                this.isActive_name=true;
            }
            if((this.coloring_name.length>70)&&(this.coloring_name!==""))
            {
                this.alert=true;
                this.alert_arr.push('Поле "Название раскраски не должно быть длиннее 70 символов"');
                this.isActive_name=true;
            }
            if(this.description=="")
            {
                this.alert=true;
                this.alert_arr.push('Заполните поле "Описание раскраски"');
                this.isActive_description=true;
            }
            if((this.description.length<10)&&(this.description!==""))
            {
                this.alert=true;
                this.alert_arr.push('Поле "Описание раскраски не должно быть короче 5 символов"');
                this.isActive_description=true;
            }
            if((this.description.length>300)&&(this.description!==""))
            {
                this.alert=true;
                this.alert_arr.push('Поле "Описание раскраски не должно быть длиннее 300 символов"');
                this.isActive_description=true;
            }
            if(this.tag_list=='')
            {
                this.alert=true;
                this.alert_arr.push('Выберите хотя бы один тег');
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
            let coloring_name=this.coloring_name;
            let description=this.description;
            let selected_category=this.tag_list;
            let published=this.published;
            let selected_cat=this.cat_list;
            let slug=this.chpu;
            let temp_selected_category=[];
                selected_category.forEach(function(number) {
                    temp_selected_category.push(number.id)
                });
                let temp_selected_cat=[];
                selected_cat.forEach(function(number) {
                    temp_selected_cat.push(number.id)
                });
                data.append('file', this.file);
            data.append('coloring_name', coloring_name);
            data.append('slug', slug);
            data.append('description', description);
            data.append('published', published);
            data.append('selected_category', temp_selected_category);
            data.append('cat', temp_selected_cat);
                axios.post('/upload_img'
             ,data,config)
                .then(function (res) {
                    window.location.href =('/admin/add_coloring_success')
                })
            .catch((error) => {
                this.isDisabled_button=false;
                this.add_to_errors(error.response.data.errors);
            })

            }
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
        search(input)
        {
            return new Promise(resolve => {
                if (input.length < 3) {
                    return resolve([])
                }
                axios
                    .post('/get_categories',{
                        req:input
                    }).then(response => {
                    resolve(response.data)
                })
            })
        },

        getResultValue(result) {
            return result.name
        },
        handleSubmit(result)
        {
            //результат забирать отсюда
            this.search_result= result.name
            this.search_result_id=result.id
        },

        search_cat(input)
        {
            return new Promise(resolve => {
                if (input.length < 3) {
                    return resolve([])
                }
                axios
                    .post('/get_cat_search',{
                        req:input
                    }).then(response => {
                    resolve(response.data)
                })
            })
        },

        getResultValue_cat(result) {
            return result.name
        },
        handleSubmit_cat(result)
        {
            //результат забирать отсюда
            this.search_result_cat= result.name
            this.search_result_id_cat=result.id
        }

    }
}
</script>
