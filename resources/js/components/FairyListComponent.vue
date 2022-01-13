<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
                <div class="col"  v-for="(colored,index) in coloring_list">
                    <div class="card radius-15">
                        <div class="card-body text-center">
                            <div class="p-4 border radius-15">
                                <img :src="'/images/fairy/'+colored.img" width="110" height="110" class="rounded-circle shadow" alt="">
                                <div class="justify-content-center">
                                <h5 class="col-12 custom_text_comp_header">{{ colored.name }}</h5>
                                <p class="col-12 custom_text_comp">{{ colored.description }}</p>
                                </div>
                                <div class="list-inline contacts-social mt-3 mb-3">
                                    <span v-if="colored.published_db==1" v-on:click="moderation(index,colored.id)" class="list-inline-item bg-light text-white border-0 change_status_col_fai"><i class="bx bx-check "></i></span>
                                    <span v-else v-on:click="moderation(index,colored.id)"  class="list-inline-item bg-light text-white border-0 change_status_col_fai"><i class="bx bx-question-mark "></i></span>
                                    <span class="list-inline-item bg-light text-white border-0 change_status_col_fai" v-on:click="moderation_delete(index,colored.id)"><i class="bx bxs-x-circle"></i></span>
                                </div>
                                <div class="d-grid"> <a :href="'/admin/edit_fairy/' + colored.id " class="btn btn-light radius-15">Редактировать</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--            пагинация          -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item pagination_button" v-on:click="prev_page()"><span class="page-link" >Previous</span>
                    </li>
                    <li v-if="pag.id!='...'" v-for="pag in pagination_numb" class="page-item pagination_button"
                        v-bind:class="{ active: pag.id==current_page }"
                        v-on:click="new_page(pag.id)">
                        <span class="page-link" >{{ pag.id }}</span>
                    </li>
                    <li v-else class="page-item">
                        <span class="page-link" >{{ pag.id }}</span>
                    </li>
                    <li class="page-item pagination_button" v-on:click="next_page()"><span class="page-link" href="javascript:;">Next</span>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            coloring_list:[],
            published:0,
            //текущая страница
            current_page:1,
            //по сколько записей на странице выбирать результат
            offset:12,
            // ниже сколько всего записей в БД с такими параметрами
            pagination_all:0,
            //ниже массив в который добавляем страницы пагинации 1 2 3 и т.д.
            pagination_numb:[],
            isModalVisible: false,
        };
    },
    mounted() {
        this.get_coloring_list(this.coloring_list);
    },
    methods: {
        alert: function(){

        },
        get_coloring_list(inp)
        {
            let offset_from_start=(this.current_page-1)*this.offset
            axios
                .post('/get_fairy_list',{
                    offset:offset_from_start,
                    published:this.published,
                })
                .then(({ data }) => (
                        this.pagination_all=data.tipes_count,
                        data.list_colored.forEach(function(entry) {
                            inp.push({
                                id:entry.id,
                                name:entry.name,
                                description:entry.description,
                                img:entry.img_title,
                                published_db:entry.published,
                                from_user:entry.from_user,
                            });
                        }),
                        this.pagination_counter()
                    )
                );
        },
        prev_page()
        {
            if(this.current_page!=1)
            {
                this.current_page=this.current_page-1;
                this.new_page(this.current_page)
            }

        },
        next_page()
        {
            let g=Math.ceil(this.pagination_all/this.offset);
            if(this.current_page!=g)
            {
                this.current_page=this.current_page+1;
                this.new_page(this.current_page)
            }
        },
        new_page(page_id)
        {
            this.pagination_numb=[]
            this.coloring_list=[]
            this.current_page=page_id
            this.get_coloring_list(this.coloring_list);
        },
        pagination_counter()
        {
            if(this.pagination_all!=0) {
                //формирование отрисовки пагинации
                let g = Math.ceil(this.pagination_all / this.offset);

                //текущая страница первая
                if (this.current_page == 1) {
                    if (g > 1) {
                        for (let i = 1; i <= 2; i++) {
                            this.pagination_numb.push({'id': i})
                        }
                        this.pagination_numb.push({'id': '...'})
                        this.pagination_numb.push({'id': g})
                    } else {
                        this.pagination_numb.push({'id': g})
                    }
                }
                //текущая страница вторая
                if (this.current_page == 2) {
                    if (g > 2) {
                        for (let i = 1; i <= 3; i++) {
                            this.pagination_numb.push({'id': i})
                        }
                        this.pagination_numb.push({'id': '...'})
                        this.pagination_numb.push({'id': g})
                    } else {
                        for (let i = 1; i <= 2; i++) {
                            this.pagination_numb.push({'id': i})
                        }
                    }
                }
                if (this.current_page > 2) {

                    //минусовая
                    if ((this.current_page - 3) == 1) {
                        let page = this.current_page - 3
                        this.pagination_numb.push({'id': '1'})
                    } else {
                        if ((this.current_page - 2) > 1) {
                            this.pagination_numb.push({'id': '1'})
                            this.pagination_numb.push({'id': '...'})
                        }
                    }
                    let page = this.current_page - 2
                    this.pagination_numb.push({'id': page})
                    page = this.current_page - 1
                    this.pagination_numb.push({'id': page})
                    this.pagination_numb.push({'id': this.current_page})
                    //плюсовая
                    if ((this.current_page + 1) <= g) {
                        page = this.current_page + 1
                        this.pagination_numb.push({'id': page})
                    }
                    if ((this.current_page + 2) <= g) {
                        page = this.current_page + 2
                        this.pagination_numb.push({'id': page})
                    }
                    if ((this.current_page + 3) == g) {
                        page = this.current_page + 3
                        this.pagination_numb.push({'id': page})
                    } else {
                        if ((g - this.current_page) > 2) {
                            this.pagination_numb.push({'id': '...'})
                            this.pagination_numb.push({'id': g})
                        }
                    }
                }
            }
        },
        moderation(index,id)
        {
            let published_status = 0;
            if(this.coloring_list[index].published_db == 0)
            {
                this.coloring_list[index].published_db=1
                published_status=1
            }
            else
            {
                published_status=0
                this.coloring_list[index].published_db=0
            }
            axios
                .post('/moderation_fairy',{
                    id:id,
                    published:published_status
                })
        },
        moderation_delete(index,id)
        {
            const options = {title: '', size: 'sm',okLabel:'Удалить', cancelLabel: 'Не удалять'}
            this.$dialogs.confirm('Удалить?', options)
                .then(res => {
                    if(res.ok==true)
                    {
                        this.coloring_list.splice(index, 1);
                        axios
                            .post('/delete_fairy',{
                                id:id,
                            })
                    }
                })
        },
    }
}
</script>
