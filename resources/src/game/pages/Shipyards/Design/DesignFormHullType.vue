<script>
/******************************************************************************
 * PageComponent: DesignFormHullType
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "DesignFormHullType",
    components: { SubHeadline, Icon },
    setup() {
        const store = useStore();
        const shipTypes = computed(() => {
            const hullTypes = [];
            const rules = window.rules.shipyards.hullTypes;
            // loop all shipyards
            store.state.shipyards.shipyards.forEach((shipyard) => {
                const types = rules[shipyard.type].construct;
                // loop all types that can be constructed in the shipyard
                types.forEach((type) => {
                    // if they do not already exist, add them
                    if (!hullTypes.includes(type)) hullTypes.push(type);
                });
            });
            return hullTypes;
        });
        const selectedShipType = computed({
            get: () => store.state.shipyards.design.hullType,
            set: (value) => {
                store.commit("shipyards/SET_DESIGN_HULLTYPE", value);
            },
        });
        return {
            shipTypes,
            selectedShipType,
        };
    },
};
</script>

<template>
    <sub-headline
        :headline="`#1 ${$t('shipyards.design.hullType.headline')}`"
    />
    <div class="hull-types">
        <label
            v-for="type in shipTypes"
            :key="`shipType${type}`"
            :for="`shipType${type}`"
            :title="
                $t('shipyards.design.hullType.radioLabel', {
                    type: $t('shipyards.hulls.' + type),
                })
            "
            :aria-label="
                $t('shipyards.design.hullType.radioLabel', {
                    type: $t('shipyards.hulls.' + type),
                })
            "
        >
            <input
                type="radio"
                name="shipyard_type"
                :id="`shipType${type}`"
                :value="type"
                v-model="selectedShipType"
            />
            <label :for="`shipType${type}`" class="hull-types__type">
                <icon :name="`hull-${type}`" />
                {{ $t("shipyards.hulls." + type) }}
            </label>
        </label>
    </div>
</template>

<style lang="scss" scoped>
.hull-types {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));

    margin-bottom: 8px;
    grid-gap: 4px;

    @include respond-to("medium") {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));

        margin-bottom: 16px;
        grid-gap: 8px;
    }

    &__type {
        display: flex;
        align-items: center;
        justify-content: center;

        padding: 8px;

        cursor: pointer;

        transition: background-color map-get($animation-speeds, "fast"),
            border-color map-get($animation-speeds, "fast"),
            color map-get($animation-speeds, "fast");

        @include themed() {
            border: 1px solid t("g-abbey");

            background: t("g-deep");
            color: t("t-light");
        }

        &:hover {
            @include themed() {
                border: 1px solid t("g-raven");

                background: t("g-sunken");
                color: t("t-light");
            }
        }

        > .icon {
            width: 24px;
            height: 12px;
            margin-right: 4px;
            flex: 0 0 24px;

            @include respond-to("medium") {
                width: 36px;
                height: 18px;
                margin-right: 8px;
                flex: 0 0 36px;
            }
        }
    }

    input[type="radio"] {
        display: none;
    }

    input[type="radio"]:checked + label {
        @include themed() {
            border: 1px solid t("g-astral");

            background: t("g-iron");
            color: t("t-dark");
        }
    }
}
</style>
