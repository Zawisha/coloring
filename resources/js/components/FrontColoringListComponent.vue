<template>
        <div class="container-fluid front_coloring_list_cont">
            <div class="row">

<!--            <h1 class="col-12 main_coloring_list_h1">Каталог бесплатных раскрасок</h1>-->
                <div v-if="menu_size<992"  v-bind:style="{ top: window_height + 'px' }" class="fixed-bottom row justify-content-center mob_menu_main">
                    <div class="mob_menu_bootom_left col">
                        <div class="mob_menu_bootom ">
                            <span class="iconify col-12 mob_menu_bootom_icon" style="color: #FFFFFF;" data-icon="ion:color-palette-outline"></span>
                            <div class="col-12 mob_menu_bootom_text text-center" v-on:click="go_to_col()">Раскраски</div>
                        </div>
                    </div >
                    <div class="mob_menu_bootom_center col">
                        <div class="mob_menu_bootom ">
                            <span class="iconify col-12 mob_menu_bootom_icon" style="color: #FFFFFF;" data-icon="ion:videocam-outline"></span>
                            <div class="col-12 mob_menu_bootom_text text-center">Видео</div>
                        </div>
                    </div >
                    <div class="mob_menu_bootom_right col">
                        <div class="mob_menu_bootom ">
                            <span class="iconify col-12 mob_menu_bootom_icon" style="color: #FFFFFF;" data-icon="icon-park-outline:book-open"></span>
                            <div class="col-12 mob_menu_bootom_text text-center">Сказки</div>
                        </div>
                    </div >
                </div>
                <div  class="col-12 one_front_coloring " v-for="(colored,index) in coloring_list">
                    <div class="col-12 row reklama_row_main_list" v-if="(index%reklama_number===0)&&(hide_on_mob)&&(menu_size>991)">
                        <div id="yandex_rtb_R-A-1785111-6"></div>
                    </div>
                    <span v-else class="row list_additional_imgs">
                     <div class="d-flex justify-content-center align-items-center col-4 col-lg-3 front-list-new-np" v-on:click="go_to_one_coloring(colored.slug)">
                          <img class="front-list-img-new" loading="lazy" :src="'/images/colorings/'+colored.img" alt="">
                     </div>
                    <div class="col-8 col-lg-9">
                        <div class="coloring_list_title_new col-lg-12 front-list-new-np-l"  v-on:click="go_to_one_coloring(colored.slug)">{{ colored.name }}</div>
                        <div class="offset-lg-0 col-lg-11 coloring_list_text justify-content-start front-list-new-np-l">{{ colored.description }}</div>
                        <div class="rate_coloring_list col-12 offset-xl-5 col-xl-6 offset-lg-3 col-lg-8 text-md-right">

                            <span v-show="colored.type_of_like=='1'"  class="like_up1"   v-on:click="setLike('1',colored.id,coloring_list)" ><span  class="iconify" data-icon="ant-design:like-filled" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                            <span v-show="colored.type_of_like==('0'||'2')"  class="like_down2" v-on:click="setLike('1',colored.id,coloring_list)" ><span  class="iconify" data-icon="ant-design:like-outlined" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                            <span class="coloring_rate">{{ colored.count_of_like }}</span>
                            <span v-show="colored.type_of_like=='2'" class="like_down3" v-on:click="setLike(2,colored.id,coloring_list)" ><span class="iconify" data-icon="ant-design:dislike-filled" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                            <span v-show="colored.type_of_like==('0'||'1')" class="like_down4" v-on:click="setLike(2,colored.id,coloring_list)" ><span class="iconify" data-icon="ant-design:dislike-outlined" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height' ></span></span>

                            <span v-if="menu_size<993" class="iconify follow_arrow_mob" data-icon="akar-icons:arrow-forward-thick-fill" v-bind:data-width='menu_data_width_arrow'  v-bind:data-height='menu_data_height_arrow'></span>
<!--                            <span class="iconify" data-icon="ant-design:star-filled" data-width="25" data-height="23"></span>-->
                            <!--                            <span class="iconify" data-icon="icon-park:like"></span>-->
                        </div>
                    </div>
                    </span>
                </div>

                    <preloader-component v-if="preload"></preloader-component>


            </div>


        </div>
</template>

<script>
import {eventBusColoring} from "../app";
import {eventSearch} from "../app";
window.onload = function() {
    window.yaContextCb.push(()=>{
        Ya.Context.AdvManager.render({
            renderTo: 'yandex_rtb_R-A-1785111-6',
            blockId: 'R-A-1785111-6',
            statId: 34567,
        })
    })
};
export default {
    props: ['auth_user','search_q'],
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
            search_id:'',
            menu_size:'',
            menu_data_width:'',
            menu_data_height:'',
            menu_data_width_arrow:'',
            menu_data_height_arrow:'',
            window_height:'',
            isStick:true,
            reklama_number:6,
            hide_on_mob:true,
            like_status_arr:[{12: 'ant-design:like-outlined' },{13: 'ant-design:like-filled'}],
            like_status:'ant-design:like-outlined',
            like_status1:'ant-design:like-filled',
            like1:false,
            like2:false,
            preload:false,


        };
    },
    mounted() {
        this.get_coloring_list(this.coloring_list);
        this.get_size();
        this.scroll();

    },
    created() {
        // //получаем результат поиска
        // eventSearch.$on("click_search", (id) => {
        //         console.log(id)
        // })
        // eventBusColoring.$on("click_tag_coloring", (id) => {
        //     this.coloring_list=[],
        //         this.current_page=1,
        //         this.pagination_all=0,
        //         this.pagination_numb=[],
        //         this.search_id=id,
        //         this.get_coloring_list(this.coloring_list)
        // })
    },
    updated() {

    },
    methods: {
        scroll () {

            window.onscroll = () => {
                // if($(window).scrollTop() + $(window).height() == $(document).height()) {


                    let documentHeight = document.body.scrollHeight;
                    let currentScroll = window.scrollY + window.innerHeight;
                    // When the user is [modifier]px from the bottom, fire the event.
                    let modifier = 200;
                    if(currentScroll + modifier > documentHeight) {

                        this.get_coloring_list(this.coloring_list)
                    }

                // }
            }



            // $(window).scroll(function() {
            //     if($(window).scrollTop() + $(window).height() == $(document).height()) {
            //         this.test='YES!!!!!!!!!!!!'
            //         alert('yes')
            //         this.get_coloring_list(this.coloring_list)
            //
            //     }
            // });
            // window.onscroll = () => {
            //     if($(window).scrollTop() + $(window).height() == $(document).height() -100) {
            //         console.log('YES')
            //         this.test='YES!!!!!!!!!!!!'
            //         this.get_coloring_list(this.coloring_list)
            //
            //     }
            // }
        },
        go_to_col()
        {
            console.log('qw')
            window.location.href = '/'
        },
          setLike(n,colored_id,inp) {
              if(this.auth_user) {
                  this.coloring_list.forEach(function (item, i) {
                      if (item.id === colored_id) {
                          axios
                              .post('/setLike', {
                                  type_of_like: n,
                                  colored_id: colored_id,
                                  type_of_content:'1',
                              })
                          if (inp[i]['type_of_like'] == n) {
                              inp[i]['type_of_like'] = '0'
                              if (n == '1') {
                                  inp[i]['count_of_like'] = (Number(inp[i]['count_of_like']) - Number(1))
                              }
                          } else {
                              if (n == '1') {
                                  inp[i]['count_of_like'] = (Number(inp[i]['count_of_like']) + Number(1))
                                  console.log(inp[i]['count_of_like']);
                              }
                              inp[i]['type_of_like'] = n;
                          }

                      }
                  });
              }
              else
              {
                  window.location.href = '/login'
              }
        },
        get_size()
        {
           this.menu_size=window.innerWidth;
           this.window_height=(window.innerHeight-60);
           //получаем высоту экрана
           // console.log(this.window_height)
           // console.log(this.menu_size);
           if(this.menu_size>993)
           {
               this.menu_data_width=25
               this.menu_data_height=23
           }
           if(this.menu_size<993)
           {
               this.menu_data_width=20
               this.menu_data_height=16
               this.hide_on_mob=false


           }
            if(this.menu_size<373)
            {
                this.menu_data_width_arrow=18
                this.menu_data_height_arrow=15
            }
            else
            {
                this.menu_data_width_arrow=25
                this.menu_data_height_arrow=23
            }

        },
        get_coloring_list(inp)
        {
            if(this.offset<=this.types_count)
            {
            this.preload=true
            axios
                .post('/get_coloring_list',{
                    offset:this.offset,
                    front:true,
                    search_id:this.search_id,
                    search_q:this.search_q
                })
                .then(({ data }) => (
                        this.types_count=data.tipes_count,
                        data.list_colored.forEach(function(entry) {
                            inp.push({
                                id:entry.id,
                                name:entry.name,
                                description:entry.description,

                                img:entry.img_small,
                                published_db:entry.published,
                                slug:entry.slug,
                                type_of_like:entry.type_of_like,
                                count_of_like:entry.count_of_like
                            });
                        }),
                            this.preload=false
                    ),
                    this.offset=Number(this.offset)+Number(10),
                );
            }

        },
        go_to_one_coloring(id)
        {
            console.log('12');
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
