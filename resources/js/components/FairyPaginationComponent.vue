<template>
    <div class="container justify-content-center text-center">
        <div class="row justify-content-center">
        <div class="col-12">
        <button type="submit" class="btn btn-light add_coloring_title" v-on:click="add_new_fairy_page()">Добавить новую страницу</button>
        </div>
        <div class="col-12 d-flex justify-content-center">
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
    </div>
</template>

<script>
export default {
    data() {
        return {
            coloring_list:[],
            published:1,
            //текущая страница
            current_page:1,
            //по сколько записей на странице выбирать результат
            offset:1,
            // ниже сколько всего записей в БД с такими параметрами
            pagination_all:0,
            //ниже массив в который добавляем страницы пагинации 1 2 3 и т.д.
            pagination_numb:[]
        };
    },
    props: ['current_page_vue','current_fairy_vue'],
    mounted() {
        this.get_start_page_number();
        this.get_coloring_list();
    },
    methods: {
        add_new_fairy_page()
        {
            let new_page_id=(this.pagination_all+1);
            window.location.href =('/admin/add_fairy/'+this.current_fairy_vue+'/'+new_page_id)
        },
        get_start_page_number()
        {
            this.current_page=this.current_page_vue;
        },
        get_coloring_list()
        {
            axios
                .post('/get_fairy_pagination',{
                    current_fairy_id:this.current_fairy_vue,
                })
                .then(({ data }) => (
                        this.pagination_all=data.tipes_count,
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
            window.location.href =('/admin/add_fairy/'+this.current_fairy_vue+'/'+page_id)
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
        moderation(id)
        {
            let id_in=id-1
            if(this.coloring_list[id_in].published_db == 0)
            {
                this.coloring_list[id_in].published_db=1
            }
            else
            {
                this.coloring_list[id_in].published_db=0
            }

        }
    }
}
</script>
