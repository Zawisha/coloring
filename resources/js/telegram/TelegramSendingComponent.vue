<template>
    <div class="container">
        <div>
            1. менять значения в таблице telegram_invites, значения введенные здесь не сохраняются
        </div>
        <button type="button" class="btn btn-secondary" v-on:click="invite_users()">Начать работу</button>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Группа</th>
                <th scope="col">Номер</th>
                <th scope="col">Сделано</th>
                <th scope="col">Предохранитель</th>
                <th scope="col">Осталось всего</th>
                <th scope="col">Выполнить</th>
                <th scope="col">Лог</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(group, index) in global_arr">
                <th>
                    <textarea class="form-control textarea_admin" rows="1"  name="text" v-model=group.group ></textarea>
                </th>
                <td>
                    <textarea class="form-control textarea_admin" rows="1"  name="text" v-model=group.number></textarea>
                </td>
                <td>{{ group.loc_counter }}</td>
                <td>{{ group.fuse_counter }}</td>
                <td>{{ group.count_user }}</td>
                <td><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" v-model=group.do></td>
                <td><button type="button" class="btn btn-primary" v-on:click="log(index)">Лог</button></td>
            </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
    import axios from "axios";

    export default {
        data() {
            return {
                vk_group:'',
                telegram_channel:'',
                response_add_vk_to_channel:'',
                post_list:new Array(),
                response_post_list:new Array(),
                post_list_one:'',
                added_message_post_list:'',
                show_instruction:false,
                number_in_array:0,
                number:'+79612614719',
                group:'https://t.me/avto_moskva_ok',
                //счётчик для одного телефона
                number_local_counter:0,
                //id технологии
                technology_id:'32',
                global_arr:new Array(),
                work_counter:0,
            }
        },

        mounted() {
            this.get_start_data_telegram(this.global_arr);
        },
        methods: {
            get_start_data_telegram(inp)
            {
                axios
                    .post('/get_start_data_telegram',{

                    })
                    .then(({ data }) => {
                        data.message.forEach(function(entry) {
                            inp.push({
                                id:entry.id,
                                group:entry.group,
                                number:entry.number,
                                do:entry.do,
                                count_user:entry.count_user,
                                loc_counter:0,
                                fuse_counter:0,
                                log:[]
                            });
                        })
                    })

            },
            log(index)
            {
                console.log(this.global_arr[index]['log']);
            },
            invite_users()
            {
                if(this.global_arr.length!=this.work_counter) {
                    if (this.global_arr[this.work_counter]['do']==1)
                    {
                        alert('ok1')

                        axios
                            .post('/invite_users', {
                                phone: this.global_arr[this.work_counter]['number'],
                                group: this.global_arr[this.work_counter]['group'],
                            })
                            .then(({data}) => {
                                this.global_arr[this.work_counter]['fuse_counter']++
                                    if (data.success === 'yes') {
                                        this.global_arr[this.work_counter]['loc_counter']++
                                        this.global_arr[this.work_counter]['log'].push(data.message)
                                        if ((this.global_arr[this.work_counter]['loc_counter'] < 40)||(this.global_arr[this.work_counter]['loc_counter'] < 100)) {
                                            // this.invite_users()
                                        } else {
                                            this.work_counter++
                                            // this.invite_users()
                                        }
                                    } else {
                                        if (data.critical === 'yes') {
                                            this.global_arr[this.work_counter]['log'].push(data.message)
                                            this.work_counter++
                                            // this.invite_users()
                                        } else {
                                            if (this.global_arr[this.work_counter]['loc_counter'] < 100) {
                                                this.global_arr[this.work_counter]['log'].push(data.message)
                                                // this.invite_users()
                                            }
                                            else
                                            {
                                                this.global_arr[this.work_counter]['log'].push(data.message)
                                                this.work_counter++
                                                // this.invite_users()
                                            }

                                        }
                                    }
                                },
                            )
                }
                    else
                    {
                        this.work_counter++
                        // this.invite_users()
                    }
                }
                else
                {
                    alert('сделано')
                }
            },
            show_instruction_f()
            {
              this.show_instruction=(!this.show_instruction)
            },

        }
    }
</script>
