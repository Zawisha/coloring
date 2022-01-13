<template>
    <div class="container">
        <div class="row justify-content-center">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Ник</th>
                    <th scope="col">Почта</th>
                    <th scope="col">Статус(админ,редактор,юзер)</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user,index) in users_list">
                    <th scope="row">{{ user.id }}</th>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                        <input type="radio" value="1" v-model="user.perm" :checked="user.perm=='1'" v-on:change="change_permission(user.id,user.perm)">
                        <label>Админ</label>
                        <br>
                        <input type="radio" value="2" v-model="user.perm" :checked="user.perm=='2'" v-on:change="change_permission(user.id,user.perm)">
                        <label>Редактор</label>
                        <br>
                        <input type="radio" value="0" v-model="user.perm" :checked="user.perm=='0'" v-on:change="change_permission(user.id,user.perm)">
                        <label>Юзер</label>
                        <br>
                    </td>
                </tr>
                </tbody>
            </table>

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
            users_list:[],
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
        this.get_users_list(this.users_list);
    },
    methods: {
        change_permission(user_id,user_perm)
        {
            axios
                .post('/change_permission',{
                    user_id:user_id,
                    user_perm:user_perm,
                })
        },
        get_users_list(inp)
        {
            let offset_from_start=(this.current_page-1)*this.offset
            axios
                .post('/get_users_list',{
                    offset:offset_from_start,
                })
                .then(({ data }) => (
                        this.pagination_all=data.tipes_count,
                        data.list_users.forEach(function(entry) {
                            inp.push({
                                id:entry.id,
                                name:entry.name,
                                email:entry.email,
                                perm:entry.perm
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
        moderation_user(index,id)
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

    }
}
</script>
