<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
                <div class="col"  v-for="(colored,index) in coloring_list">
                    <div class="card radius-15">
                        <div class="card-body text-center">
                            <div class="p-4 border radius-15">
                                <img :src="'/images/colorings/'+colored.img" width="110" height="110" class="rounded-circle shadow" alt="">
                                <h5 class="col-12 custom_text_comp_header">{{ colored.slug }}</h5>
                                <h5 class="col-12 custom_text_comp_header">{{ colored.name_old }}</h5>
                                <p class="col-12 custom_text_comp">{{ colored.description }}</p>
                                <div class="list-inline contacts-social mt-3 mb-3">
                                    <span v-if="colored.published_db==1" v-on:click="moderation(index,colored.id)" class="list-inline-item text-white bg-light-border change_status_col_fai"><i class="bx bx-check "></i></span>
                                    <span v-else v-on:click="moderation(index,colored.id)"  class="list-inline-item  text-white bg-light-border change_status_col_fai"><i class="bx bx-question-mark "></i></span>
                                    <span class="list-inline-item text-white bg-light-border change_status_col_fai"v-on:click="moderation_delete(index,colored.id)"><i class="bx bxs-x-circle"></i></span>
                                </div>
                                <div class="d-grid"> <a :href="'/admin/edit_coloring/' + colored.id " class="btn btn-light radius-15">Редактировать</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <preloader-component v-if="preload"></preloader-component>
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
            offset:0,
            types_count:0,
            // ниже сколько всего записей в БД с такими параметрами
            pagination_all:0,
            //ниже массив в который добавляем страницы пагинации 1 2 3 и т.д.
            pagination_numb:[],
            preload:false
        };
    },
    mounted() {
        this.get_decorated_coloring_list(this.coloring_list);
        this.scroll();
    },
    methods: {
        scroll () {
            window.onscroll = () => {
                let documentHeight = document.body.scrollHeight;
                let currentScroll = window.scrollY + window.innerHeight;
                // When the user is [modifier]px from the bottom, fire the event.
                let modifier = 200;
                if(currentScroll + modifier > documentHeight) {

                    this.get_decorated_coloring_list(this.coloring_list)
                }
            }
        },

        get_decorated_coloring_list(inp)
        {
            if(this.offset<=this.types_count)
            {
                this.preload=true
                axios
                    .post('/get_decorated_coloring_list',{
                        offset:this.offset,
                    })
                    .then(({ data }) => (
                            this.types_count=data.tipes_count,
                                data.list_colored.forEach(function(entry) {
                                    inp.push({
                                        id:entry.id,
                                        coloring_id:entry.coloring_id,
                                        user_id:entry.user_id,
                                        img:entry.img,
                                        user_name:entry.user_name,
                                        slug:entry.slug,
                                        age:entry.age,
                                        published_db:entry.published,
                                        name_old:entry.name_old
                                    });
                                }),
                                this.preload=false
                        ),
                        this.offset=Number(this.offset)+Number(20),
                    );
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
                .post('/moderation_decorated_color',{
                    id:id,
                    published:published_status
                })
            console.log(this.coloring_list[index])
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
                            .post('/delete_colored_decoration',{
                                id:id,
                            })
                    }
                })
        },
    }
}
</script>
