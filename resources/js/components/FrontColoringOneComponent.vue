<template>
    <div>
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
    <div class="container-fluid single_coloring_back">
        <div class="row single_coloring_img_text ">
            <div class="col-12 justify-content-center col-lg-6 mob_img_settings">
                <img id="image" class="col single_coloring_img " :src="mainImage"  alt="Image"/>
            </div>
            <div v-if="menu_size<992" class="col-12 justify-content-center text-center mob_settings_one_imgs">
                <span v-show="one_type_of_like=='1'"  class="like_up1"   v-on:click="setLike_one_coloring('1',one_coloring_id)" ><span  class="iconify" data-icon="ant-design:like-filled" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                <span v-show="one_type_of_like==('0'||'2')"  class="like_down2" v-on:click="setLike_one_coloring('1',one_coloring_id)" ><span  class="iconify" data-icon="ant-design:like-outlined" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                <span class="coloring_rate">{{ one_count_of_like }}</span>
                <span v-show="one_type_of_like=='2'" class="like_down3" v-on:click="setLike_one_coloring(2,one_coloring_id)" ><span class="iconify" data-icon="ant-design:dislike-filled" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                <span v-show="one_type_of_like==('0'||'1')" class="like_down4" v-on:click="setLike_one_coloring(2,one_coloring_id)" ><span class="iconify" data-icon="ant-design:dislike-outlined" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height' ></span></span>
                <span class="iconify follow_arrow_mob follow_one_coloring_front" data-icon="akar-icons:arrow-forward-thick" data-width="34" data-height="32"></span>
            </div>
            <div class="col-12 col-lg-6 single_coloring_col_text mob_settings_one_imgs">
                <div class="col-12 mob_settings_one_imgs" v-bind:class="{'text-center':menu_size<992}"> <h6 class="single_title_descr_main">{{ coloring_name }}</h6></div>
                <div class=" col-12 single_title_descr mob_settings_one_imgs">{{ description }}</div>
                <div class="col-12 row single_col_mob_setting_menu">
                <div class="col-5 single_coloring_cat justify-content-center text-center" v-bind:class="{ single_coloring_cat_color:cat_flag }" v-on:click="change_status_cat('cat')">категории</div>
                <div class="col-5 single_coloring_cat_sec  justify-content-center text-center" v-bind:class="{ single_coloring_cat_color:category_flag }" v-on:click="change_status_cat('category')">теги/коллекции</div>
                </div>
                <div class="col-12 green_single_coloring_cat row">
                  <div class="col-12 green_cat_list row">
                    <div v-if="cat_flag" v-for="one_cat in cat_list" v-on:click="go_to_one_cat(one_cat.slug)" class="col one_cat">
                        {{ one_cat.cat_name }}
                    </div>
                      <div v-if="category_flag" v-for="one_category in category_list" class="col one_cat">
                          {{ one_category.category_name }}
                      </div>
                    </div>
                </div>
                <div v-if="menu_size>991" class="col-6 coloring_author">Автор:<span class="coloring_author_ref">{{ nickname }}</span></div>

            </div>
        </div>
        <div class="row col-12 like_panel_one_coloring_front mob_settings_one_imgs">
<!--            <div class="col-5 text-center">-->
            <div v-if="menu_size>991" class="col-6 one_front_coloring_like_row">
                <span v-show="one_type_of_like=='1'"  class="like_up1"   v-on:click="setLike_one_coloring('1',one_coloring_id)" ><span  class="iconify" data-icon="ant-design:like-filled" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                <span v-show="one_type_of_like==('0'||'2')"  class="like_down2" v-on:click="setLike_one_coloring('1',one_coloring_id)" ><span  class="iconify" data-icon="ant-design:like-outlined" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                <span class="coloring_rate">{{ one_count_of_like }}</span>
                <span v-show="one_type_of_like=='2'" class="like_down3" v-on:click="setLike_one_coloring(2,one_coloring_id)" ><span class="iconify" data-icon="ant-design:dislike-filled" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                <span v-show="one_type_of_like==('0'||'1')" class="like_down4" v-on:click="setLike_one_coloring(2,one_coloring_id)" ><span class="iconify" data-icon="ant-design:dislike-outlined" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height' ></span></span>
                <span class="iconify follow_arrow_mob follow_one_coloring_front" data-icon="akar-icons:arrow-forward-thick" data-width="34" data-height="32"></span>
            </div>
            <div class="col-12 justify-content-center text-center col-lg-6  row download_print_mob mob_settings_one_imgs">
                <div class="col-5   button_download_green text-center" v-on:click="download_img()">Скачать</div>
                <div class="col-5   button_download_green button_download_green_print text-center" v-on:click="print()">Печатать</div>
            </div>
        </div>
<!--        <div class="col-12 streack"></div>-->
<!--        <div class="col-12 header_works text-center">Работы художников</div>-->
<!--        <div class="col-12 row works_imgs_row mob_settings_one_imgs">-->
<!--            <div class="col-4 col-lg-3">-->
<!--                <div class="text-center works_list">-->
<!--                    <img class="works_list_img" :src="'/images/colorings/1_1651087261.jpg'" alt="">-->
<!--                    <div class="who_works">-->
<!--                        Oleg 5 let-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-4  col-lg-3">-->
<!--                <div class="text-center works_list">-->
<!--                    <img class="works_list_img" :src="'/images/colorings/1_1651087261.jpg'" alt="">-->
<!--                    <div class="who_works">-->
<!--                        Oleg 5 let-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-4  col-lg-3">-->
<!--                <div class="text-center works_list">-->
<!--                    <img class="works_list_img" :src="'/images/colorings/1_1651087261.jpg'" alt="">-->
<!--                    <div class="who_works">-->
<!--                        Oleg 5 let-->
<!--                    </div>-->
<!--                </div>-->
<!--&lt;!&ndash;                <span class="streak_black_works iconify" data-icon="codicon:triangle-right" data-width="80" data-height="240"></span>&ndash;&gt;-->
<!--            </div>-->
<!--            <div v-if="menu_size>991" class="col-lg-3">-->
<!--                <div class="text-center works_list_empty">-->
<!--                    <div class="works_text">-->
<!--                       <span class="works_add_plus">+</span>-->
<!--                       <div class="works_add">Добавить свой вариант</div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div v-if="menu_size<992" class="col-12 add_your_coloring text-center">Добавить свой вариант</div>-->
        <div v-if="menu_size<992" class="col-12 row">
            <div class="col-6">
                <div class="text-center">
                    <img class="main_list_rek_mob" :src="'/images/colorings/1_1651087261.jpg'" alt="">
                </div>
            </div>
            <div class="col-6">
                <div class="text-center">
                    <img class="main_list_rek_mob" :src="'/images/colorings/1_1651087261.jpg'" alt="">
                </div>
            </div>
        </div>
        <div class="col-12 streack"></div>
        <div class="col-12 header_works text-center">Похожие раскраски</div>
    </div>
        <div class="one_coloring_back_white">
        <div  class=" one_front_coloring " v-for="(colored,index) in coloring_list">
            <div class="col-12 row reklama_row_main_list" v-if="(index===reklama_number)&&(hide_on_mob)&&(menu_size>991)">
                <div class="col-3 ">
                    <div class="text-center">
                        <img class="main_list_rek" :src="'/images/colorings/1_1651087261.jpg'" alt="">
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center">
                        <img class="main_list_rek" :src="'/images/colorings/1_1651087261.jpg'" alt="">
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center">
                        <img class="main_list_rek" :src="'/images/colorings/1_1651087261.jpg'" alt="">
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center">
                        <img class="main_list_rek" :src="'/images/colorings/1_1651087261.jpg'" alt="">
                    </div>
                </div>
            </div>
            <span v-else class="row">
                     <div class="col-4 col-lg-3 front-list-new-np" v-on:click="go_to_one_coloring(colored.slug)">
                          <img class="front-list-img-new front-list-img-new-one-coloring" :src="'/images/colorings/'+colored.img" alt="">
                     </div>
                    <div class="col-8 col-lg-9">
                        <div class="coloring_list_title_new col-lg-12 front-list-new-np-l"  v-on:click="go_to_one_coloring(colored.slug)">{{ colored.name }}</div>
                        <div class="offset-lg-0 col-lg-11 coloring_list_text justify-content-start front-list-new-np-l">{{ colored.description }}</div>
                        <div class="rate_coloring_list col-12 offset-xl-5 col-xl-6 offset-lg-4 col-lg-8 text-md-right">
                            <span v-if="menu_size<993" class="iconify like_list" data-icon="icon-park-outline:like" data-width="21" data-height="17"></span>
                            <span v-show="colored.type_of_like=='1'"  class="like_up1"   v-on:click="setLike('1',colored.id,coloring_list)" ><span  class="iconify" data-icon="ant-design:like-filled" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                            <span v-show="colored.type_of_like==('0'||'2')"  class="like_down2" v-on:click="setLike('1',colored.id,coloring_list)" ><span  class="iconify" data-icon="ant-design:like-outlined" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                            <span class="coloring_rate">{{ colored.count_of_like }}</span>
                            <span v-show="colored.type_of_like=='2'" class="like_down3" v-on:click="setLike(2,colored.id,coloring_list)" ><span class="iconify" data-icon="ant-design:dislike-filled" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height'></span></span>
                            <span v-show="colored.type_of_like==('0'||'1')" class="like_down4" v-on:click="setLike(2,colored.id,coloring_list)" ><span class="iconify" data-icon="ant-design:dislike-outlined" v-bind:data-width='menu_data_width' v-bind:data-height='menu_data_height' ></span></span>

                            <span v-if="menu_size<993" class="iconify follow_arrow_mob" data-icon="akar-icons:arrow-forward-thick-fill" v-bind:data-width='menu_data_width_arrow'  v-bind:data-height='menu_data_height_arrow'></span>
                            <!--                            <span class="iconify" data-icon="ant-design:star-filled" data-width="25" data-height="23"></span>-->
                            <span v-if="menu_size>993" class="iconify like_list" data-icon="icon-park-outline:like" data-width="25" data-height="23"></span>
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

export default {
    props: ['auth_user'],
    data() {
        return {
            coloring_name: '',
            description:'',
            mainImage:'',
            colorSlug:'',
            menu_data_width:'',
            menu_data_height:'',
            hide_on_mob:true,
            coloring_list:[],
            reklama_number:4,
            menu_size:'',
            menu_data_width_arrow:'',
            menu_data_height_arrow:'',
            menu_data_height_main_mob:'',
            menu_data_width_main_mob:'',
            window_height:'',
            one_count_of_like:'',
            one_type_of_like:'',
            nickname:'',
            cat_list:[],
            category_list:[],
            cat_flag:true,
            category_flag:false,
            preload:false,
            offset:0,
            types_count:0,
        };
    },
    mounted() {
        this.get_size();
        this.getColoredData(this.cat_list,this.category_list),
        this.get_coloring_list(this.coloring_list);
        this.scroll();
    },
    created() {

    },
    methods: {
        scroll () {
            window.onscroll = () => {
                let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight

                if (bottomOfWindow) {
                    this.get_coloring_list(this.coloring_list)
                }
            }
        },
        go_to_col()
        {
            window.location.href = '/'
        },
        print()
        {
            const iframe = document.createElement('iframe');
            iframe.style.height = 0;
            iframe.style.visibility = 'hidden';
            iframe.style.width = 0;
            iframe.setAttribute('srcdoc', '<html><body></body></html>');
            document.body.appendChild(iframe);
            iframe.addEventListener('load', function () {
                const image = document.getElementById('image').cloneNode();
                image.style.maxWidth = '100%';
                const body = iframe.contentDocument.body;
                body.style.textAlign = 'center';
                body.appendChild(image);

                image.addEventListener('load', function() {
                    // Invoke the print when the image is ready
                    iframe.contentWindow.print();
                });
            });
            iframe.contentWindow.addEventListener('afterprint', function () {
                iframe.parentNode.removeChild(iframe);
            });
        },
        download_img()
        {
            const url = '/download/1_1657188234.jpg';
            window.location.href = url;
            // axios
            //     .get('/download/1_1657188234.jpg', {
            //     })
        },
        go_to_one_cat(slug)
        {
            window.location.href = '/cat/'+slug
        },
        change_status_cat(stat)
        {
            if(stat=='cat')
            {
                this.cat_flag=true
                this.category_flag=false
            }
            else
            {
                this.category_flag=true
                this.cat_flag=false
            }
        },
        setLike(n,colored_id,inp) {
            if(this.auth_user) {
                this.coloring_list.forEach(function (item, i) {
                    if (item.id === colored_id) {
                        axios
                            .post('/setLike', {
                                type_of_like: n,
                                colored_id: colored_id
                            })
                        if (inp[i]['type_of_like'] == n) {
                            inp[i]['type_of_like'] = '0'
                            if (n == '1') {
                                inp[i]['count_of_like'] = (Number(inp[i]['count_of_like']) - Number(1))
                            }
                        } else {
                            if (n == '1') {
                                inp[i]['count_of_like'] = (Number(inp[i]['count_of_like']) + Number(1))
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
        setLike_one_coloring(n,colored_id) {
            if(this.auth_user) {
                          axios
                            .post('/setLike', {
                                type_of_like: n,
                                colored_id: colored_id
                            })
                        if (this.one_type_of_like == n) {
                            this.one_type_of_like = '0'
                            if (n == '1') {
                                this.one_count_of_like = (Number(this.one_count_of_like) - Number(1))
                            }
                        } else {
                            if (n == '1') {
                                this.one_count_of_like = (Number(this.one_count_of_like) + Number(1))
                            }
                            this.one_type_of_like = n;
                        }
            }
            else
            {
                window.location.href = '/login'
            }
        },

        get_size() {
            this.menu_size=window.innerWidth;
            this.window_height=(window.innerHeight-60);
            //получаем высоту экрана
            // console.log(this.window_height)
            // console.log(this.menu_size);
            if(this.menu_size<992)
            {
                this.menu_data_height_main_mob=30
                this.menu_data_width_main_mob=30
            }
            if(this.menu_size>992&&this.menu_size<1100)
            {
                this.menu_data_width=22
                this.menu_data_height=20

            }
            if(this.menu_size>1099)
            {
                this.menu_data_width=26
                this.menu_data_height=24
            }
        },
        getColoredData(cat_list,category_list)
        {
            let adress=window.location.href;
            this.colorSlug = adress.split("/")[4];
            axios
                .post('/getOneFrontColoring',{
                    colorSlug:this.colorSlug
                })
                .then(({ data }) => (
                            this.one_coloring_id=data.coloring[0]['id'],
                            this.coloring_name=data.coloring[0]['name'],
                            this.description=data.coloring[0]['description'],
                            this.mainImage='/images/colorings/'+data.coloring[0]['img'],
                            this.one_count_of_like=data.coloring[0]['count_of_like'],
                            this.nickname=data.coloring[0]['nickname'],
                            this.one_type_of_like=data.coloring[0]['type_of_like'],
                                data.cat.forEach(function(entry) {
                                    cat_list.push({
                                        id:entry.id,
                                        cat_id:entry.cat_id,
                                        cat_name:entry.cat.name,
                                        slug:entry.cat.slug,
                                    });
                                }),
                                data.category.forEach(function(entry) {
                                    category_list.push({
                                        id:entry.id,
                                        cat_id:entry.cat_id,
                                        category_name:entry.categories.name,
                                    });
                                })
                    )
                )
        },
        go_to_one_coloring(id)
        {
            window.location.href =('/coloring/'+id)
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
                    search_id:this.search_id
                })
                .then(({ data }) => (
                    this.types_count=data.tipes_count,
                            data.list_colored.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    name:entry.name,
                                    description:entry.description,
                                    img:entry.img,
                                    published_db:entry.published,
                                    slug:entry.slug,
                                    type_of_like:entry.type_of_like,
                                    count_of_like:entry.count_of_like
                                });
                            }),
                this.preload=false
                    ),
                this.offset=Number(this.offset)+Number(20),
                );
            }
        },
    }
}
</script>
