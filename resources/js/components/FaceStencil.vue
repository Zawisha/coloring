<template>
    <div class="circle-stencil" :style="style">
<!--        <DraggableElement class="circle-stencil__handler" @drag="onHandlerMove">-->
<!--            <img :src="handlerIcon">-->
<!--        </DraggableElement>-->
        <DraggableArea @move="onMove">
            <PreviewResult
                class="circle-stencil__preview"
                :image="image"
                :stencilCoordinates="stencilCoordinates"
                :resultCoordinates="resultCoordinates"
            />
        </DraggableArea>
    </div>
</template>

<script>
// import handlerIcon from "@/assets/handler.svg";
import handlerIcon from "/images/handler.svg";
// import handlerIcon from "/images/no_img.jpg";

import {
    DraggableElement,
    DraggableArea,
    PreviewResult,
    ResizeEvent
} from "vue-advanced-cropper";

export default {
    name: "face-stencil",
    data() {
        return {
            handlerIcon,
        };
    },
    components: {
        PreviewResult,
        DraggableArea,
        DraggableElement
    },
    props: {
        image: Object,
        resultCoordinates: Object,
        stencilCoordinates: Object
    },
    computed: {

        style() {
            const { height, width, left, top } = this.stencilCoordinates;

            return {
                width: `${width}px`,
                height: `${height}px`,
                left: `${left}px`,
                top: `${top}px`
            };
        }
    },
    methods: {
        onMove(moveEvent) {
            this.$emit("move", moveEvent);
        },
        onHandlerMove(dragEvent) {
            const shift = dragEvent.shift();

            const widthResize = shift.left;
            const heightResize = -shift.top;
            this.$emit(
                "resize",
                new ResizeEvent(dragEvent.nativeEvent, {
                    left: widthResize,
                    right: widthResize,
                    top: heightResize,
                    bottom: heightResize
                })
            );
        },
        aspectRatios() {
            // const ratio = 0.85;
            const ratio = 1;
            return {
                minimum: ratio,
                maximum: ratio
            };
        }
    }
};
</script>

<style lang="scss">
.circle-stencil {
    border-radius: 50%;
    cursor: move;
    position: absolute;
    border: dashed 2px white;
    box-sizing: border-box;
    &__handler {
        position: absolute;
        right: 15%;
        top: 14%;
        z-index: 1;
        cursor: ne-resize;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        transform: translate(50%, -50%);
    }
    &__preview {
        border-radius: 50%;
        overflow: hidden;
    }
}
</style>
