<script>
/******************************************************************************
 * Component: FetchButton
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { computed } from "vue";
import Icon from "Components/Icon/Icon";
import Loading from "Components/Loading/Loading";
export default {
    name: "FetchButton",
    props: {
        area: {
            type: String,
            required: true,
        },
    },
    components: { Icon, Loading },
    setup(props) {
        const store = useStore();
        const fetchGameData = () => {
            store.dispatch(props.area + "/GET_GAME_DATA");
        };
        const fetching = computed(() => store.state[props.area].requesting);
        return {
            fetchGameData,
            fetching,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <button
        @click="fetchGameData"
        :disabled="fetching"
        :aria-disabled="fetching"
        :aria-expanded="fetching"
        :aria-label="
            fetching
                ? $t('common.header.fetchData.fetching')
                : $t('common.header.fetchData.fetch')
        "
    >
        <span v-show="fetching">
            <loading :size="20" />
            {{ $t("common.header.fetchData.fetching") }} ...
        </span>
        <span v-show="!fetching">
            <icon class="refresh-icon" name="sync" />
            {{ $t("common.header.fetchData.fetch") }}
        </span>
    </button>
</template>

<style lang="scss" scoped>
button {
    position: relative;

    height: 3.6rem;
    padding: 0.5rem 1rem;
    border: 1px solid palette("dark", "g-sunken");
    margin: 1.6rem 1.6rem 0 auto;

    background: rgba(palette("dark", "g-raven"), 0.6);
    color: palette("dark", "t-light");

    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear,
        color map-get($animation-speeds, "fast") linear;

    &:hover:not([disabled]),
    &:focus {
        background: palette("dark", "g-ebony");
        color: palette("dark", "b-viking");
        outline: 0;
    }

    &:active {
        background: palette("dark", "g-ebony");
        color: palette("dark", "g-white");
    }

    > span {
        display: flex;
        align-items: center;

        line-height: 1;

        .refresh-icon {
            margin-right: 1rem;

            color: palette("dark", "b-viking");
        }

        .spinner {
            margin-right: 1rem;
        }
    }

    &[disabled] {
        background: rgba(palette("dark", "g-raven"), 0.7);
        cursor: not-allowed;
    }
}
</style>
