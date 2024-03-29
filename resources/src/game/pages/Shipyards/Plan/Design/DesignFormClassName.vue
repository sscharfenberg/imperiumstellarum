<script>
/******************************************************************************
 * PageComponent: DesignFormClassName
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import Icon from "Components/Icon/Icon";
import Loading from "Components/Loading/Loading";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "DesignFormClassName",
    components: { SubHeadline, Icon, Loading },
    setup() {
        const store = useStore();
        const i18n = useI18n();
        const isLoading = ref(false);
        const className = computed({
            get: () => store.state.shipyards.design.className,
            set: (value) => {
                store.commit("shipyards/SET_DESIGN_CLASSNAME", value);
            },
        });
        const hullType = computed(() => store.state.shipyards.design.hullType);
        const isDisabled = computed(() => hullType.value.length === 0);
        const mods = computed(() => store.state.shipyards.design.modules);
        const btnLabel = computed(() =>
            isDisabled.value
                ? i18n.t("shipyards.design.className.readonly")
                : i18n.t("shipyards.design.className.randomize")
        );
        const classNameLength = window.rules.blueprints.className;

        // enable and show component?
        const componentIsEnabled = computed(
            () =>
                hullType.value.length &&
                mods.value.length ===
                    window.rules.ships.hullTypes[hullType.value].slots
        );

        // click on "randomize" button
        const onRandomize = () => {
            const type = computed(() => store.state.shipyards.design.hullType);
            isLoading.value = true; // show loader
            // get star details from server
            window.axios
                .get(`/api/game/shipyards/${type.value}/randomName`)
                .then((response) => {
                    if (response.status === 200) {
                        store.commit(
                            "shipyards/SET_DESIGN_CLASSNAME",
                            response.data.name
                        );
                    }
                })
                .catch((e) => {
                    console.error(e);
                })
                .finally(() => {
                    isLoading.value = false; // hide loader again
                });
        };

        return {
            componentIsEnabled,
            className,
            isLoading,
            onRandomize,
            isDisabled,
            btnLabel,
            classNameLength,
        };
    },
};
</script>

<template>
    <sub-headline
        v-if="componentIsEnabled"
        :headline="`#3 ${$t('shipyards.design.className.headline')}`"
    />
    <div class="class-name" v-if="componentIsEnabled">
        <input
            type="text"
            v-model="className"
            class="form-control"
            :placeholder="$t('shipyards.design.className.placeHolder')"
            :aria-placeholder="$t('shipyards.design.className.placeHolder')"
            :minlength="classNameLength.min"
            :maxlength="classNameLength.max"
        />
        <button
            class="randomize"
            :title="btnLabel"
            :aria-label="btnLabel"
            @click="onRandomize"
            :disabled="isDisabled"
        >
            <icon name="random" v-if="!isLoading" />
            <loading v-if="isLoading" />
        </button>
    </div>
</template>

<style lang="scss" scoped>
.class-name {
    display: flex;

    margin-bottom: 8px;

    @include respond-to("medium") {
        margin-bottom: 16px;
    }

    input[type="text"] {
        flex-grow: 1;
    }

    .randomize {
        display: flex;
        align-items: center;
        justify-content: center;

        height: 42px;
        padding: 0 10px;
        border: 1px solid transparent;
        margin-left: 8px;

        outline: 0;
        cursor: pointer;

        line-height: 42px;

        transition: background map-get($animation-speeds, "fast") linear,
            color map-get($animation-speeds, "fast") linear;

        @include themed() {
            background: t("g-raven");
            color: t("t-light");
            border-color: t("b-darkbg");
        }

        &:hover:not([disabled]) {
            @include themed() {
                background: t("g-ebony");
                color: t("b-christine");
            }
        }

        &[disabled] {
            cursor: not-allowed;
        }
    }
}
</style>
