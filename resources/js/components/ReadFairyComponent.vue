<template>
    <div class="single-product-area section-padding-100 clearfix">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12" v-html="description"></div>
            </div>
<!--            пагинация-->
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
</template>

<script>

export default {
    data() {
        return {
            text:'',
            fairy_id:'',
            current_page:1,
            description:'',
            offset:1,
            pagination_all:0,
            pagination_numb:[]
        };
    },
    mounted() {
        this.get_page_parametres()
        this.get_fairy_page_to_read()
    },
    methods: {
        get_page_parametres()
        {
            let uri = window.location.href.split('/');
            this.fairy_id=uri[4]
        },
        get_fairy_page_to_read()
            {
                axios
                    .post('/get_fairy_page_data',{
                        current_fairy:this.fairy_id,
                        current_page:this.current_page
                    })
                    .then(({ data }) => (
                        this.description=data.fairy_data[0].description,
                        this.pagination_all=data.tipes_count,
                        this.pagination_counter()
            ),

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
            this.get_fairy_page_to_read();
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

        }
}
</script>
