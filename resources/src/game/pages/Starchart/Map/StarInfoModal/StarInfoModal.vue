<script>
/******************************************************************************
 * PageComponent: StarInfoModal
 *****************************************************************************/
import { computed, ref, onMounted } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import Loading from "Components/Loading/Loading";
import Modal from "Components/Modal/Modal";
import StarInfoModalFleetsAtStar from "./StarInfoModalFleetsAtStar";
import StarInfoModalMovingFleets from "./StarInfoModalMovingFleets";
import StarInfoModalSendFleetsHere from "./StarInfoModalSendFleetsHere";
export default {
    name: "StarInfoModal",
    props: {
        starId: String,
    },
    emits: ["close"],
    components: {
        GameButton,
        Loading,
        Modal,
        StarInfoModalFleetsAtStar,
        StarInfoModalMovingFleets,
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
        const selectedFleetId = computed(
            () => store.state.starchart.starMoveFleetHereId
        );
        const empireRelation = computed(() => {
            if (!star.value.ownerId) return undefined;
            const rel = store.state.starchart.relations.find(
                (r) => r.playerId === star.value.ownerId
            );
            if (rel && rel.effective >= 0) {
                return rel.effective;
            } else {
                return 1;
            }
        });
        const ownSystem = computed(
            () => star.value.ownerId === store.state.empireId
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

        const onSubmit = () => {
            console.log("submit");
            store.dispatch("starchart/SEND_FLEET", {
                fleetId: selectedFleetId.value,
                destinationId: props.starId,
            });
        };
        return {
            requesting,
            star,
            spectralClass,
            numPlanets,
            population,
            selectedFleetId,
            onSubmit,
            empireRelation,
            ownSystem,
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

                <li class="text-left" v-if="!ownSystem && star.ownerTicker">
                    {{ $t("starchart.star.playerRelation") }}
                </li>
                <li
                    class="text-left"
                    v-if="!ownSystem && star.ownerTicker"
                    :class="{
                        allied: empireRelation === 2,
                        neutral: empireRelation === 1,
                        hostile: empireRelation === 0,
                    }"
                >
                    {{ empireRelation }} ({{
                        $t("diplomacy.status." + empireRelation)
                    }})
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
            </ul>
            <star-info-modal-send-fleets-here :star-id="starId" />
            <div class="scanning" v-if="requesting">
                <loading :size="32" />
                <span>Scanning Star...</span>
            </div>
            <template v-slot:actions>
                <game-button
                    :text-string="$t('starchart.star.sendHere.submit')"
                    icon-name="save"
                    @click="onSubmit"
                    :disabled="!selectedFleetId"
                />
            </template>
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
    margin-bottom: 0;

    li.allied {
        @include themed() {
            border-color: t("s-success");
        }
    }
    li.hostile {
        @include themed() {
            border-color: t("s-error");
        }
    }
    li.neutral {
        @include themed() {
            border-color: t("s-building");
        }
    }
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
