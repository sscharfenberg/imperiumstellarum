<script>
/******************************************************************************
 * PageComponent: RenderStar
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref } from "vue";
import { useI18n } from "vue-i18n";
import StarInfoModal from "./StarInfoModal/StarInfoModal";
export default {
    name: "RenderStar",
    props: {
        zoom: Number, // 0..4
        id: String,
        bgPos: String,
        top: String,
        left: String,
        ownerId: String,
        ownerColour: String,
        ticker: String,
        name: String,
    },
    components: { StarInfoModal },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();
        const showModal = ref(false);
        const empireId = computed(() => store.state.empireId);
        const starClass = computed(() =>
            empireId.value === props.ownerId ? "my" : ""
        );
        const bgColour = computed(() =>
            props.zoom <= 2 ? props.ownerColour : "transparent"
        );
        const starTitle = computed(() => {
            let title = `${props.name}: ${i18n.t(
                "starchart.star.buttonLabel"
            )}`;
            if (props.ownerId) title = `[${props.ticker}] ${title}`;
            return title;
        });
        return {
            showModal,
            bgColour,
            starClass,
            starTitle,
        };
    },
};
</script>

<template>
    <button
        class="star"
        :class="starClass"
        :style="{
            '--bgPos': bgPos,
            '--ownerColour': ownerColour,
            top: top,
            left: left,
            backgroundColor: bgColour,
        }"
        :title="starTitle"
        :aria-label="starTitle"
        @click="showModal = true"
    >
        <aside class="ticker" v-if="ticker">{{ ticker }}</aside>
    </button>
    <star-info-modal
        v-if="showModal"
        @close="showModal = false"
        :star-id="id"
    />
</template>

<style lang="scss" scoped>
.star {
    position: absolute;

    box-sizing: content-box;
    overflow: hidden;
    width: var(--cssTileSize);
    height: var(--cssTileSize);
    padding: 0;
    border-width: var(--borderWidth);

    background: transparent url("@/theme/spectral-types.png") var(--bgPos)
        no-repeat;
    background-size: var(--cssTileSize);
    border-style: dashed;
    border-color: var(--ownerColour);

    cursor: pointer;

    text-align: right;

    &.my {
        border-style: solid;
    }

    &:active,
    &:focus {
        outline: 0;
    }
}

.ticker {
    position: absolute;
    top: 0;
    right: 0;

    padding: 2px 4px;

    font-size: calc(var(--cssTileSize) / 5);

    @include themed() {
        background: rgba(t("g-raven"), 0.7);
        color: t("g-white");
    }
}
</style>
