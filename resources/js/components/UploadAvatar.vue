<template>
    <section class="upload-example" >
        <Cropper
            v-if="image&&up_exam"
            class="upload-example-cropper"
            :src="image"
            ref="cropper"
            :stencilComponent="FaceStencil"
        />

        <div class="button-wrapper">
            <label class="button">
<!--                <input type="file" @change="uploadImage" v-on:click="$emit('enlarge-text',false)" accept="image/*">-->
                <input type="file" @change="uploadImage" v-on:click="$emit('enlarge-text',false)" accept="image/*">
                📸 Поменять аватарку
            </label>
            <button v-if="image" class="button" @click="crop">Сохранить аватар</button>
        </div>
    </section>
</template>

<script>
import { Cropper } from "vue-advanced-cropper";
import FaceStencil from "./FaceStencil";

export default {
    name: "upload-avatar",
    data() {
        return {
            image: null,
            FaceStencil,
            coordinates: {
                width: 0,
                height: 0,
                left: 0,
                top: 0
            },
            img_new:'',
            up_exam:true
        };
    },
    components: { Cropper},
    methods: {
        roundEdges(canvas) {
            let context = canvas.getContext('2d');

            let width = canvas.width;
            let height = canvas.height;
            //

            // //открываем второй путь
            context.beginPath();
            // //рисуем внутри
            context.globalCompositeOperation = "destination-in";
            // context.rect(20, 20, 150, 100);
            context.fillStyle = "red";
            // //масштабируем
            context.scale(1, height/width);
            // //рисуем дугу
            context.arc(width / 2, width / 2, width / 2, 0, Math.PI * 2);
            // context.closePath();
            context.fill();
            return canvas;
        },
        crop() {
             // this.up_exam=false
            const { canvas } = this.$refs.cropper.getResult();
            this.$emit("submit", this.roundEdges(canvas).toDataURL());
        },
        uploadImage(event) {
            console.log('ok')
            // this.up_exam=true


            const input = event.target;

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    this.image = e.target.result;
                };
                // Start the reader job - read file as a data url (base64 format)
                reader.readAsDataURL(input.files[0]);
            }
        }
    }
};
</script>

<style lang="scss">
.upload-example {
    max-width: 95%;
    width: 40rem;
    margin: 2rem auto;

    .instructions {
        margin-bottom: 1rem;
    }

    .upload-example-cropper {
        border: solid 1px #eee;
        height: 300px;
        width: 100%;
    }
    .button {
        display: inline-block;
        border: 2px dashed #223;
        padding: 1rem;
        margin-top: 1rem;
    }
    .button + .button {
        margin-left: 1rem;
    }

    .button input {
        display: none;
    }
}
</style>
