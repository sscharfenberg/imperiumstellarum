<script>
/******************************************************************************
 * PageComponent: StarInfoModal
 *****************************************************************************/
import { computed, ref, onMounted } from "vue";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import Modal from "Components/Modal/Modal";
import Loading from "Components/Loading/Loading";
export default {
    name: "StarInfoModal",
    props: {
        starId: String,
    },
    emits: ["close"],
    components: { Modal, Loading },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();
        const requesting = ref(false);
        const star = computed(() =>
            store.getters["starchart/starById"](props.starId)
        );
        const modalTitle = computed(() => {
            return `${i18n.t("starchart.star.title")}: „${star.value.name}“`;
        });
        const spectralClass = computed(
            () => "spectral--" + star.value.spectral
        );
        onMounted(() => {
            const gameId = document.getElementById("game").dataset.gameId;
            requesting.value = true;
            window.axios
                .get(`/api/game/${gameId}/star/${star.value.id}/details`)
                .then((response) => {
                    console.log(response);
                })
                .catch((e) => {
                    console.error(e);
                })
                .finally(() => {
                    requesting.value = false;
                });
        });
        return {
            modalTitle,
            requesting,
            star,
            spectralClass,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <teleport to="#StarChartModal">
        <modal :title="modalTitle" @close="$emit('close')">
            <ul class="stats" v-if="!requesting">
                <li class="stats--centered text-left">
                    {{ $t("starchart.star.spectral.label") }}
                </li>
                <li class="spectral-class justified">
                    {{
                        $t("starchart.star.spectral.value", {
                            type: star.spectral,
                        })
                    }}
                    <div class="spectral" :class="spectralClass"></div>
                </li>
                <li class="text-left">
                    {{ $t("starchart.star.ownerName.label") }}
                </li>
                <li class="text-left" :class="{ featured: star.ownerName }">
                    {{
                        star.ownerName
                            ? star.ownerName
                            : $t("starchart.star.ownerName.none")
                    }}
                </li>
                <li class="text-left" v-if="star.ownerTicker">
                    {{ $t("starchart.star.ownerTicker") }}
                </li>
                <li class="text-left featured" v-if="star.ownerTicker">
                    {{ star.ownerTicker }}
                </li>
            </ul>
            <loading :size="32" v-if="requesting" />
        </modal>
    </teleport>
</template>

<style lang="scss" scoped>
.spinner {
    margin: 0 auto;
}

.spectral-class {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.spectral {
    z-index: z("form");

    width: 24px;
    height: 24px;
    margin-right: 0;
    flex: 0 0 24px;

    background: url("@/theme/spectral-types.png") 0 0 no-repeat;
    background-size: 24px;

    text-indent: -1000em;

    &--O {
        background-position: 0 0;
    }
    &--B {
        background-position: 0 -24px;
    }
    &--A {
        background-position: 0 -48px;
    }
    &--F {
        background-position: 0 -72px;
    }
    &--G {
        background-position: 0 -96px;
    }
    &--K {
        background-position: 0 -120px;
    }
    &--M {
        background-position: 0 -144px;
    }
    &--Y {
        background-position: 0 -168px;
    }
}
</style>
