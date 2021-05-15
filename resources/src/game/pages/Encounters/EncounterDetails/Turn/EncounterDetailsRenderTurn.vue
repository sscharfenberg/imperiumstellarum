<script>
/******************************************************************************
 * Page: EncounterDetailsRenderTurn
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref, onMounted, onUnmounted } from "vue";
import debounce from "lodash/debounce";
import AreaSection from "Components/AreaSection/AreaSection";
import EncounterDetailsFleetRow from "./EncounterDetailsFleetRow";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "EncounterDetailsRenderTurn",
    props: {
        turn: Number,
    },
    components: { AreaSection, EncounterDetailsFleetRow, SubHeadline },
    setup(props) {
        const store = useStore();
        const turnData = computed(() =>
            store.getters["encounters/getTurnByNumber"](props.turn)
        );
        const defender = computed(() => {
            const data = turnData.value.defender;
            return data.sort((a, b) => a.row - b.row);
        });
        const attacker = computed(() => {
            const data = turnData.value.attacker;
            console.log(data);
            return data.sort((a, b) => a.row - b.row);
        });

        const fleetRow = ref(null);
        const rowWidth = ref(0);
        const columnWidth = ref([]);

        /**
         * calculate column widths
         */
        const updateColumnWidths = () => {
            if (fleetRow.value) {
                rowWidth.value = fleetRow.value.clientWidth;
                let width = rowWidth.value;
                let columns = 11;
                columnWidth.value = [];
                while (width > 0 && columns > 0) {
                    let a;
                    if (columns % 2 === 0) {
                        a = Math.floor(width / columns);
                    } else {
                        a = Math.ceil(width / columns);
                    }
                    width -= a;
                    columns--;
                    columnWidth.value.push(a);
                }
                console.log("updated column widths to", columnWidth.value);
            }
        };

        /**
         * @function when component is mounted, calculate widths / margins
         */
        onMounted(() => {
            updateColumnWidths();
            window.addEventListener(
                "resize",
                debounce(() => updateColumnWidths(), 500)
            );
        });
        onUnmounted(() => {
            window.removeEventListener(
                "resize",
                debounce(() => updateColumnWidths(), 500)
            );
        });

        return { fleetRow, columnWidth, turnData, defender, attacker };
    },
};
</script>

<template>
    <area-section :headline="$t('encounters.details.turn', { turn })">
        <sub-headline
            :headline="$t('encounters.details.attacker')"
            :centered="true"
        />
        <section class="arena attacker" ref="fleetRow">
            <encounter-details-fleet-row
                v-for="fleet in attacker"
                :key="fleet.fleetId"
                :fleet-id="fleet.fleetId"
                :name="fleet.name"
                :row="fleet.row"
                :col="fleet.col"
                :ships="fleet.ships"
                :owner-id="fleet.playerId"
                :column-width="columnWidth"
            />
        </section>
        <sub-headline
            :headline="$t('encounters.details.defender')"
            :centered="true"
        />
        <section class="arena defender">
            <encounter-details-fleet-row
                v-for="fleet in defender"
                :key="fleet.fleetId"
                :fleet-id="fleet.fleetId"
                :name="fleet.name"
                :row="fleet.row"
                :col="fleet.col"
                :ships="fleet.ships"
                :owner-id="fleet.playerId"
                :column-width="columnWidth"
            />
        </section>
    </area-section>
</template>

<style lang="scss" scoped>
.arena {
    padding: 4px;
    border: 1px solid transparent;
    margin: 0;

    list-style: none;

    @include themed() {
        background-color: t("g-sunken");
        border-color: t("g-deep");
    }

    @include respond-to("medium") {
        padding: 8px;
    }

    &.attacker {
        margin-bottom: 8px;

        @include respond-to("medium") {
            margin-bottom: 16px;
        }
    }
}
</style>
