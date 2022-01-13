<template>
    <div v-if="show_templ" class="shop_sidebar_area" >
        <!-- ##### Single Widget ##### -->
        <div  class="widget catagory mb-50">
            <!-- Widget Title -->
            <h6 class="widget-title mb-30">Список тегов</h6>
            <div class="catagories-menu">
                <ul class="front-tag-click">
                    <li  v-for="tag in tag_list" v-on:click="set_current_tag(tag.id)">#{{ tag.name }}</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import {eventBusFairy} from "../app";
import {eventBusColoring} from "../app";
import {eventBusVideo} from "../app";

export default {
    data() {
        return {
            tag_list:[],
            type:'',
            show_templ:false
        };
    },
    mounted() {
        this.get_front_tag_list(this.tag_list)
    },
    methods: {
            get_front_tag_list(inp) {
                let uri = window.location.href.split('/');
                if (uri[3] == '') {
                    this.type = 'coloring'
                    this.show_templ = true
                }
                if (uri[3] !== '' && ((uri[3]) === ('fairy-list'))) {
                    this.type = 'fairy'
                    this.show_templ = true
                }
                if (uri[3] !== '' && ((uri[3]) === ('video-list'))) {
                    this.type = 'video'
                    this.show_templ = true
                }
                if (this.show_templ)
                {
                    axios
                        .post('/front_get_tag_list', {
                            type: this.type
                        })
                        .then(({data}) => (
                                data.list_tags.forEach(function (entry) {
                                    inp.push({
                                        id: entry.categories.id,
                                        name: entry.categories.name,
                                    });
                                })
                            )
                        );
                 }
            },
        set_current_tag(id)
        {
            let uri = window.location.href.split('/');
            //в зависимости от роута разные ивенты
            //раскраски
            if(uri[3]=='')
            {
                eventBusColoring.$emit("click_tag_coloring",id);
            }
            if(uri[3]!==''&&((uri[3])===('fairy-list')))
            {
                eventBusFairy.$emit("click_tag_fairy",id);
            }
            if(uri[3]!==''&&((uri[3])===('video-list')))
            {
                eventBusVideo.$emit("click_tag_video",id);
            }
            //сказки
            //видео
          //  eventBusVideo.$emit("click_tag_video",id);

        }
        }
}
</script>
