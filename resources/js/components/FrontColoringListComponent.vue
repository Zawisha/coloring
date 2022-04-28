<template>
    <div class="amado_product_area section-padding-100">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
<!--                    <div class="product-topbar d-xl-flex align-items-end justify-content-between">-->
<!--                        &lt;!&ndash; Total Products &ndash;&gt;-->
<!--                        <div class="total-products">-->
<!--                            <p>Showing 01-8 0f 25</p>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>

            <div class="row">

                <div class="col-12 col-sm-4" v-for="(colored,index) in coloring_list">
                    <div class="single-product-wrapper">
                        <div class="product-img " v-on:click="go_to_one_coloring(colored.slug)">
                            <img class="front-list-img" :src="'/images/colorings/'+colored.img" alt="">
                        </div>
                        <!-- Product Description -->
                        <div class="product-description d-flex align-items-center justify-content-between">
                            <!-- Product Meta Data -->
                            <div class="product-meta-data">
                                <div class="line"></div>
                                <p class="product-price"  v-on:click="go_to_one_coloring(colored.slug)">{{ colored.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-12">
                    <!-- Pagination -->
                    <!--            пагинация          -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item pagination_button prev_pag_button" v-on:click="prev_page()"><span class="page-link page_link_width" >Назад</span>
                            </li>
                            <li v-if="pag.id!='...'" v-for="pag in pagination_numb" class="page-item pagination_button"
                                v-bind:class="{ active: pag.id==current_page }"
                                v-on:click="new_page(pag.id)">
                                <span class="page-link" >{{ pag.id }}</span>
                            </li>
                            <li v-else class="page-item">
                                <span class="page-link" >{{ pag.id }}</span>
                            </li>
                            <li class="page-item pagination_button next_pag_button" v-on:click="next_page()"><span class="page-link page_link_width" href="javascript:;">Вперёд</span>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>

        </div>
    </div>
</template>

<script>
import {eventBusColoring} from "../app";

export default {
    data() {
        return {
            coloring_list:[],
            published:1,
            //текущая страница
            current_page:1,
            //по сколько записей на странице выбирать результат
            offset:12,
            // ниже сколько всего записей в БД с такими параметрами
            pagination_all:0,
            //ниже массив в который добавляем страницы пагинации 1 2 3 и т.д.
            pagination_numb:[],
            search_id:''
        };
    },
    mounted() {
        this.get_coloring_list(this.coloring_list);
    },
    created() {
        eventBusColoring.$on("click_tag_coloring", (id) => {
            this.coloring_list=[],
                this.current_page=1,
                this.pagination_all=0,
                this.pagination_numb=[],
                this.search_id=id,
                this.get_coloring_list(this.coloring_list)
        })
    },
    methods: {
        get_coloring_list(inp)
        {
            let offset_from_start=(this.current_page-1)*this.offset
            axios
                .post('/get_coloring_list',{
                    offset:offset_from_start,
                    front:true,
                    search_id:this.search_id
                })
                .then(({ data }) => (
                        this.pagination_all=data.tipes_count,
                        data.list_colored.forEach(function(entry) {
                            inp.push({
                                id:entry.id,
                                name:entry.name,
                                description:entry.description,
                                img:entry.img,
                                published_db:entry.published,
                                slug:entry.slug
                            });
                        }),
                        this.pagination_counter()
                    )
                );
        },
        go_to_one_coloring(id)
        {
            window.location.href =('/coloring/'+id)
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
                .post('/moderation_color',{
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
                            .post('/delete_colored',{
                                id:id,
                            })
                    }
                })
        },
    }
}
</script>
