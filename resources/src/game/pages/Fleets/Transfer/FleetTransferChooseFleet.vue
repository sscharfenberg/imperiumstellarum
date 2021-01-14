<script>
/******************************************************************************
 * PageComponent: FleetTransferChooseFleet
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "FleetTransferChooseFleet",
    props: {
        fleetId: String,
        starId: String,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const fleets = computed(() =>
            store.state.fleets.fleets.filter((f) => f.id !== props.fleetId)
        );
        const shipyards = computed(() => store.state.fleets.shipyards);
        const available = computed(() =>
            fleets.value
                .concat(shipyards.value)
                .filter((loc) => loc.starId === props.starId)
        );

        const selectedTransferId = computed({
            get: () => store.state.fleets.transferId,
            set: (value) => {
                store.commit("fleets/SET_TRANSFER_ID", value);
            },
        });

        return { available, selectedTransferId };
    },
};
</script>

<template>
    <ul class="items">
        <li class="item" v-for="item in available" :key="item.id">
            <input
                type="radio"
                :id="`chooseItem-${item.id}`"
                name="selectTransferId"
                v-model="selectedTransferId"
                :value="item.id"
                :aria-labelledby="`chooseItem-${item.id}-label`"
            />
            <label
                :for="`chooseItem-${item.id}`"
                :id="`chooseItem-${item.id}-label`"
            >
                <icon v-if="item.planetName" name="shipyards" />
                <icon v-if="item.name" name="fleets" />
                <span v-if="item.name">{{ item.name }}</span>
                <span v-if="item.planetName">{{ item.planetName }}</span>
            </label>
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.items {
    display: flex;
    flex-wrap: wrap;

    padding: 0;
    margin: 0 0 8px 0;

    list-style: none;

    @include respond-to("medium") {
        margin-bottom: 16px;
    }
}

.item {
    margin-right: 4px;

    @include respond-to("medium") {
        margin-right: 8px;
    }

    &:last-child {
        margin-right: 0;
    }

    > label {
        display: flex;
        align-items: center;

        padding: 4px;

        cursor: pointer;

        transition: background-color map-get($animation-speeds, "fast") linear;

        @include themed() {
            background-color: t("g-deep");
        }

        @include respond-to("medium") {
            padding: 8px;
        }

        &:hover {
            @include themed() {
                background-color: t("g-bunker");
            }
        }

        .icon {
            margin-right: 4px;

            @include respond-to("medium") {
                margin-right: 8px;
            }
        }
    }

    input[type="radio"] {
        display: none;
    }

    input[type="radio"]:checked + label {
        @include themed() {
            background: t("g-iron");
            color: t("t-dark");
        }

        .icon {
            @include themed() {
                color: t("s-active");
            }
        }
    }
}
</style>
