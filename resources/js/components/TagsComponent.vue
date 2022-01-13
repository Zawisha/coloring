<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <autocomplete
                    :search="search"
                    :get-result-value="getResultValue"
                    :debounce-time="500"
                    @submit="handleSubmit"
                    v-on:focus=delete_bars()
                ></autocomplete>
                <div v-if="search_result!=''" class="tag_title">Выбран тег: {{ search_result }}</div>
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
                <input id="todo-input" type="text" class="form-control" value="" v-model="tag_to_add" v-on:focus=delete_bars() maxlength="32">
            </div>
            <div class="col-sm-2">
                <button type="button" v-on:click="add_tag()" class="btn btn-light admin_tag_button">Добавить тег</button>
            </div>
            <div class="col-sm-4 ">
                        <input id="" type="text" class="form-control" value="" v-model="search_result" v-on:focus=delete_bars() :disabled="disable_edit" maxlength="32">
            </div>
            <div class="col-sm-2">
                        <button type="button" v-on:click="edit_tag()" class="btn btn-light admin_tag_button" >Редактировать тег</button>
            </div>
            </div>
            <div class="col-12 tag_list_title ">
                Список тегов
            </div>
            <div class="customers-list row col-12 float-left">
                <div  v-for="tag in tag_list" class="col-12 col-sm-4 customers-list-item d-flex align-items-center border-top border-bottom _list borders_tag_list cursor-pointer text-center">
                    <div class="col-12" v-on:click="select_tag_from_list(tag.id,tag.name)">
                        <h6 class="font-14">{{ tag.name }}</h6>
                    </div>
                </div>
            </div>
            <div v-if="tags_count>tag_offset" class="col-2 text-end d-grid load_more_tags">
                <button type="button" v-on:click="add_more_tags()" class="btn btn-light">Загрузить еще теги</button>
            </div>
        </div>
    </div>
</template>

<script>
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
            disable_edit:true
        };
    },
    mounted() {
            this.get_tag_list(this.tag_list)
    },
    methods: {
        select_tag_from_list(id,name)
        {
            this.search_result=name
            this.search_result_id=id
            this.disable_edit=false
        },
        edit_tag()
        {
            this.alert_arr=[];
            this.alert=false;
            this.success_added=false
            if(this.search_result=="")
            {
                this.alert=true;
                this.alert_arr.push('Заполните поле "Редактирование тега"');
            }
            else {
                axios.post('/edit_tag', {
                        tag: this.search_result,
                        tag_id: this.search_result_id
                    }
                )
                    .then(response => {
                        this.success_added = true
                        this.success_text='Тег успешно отредактирован'
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
                    })
                    .catch(error => {
                        this.add_to_errors(error.response.data.errors)
                    });
            }
        },
        add_more_tags()
        {
            this.tag_offset=(this.tag_offset)+50
            this.get_tag_list(this.tag_list)
        },
        get_tag_list(inp)
        {
            axios.post('/get_tag_list', {
                    offset: this.tag_offset
                }
            )
                .then(({ data }) => (
                            data.list_tags.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    name:entry.name,
                                });
                            }),
                           this.tags_count=data.tipes_count
                    )
                );
        },
        delete_bars()
        {
            this.alert=false;
            this.success_added=false
        },
        add_tag()
        {
            this.alert_arr=[];
            this.alert=false;
            if(this.tag_to_add=="")
            {
                this.alert=true;
                this.alert_arr.push('Заполните поле "Название тега"');
                this.isActive_name=true;
            }
            else {
                axios.post('/add_tag', {
                        tag: this.tag_to_add
                    }
                )
                    .then(response => {
                           this.success_added = true
                           this.tag_to_add=''
                        this.success_text='Тег успешно добавлен'
                    })
                    .catch(error => {
                        this.add_to_errors(error.response.data.errors)
                    });
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
        //ниже методы динамического поиска
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
            this.disable_edit=false
        }

    }
}
</script>
