<template>
    <autocomplete
        :search="search"
        :get-result-value="getResultValue"
        :debounce-time="500"
        placeholder="поиск раскрасок"
        ref="autocom_tag"
        :auto-select=true
        @submit="handleSubmit"
    ></autocomplete>

</template>

<script>
import {eventSearch} from "../app";

export default {
    data() {
        return {
            search_result:'',
            search_result_id:'',
        };
    },
    mounted() {

    },
    methods: {
        go_to_user_add_coloring()
        {
            this.flag=2;
            //генерируем событий
            eventSearch.$emit("click_search",this.flag);

        },

        search(input)
        {
            //передалть пусть сразу ищет а не возвращает результаты
            return new Promise(resolve => {
                if (input.length < 1) {
                    return resolve([])
                }
                else
                {
                     axios
                         .post('/get_coloring_names',{
                             req:input
                         }).then(response => {
                         resolve(response.data)
                     })
                }

            })
        },
        getResultValue(result) {
            // return result.name
            return result.name
        },
        handleSubmit(result)
        {

            //результат забирать отсюда
            // this.color_id = adres.split("/")[5];
            // console.log(adres.split("/")[3])
                window.location.href='/?search='+result.name
           //     eventSearch.$emit("click_search",result.name);
            // this.search_result= result.name
            // this.search_result_id=result.id
            // console.log(this.search_result)
           // this.$refs.autocom_tag.setValue('')
            // this.add_tag_to_tag_list()
        },

    }
}
</script>

