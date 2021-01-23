<script>
/******************************************************************************
 * PageComponent: StarInfoModal
 *****************************************************************************/
import { computed, ref, onMounted } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import Loading from "Components/Loading/Loading";
import StarInfoModalMovingFleets from "./StarInfoModalMovingFleets";
import StarInfoModalFleetsAtStar from "./StarInfoModalFleetsAtStar";
import StarInfoModalSendFleetsHere from "./StarInfoModalSendFleetsHere";
export default {
    name: "StarInfoModal",
    props: {
        starId: String,
    },
    emits: ["close"],
    components: {
        Modal,
        Loading,
        StarInfoModalMovingFleets,
        StarInfoModalFleetsAtStar,
        StarInfoModalSendFleetsHere,
    },
    setup(props) {
        const store = useStore();
        const requesting = ref(false);
        const star = computed(() =>
            store.getters["starchart/starById"](props.starId)
        );
        const numPlanets = ref(0);
        const population = ref(0);
        const spectralClass = computed(
            () => "spectral--" + star.value.spectral
        );
        onMounted(() => {
            const gameId = document.getElementById("game").dataset.gameId;
            requesting.value = true; // show loader
            // get star details from server
            window.axios
                .get(`/api/game/${gameId}/star/${star.value.id}/details`)
                .then((response) => {
                    if (response.status === 200) {
                        numPlanets.value = response.data.planets;
                        population.value = response.data.population;
                    }
                })
                .catch((e) => {
                    console.error(e);
                })
                .finally(() => {
                    requesting.value = false; // hide loader again
                });
        });
        return {
            requesting,
            star,
            spectralClass,
            numPlanets,
            population,
        };
    },
};
</script>

<template>
    <teleport to="#StarChartModal">
        <modal
            :title="$t('starchart.star.title', { name: star.name })"
            @close="$emit('close')"
        >
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

                <li class="text-left" v-if="numPlanets">
                    {{ $t("starchart.star.numPlanets") }}
                </li>
                <li class="text-left" v-if="numPlanets">{{ numPlanets }}</li>

                <li class="text-left" v-if="population">
                    {{ $t("starchart.star.population") }}
                </li>
                <li class="text-left" v-if="population">{{ population }}</li>

                <star-info-modal-fleets-at-star :star-id="starId" />
                <star-info-modal-moving-fleets :star-id="starId" />
                <star-info-modal-send-fleets-here :star-id="starId" />
            </ul>
            <div class="scanning" v-if="requesting">
                <loading :size="32" />
                <span>Scanning Star...</span>
            </div>
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

.stats {
    grid-template-columns: 2fr 3fr;

    margin-bottom: 0;
}

.scanning {
    display: flex;
    align-items: center;
    justify-content: center;

    .spinner {
        margin: 0;
    }

    > span {
        margin-left: 16px;
    }
}
</style>
