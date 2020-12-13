<script>
/******************************************************************************
 * PageComponent: DesignFormModules
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import Icon from "Components/Icon/Icon";
export default {
    name: "DesignFormModules",
    components: { SubHeadline, Icon },
    setup() {
        const store = useStore();
        const hull = computed(() => store.state.shipyards.design.hullType);
        const tl = computed(() => store.state.shipyards.techLevels);
        const modules = computed(() => {
            if (hull.value) {
                return window.rules.modules.filter(
                    (mod) => mod.hullType === hull.value
                );
            }
            return [];
        });
        const shipSlots = computed(() =>
            hull.value ? window.rules.ships.hullTypes[hull.value].slots : 0
        );
        return {
            hull,
            shipSlots,
            modules,
            tl,
        };
    },
};
</script>

<template>
    <sub-headline :headline="`#2 ${$t('shipyards.design.modules.headline')}`" />
    <div class="module-stage" v-if="hull.length">
        <header class="module-stage__header">Verfügbar</header>
        <header class="module-stage__header">Eingebaut</header>
        <div class="available column">
            <header class="modules__type">Offensiv</header>
            <div class="modules">
                <button
                    class="modules__mod"
                    v-for="module in modules.filter(
                        (mod) => mod.moduleType === 'offensive'
                    )"
                    :key="`offensive${module.stub}`"
                >
                    <icon :name="`tech-${module.techType}`" />
                    <span>{{ $t("research.tl." + module.techType) }}</span>
                    <span class="tl"
                        >TL{{
                            tl.find((tl) => tl.type === module.techType).level
                        }}</span
                    >
                </button>
            </div>
            <header class="modules__type">Defensiv</header>
            <div class="modules">
                <button
                    class="modules__mod"
                    v-for="module in modules.filter(
                        (mod) => mod.moduleType === 'defensive'
                    )"
                    :key="`defensive${module.stub}`"
                >
                    <icon :name="`tech-${module.techType}`" />
                    <span>{{ $t("research.tl." + module.techType) }}</span>
                    <span class="tl"
                        >TL{{
                            tl.find((tl) => tl.type === module.techType).level
                        }}</span
                    >
                </button>
            </div>
            <header class="modules__type">Engineering</header>
            <div class="modules">
                <button
                    class="modules__mod"
                    v-for="module in modules.filter(
                        (mod) => mod.moduleType === 'engineering'
                    )"
                    :key="`engineering${module.stub}`"
                >
                    <icon :name="`tech-${module.techType}`" />
                    <span>{{ $t("research.tl." + module.techType) }}</span>
                </button>
            </div>
        </div>
        <div class="using column">
            <div class="modules">
                <div
                    v-for="slot in shipSlots"
                    :key="slot"
                    class="modules__mod"
                ></div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.module-stage {
    display: grid;
    grid-template-columns: 1fr 1fr;

    margin-bottom: 8px;
    grid-gap: 8px;

    @include respond-to("medium") {
        margin-bottom: 16px;
        grid-gap: 16px;
    }

    &__header {
        display: flex;
        align-items: center;

        @include themed() {
            color: t("t-tint");
        }

        &::after,
        &::before {
            display: block;

            height: 1px;
            flex-grow: 1;

            content: " ";

            @include themed() {
                background-color: t("g-deep");
            }
        }

        &::after {
            margin-left: 8px;

            @include respond-to("medium") {
                margin-left: 16px;
            }
        }

        &::before {
            margin-right: 8px;

            @include respond-to("medium") {
                margin-right: 16px;
            }
        }
    }

    .column {
        padding: 4px 4px 0 4px;
        border: 2px solid transparent;

        @include themed() {
            background-color: t("g-bunker");
            border-color: t("g-deep");
        }

        @include respond-to("medium") {
            padding: 16px 16px 8px 16px;
        }
    }
}

.modules {
    display: flex;
    flex-direction: column;

    &__mod {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;

        min-height: 40px;
        padding: 2px;
        border: 1px solid transparent;
        margin: 0 0 4px 0;

        background-color: transparent;
        outline: 0;
        cursor: pointer;

        font-size: 12px;

        transition: background-color map-get($animation-speeds, "fast") linear;

        @include respond-to("medium") {
            padding: 4px;
            margin-bottom: 8px;

            font-size: 14px;
        }

        @include respond-to("large") {
            padding: 8px;

            font-size: 16px;
        }

        @include themed() {
            color: t("t-light");
            border-color: t("g-raven");
        }

        &:hover {
            @include themed() {
                background-color: t("g-ebony");
            }
        }

        .icon {
            width: 20px;
            height: 20px;

            @include respond-to("small") {
                width: 24px;
                height: 24px;
            }
        }

        .tl {
            display: none;

            @include respond-to("large") {
                display: block;
            }
        }
    }

    &__type {
        padding: 8px;
        border: 1px solid transparent;
        margin-bottom: 8px;
        clip-path: polygon(
            0 0,
            calc(100% - 10px) 0,
            100% 10px,
            100% 100%,
            10px 100%,
            0 calc(100% - 10px)
        );

        @include themed() {
            background-color: t("g-deep");
        }
    }
}
</style>
