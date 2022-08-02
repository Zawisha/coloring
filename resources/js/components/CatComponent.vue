<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div v-if="search_result!=''" class="tag_title">Выбрана категория: {{ search_result }}</div>
            </div>

            <div class="col-12 row float-left add_tag justify-content-center">
                    <div v-if="alert" class="alert alert-danger" role="alert">
                        <ul id="error_list">
                            <li v-for="item in alert_arr" >
                                {{ item }}
                            </li>
                        </ul>
                    </div>
                    <div v-if="success_added" class="success alert-success title_success_tag" role="alert">
                        {{ success_text }}
                    </div>

            <div class="col-sm-4 ">
                <input id="todo-input" type="text" class="form-control" value="" v-model="tag_to_add" v-on:focus=delete_bars() maxlength="32" v-on:change="slugCheck">
            </div>
            <div class="col-sm-2">
                <button type="button"  v-on:click="add_tag()" class="btn btn-light admin_tag_button">Добавить категорию</button>
            </div>
            <div class="col-sm-4 ">
                        <input id="" type="text" class="form-control" value="" v-on:change="slugCheck1"  v-model="search_result" v-on:focus=delete_bars() :disabled="disable_edit" maxlength="32">
            </div>
            <div class="col-sm-2">
                        <button type="button" v-on:click="edit_tag()" class="btn btn-light admin_tag_button" >Сохранить категорию</button>
            </div>
            </div>
            <div class="col-12 row">
                <div class="col-6">ЧПУ: {{ chpu }}</div>
                <div class="col-6">ЧПУ редактируемой категории: {{ chpu1 }}</div>
            </div>
            <div class="col-12 row">
            <div class="add_coloring_title col-6">Добавьте изображение для НОВОЙ категории
            <form @submit="formSubmit" enctype="multipart/form-data">
                <input type="file" class="form-control" v-on:change="imgPreview" name="avatar">
                <div v-if="imagepreview_start" class="col-12 avatar img-fluid img-circle add_c_image justify-content-center" style="margin-top:10px">
                    <img class="col-12" :src="imagepreview_start"/>
                </div>
                <div v-else class="col-12 avatar img-fluid img-circle add_c_image justify-content-center" style="margin-top:10px">
                    <img class="col-12" :src="imagepreview"/>
                </div>
            </form>
                <div class="desc_cat">Описание новой категории (description)</div>
                <textarea class="input_coloring_name search-control form-control desc_cat" v-model="description_new" placeholder="введите описание" rows=5  :maxlength="130" ></textarea>
            </div>
            <div class="add_coloring_title col-6">Добавьте изображение для РЕДАКТИРУЕМОЙ категории
            <form @submit="formSubmit1" enctype="multipart/form-data">
                <input type="file" class="form-control" v-on:change="imgPreview1" name="avatar">
                <div v-if="imagepreview_start1" class="col-12 avatar img-fluid img-circle add_c_image justify-content-center" style="margin-top:10px">
                    <img class="col-12" :src="imagepreview_start1"/>
                </div>
                <div v-else class="col-12 avatar img-fluid img-circle add_c_image justify-content-center" style="margin-top:10px">
                    <img class="col-12" :src="imagepreview1"/>
                </div>
            </form>
                <div class="desc_cat">Описание редактируемой категории (description)</div>
                <textarea class="input_coloring_name search-control form-control desc_cat" v-model="description_old" placeholder="введите описание" rows=5  :maxlength="130" ></textarea>
            </div>
            </div>
            <div class="col-12 tag_list_title ">
                Список категорий
            </div>
            <div class="customers-list row col-12 float-left">
                <div  v-for="tag in tag_list" class="col-12 col-sm-4 customers-list-item d-flex align-items-center border-top border-bottom _list borders_tag_list cursor-pointer text-center">
                    <div class="col-12" v-on:click="select_tag_from_list(tag.id,tag.name,tag.description)">
                        <h6 class="font-14">{{ tag.name }}</h6>
                    </div>
                </div>
            </div>
            <div v-if="tags_count>tag_offset" class="col-2 text-end d-grid load_more_tags">
                <button type="button" v-on:click="add_more_tags()" class="btn btn-light">Загрузить еще категории</button>
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
            search_result:'',
            tag_to_add:'',
            success_added:false,
            tag_offset:0,
            tag_list:[],
            tags_count:0,
            tag_to_edit:'',
            search_result_id:'',
            success_text:'',
            disable_edit:true,
            cat_slug:'',
            chpu:'',
            chpu1:'',
            imagepreview:null,
            imagepreview_start:false,
            added_cat_id:'',
            file:'',
            imagepreview1:null,
            imagepreview_start1:false,
            file1:'',
            description_new:'',
            description_old:''

        };
    },
    mounted() {
            this.get_tag_list(this.tag_list)
    },
    methods: {
        add_tag()
        {
            this.alert_arr=[];
            this.alert=false;
            if(this.tag_to_add=="")
            {
                this.alert=true;
                this.alert_arr.push('Заполните поле "Название категории"');
                this.isActive_name=true;
            }
            else {
                axios.post('/add_cat', {
                        tag:this.tag_to_add,
                        slug:this.chpu,
                        description:this.description_new
                    }
                )
                    .then(response => {
                        this.success_added = true
                        this.success_text='Категория успешно добавлена'
                        this.added_cat_id=response.data.id
                        this.tag_list.push({
                            id:this.added_cat_id,
                            name:this.tag_to_add,
                        })
                        this.tag_to_add=''
                        if(this.file) {
                            this.formSubmit()
                        }
                    })
                    .catch(error => {
                        this.add_to_errors(error.response.data.errors)
                    });
            }
        },
        formSubmit(e) {
            // e.preventDefault();
            this.success_add_final=false;
            this.alert_arr=[];
            this.alert=false;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                let data = new FormData();
                let cat_id=this.added_cat_id;
            if(this.file)
                {
                    data.append('file', this.file);
                }
                data.append('cat_id', cat_id);
                axios.post('/upload_img_cat'
                    ,data,config)
                    .then(response => {
                        this.success_add_final=true
                    })
                    .catch((error) => {
                        this.add_to_errors(error.response.data.errors);
                    })
                this.file=''
                this.chpu=''
                this.imagepreview_start=''
                this.imagepreview=''
        },
        imgPreview(e) {
            this.imagepreview_start=false
            this.file = e.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(this.file);
            if (this.file.type.match('image.*')) {
                reader.onload = e => {
                    this.imagepreview=e.target.result;
                }
            }
        },
        edit_tag()
        {
            this.alert_arr=[];
            this.alert=false;
            this.success_added=false
            if(this.search_result=="")
            {
                this.alert=true;
                this.alert_arr.push('Заполните поле "Редактирование категории"');
            }
            else {
                axios.post('/edit_cat', {
                        tag: this.search_result,
                        tag_id: this.search_result_id,
                        slug: this.chpu1,
                        description: this.description_old,
                    }
                )
                    .then(response => {
                        this.success_added = true
                        this.success_text='Категория успешно отредактирована'
                        this.disable_edit=true
                        let temp_id=this.search_result_id
                        let temp_name=this.search_result
                        this.tag_list.forEach(function(item) {
                            if(item.id==temp_id)
                            {
                                item.name=temp_name
                            }
                        });
                        this.search_result=''
                        this.search_result_id=temp_id
                        this.imagepreview_start1=''
                        this.imagepreview1=''
                        if(this.file1) {
                            this.formSubmit1()
                        }
                    })
                    .catch(error => {
                        this.add_to_errors(error.response.data.errors)
                    });
            }
        },
        formSubmit1(e) {
            // e.preventDefault();
            this.success_add_final=false;
            this.alert_arr=[];
            this.alert=false;

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            let data = new FormData();
            let cat_id=this.search_result_id;
            if(this.file1)
            {
                data.append('file', this.file1);
            }
            data.append('cat_id', cat_id);
            axios.post('/upload_img_cat_edit'
                ,data,config)
                .then(response => {
                    this.success_add_final=true
                })
                .catch((error) => {
                    this.add_to_errors(error.response.data.errors);
                })
            this.file1=''
            this.chpu1=''
            this.imagepreview_start1=''
            this.imagepreview1=''
        },
        imgPreview1(e) {
            this.imagepreview_start1=false
            this.file1 = e.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(this.file1);
            if (this.file1.type.match('image.*')) {
                reader.onload = e => {
                    this.imagepreview1=e.target.result;
                }
            }
        },
        slugCheck(){
            this.chpu=slug(this.tag_to_add)
        },
        slugCheck1(){
            this.chpu1=slug(this.search_result)
        },
        select_tag_from_list(id,name,description)
        {
            this.search_result=name
            this.search_result_id=id
            this.disable_edit=false,
            this.description_old=description
            this.slugCheck1()
            this.get_cat_img()

        },
        get_cat_img()
        {
            axios
                .post('/get_cat_img',{
                    cat_id:this.search_result_id
                })
                .then(response => {
                        if(response.data.cat_list[0].img != null)
                        {
                            this.imagepreview_start1='/images/cat/'+response.data.cat_list[0].img
                        }
                        else
                        {
                            this.imagepreview_start1='/images/no_img.jpg'
                        }
                })
        },
        add_more_tags()
        {
            this.get_tag_list(this.tag_list)
        },
        get_tag_list(inp)
        {
            axios.post('/get_cat_list', {
                    offset: this.tag_offset
                }
            )
                .then(({ data }) => (
                            data.list_tags.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    name:entry.name,
                                    description:entry.description,
                                });
                            }),
                           this.tags_count=data.tipes_count
                    )
                );
            this.tag_offset=(this.tag_offset)+20
        },
        delete_bars()
        {
            this.alert=false;
            this.success_added=false
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

    }
}
</script>
