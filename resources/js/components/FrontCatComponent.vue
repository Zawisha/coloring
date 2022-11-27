<template>
    <div class="container container-fluid ">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="col-12 row reklama_row_main_list">
                    <div v-for="(cat,index) in cat_list"  class="offset-0 offset-sm-0 col-6 col-sm-6 col-md-4 col-lg-4 col-xl-3 cat_list_front_main ">
                        <div  class="text-center cat_back_white">
                            <img v-on:click="go_to_one_cat(cat.slug)" v-if="cat.img!=null" class="col cat_list_front_main_img cat_list_one" :src="'/images/cat/'+cat.img" alt="">
                            <img v-on:click="go_to_one_cat(cat.slug)" v-else class="cat_list_front_main_img cat_list_one" :src="'/images/no_img.jpg'" alt="">
                            <div class="streack_cat"></div>
                            <div v-on:click="go_to_one_cat(cat.slug)" class="front_cat_list_title d-flex justify-content-center align-items-center cat_list_one">{{ cat.name }}</div>
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
            cat_list:[],
            preload:false,
            offset:0,
            types_count:0,
        };
    },
    mounted() {
            this.get_cat_list(this.cat_list);
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

                    this.get_cat_list(this.cat_list)
                }

            }
        },
        go_to_one_cat(slug)
        {
            window.location.href = '/cat/'+slug
        },
        get_cat_list(inp)
        {
            if(this.offset<=this.types_count)
            {
            axios.post('/get_cat_list', {
                offset:this.offset,
                }
            )
                .then(({ data }) => (
                    this.types_count=data.tipes_count,
                            data.list_tags.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    name:entry.name,
                                    slug:entry.slug,
                                    img:entry.img,
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
