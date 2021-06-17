<script>
/******************************************************************************
 * Component: Pagination
 *****************************************************************************/
import { computed, ref } from "vue";
import VueSelect from "vue-next-select";
import Icon from "Components/Icon/Icon";
export default {
    name: "Pagination",
    props: {
        currentPage: Number,
        perPage: Number,
        numItems: Number,
        messageKey: String,
        perPageKey: String,
    },
    components: { VueSelect, Icon },
    emits: ["changepage", "changeperpage"],
    setup(props, { emit }) {
        const numPages = computed(() =>
            Math.ceil(props.numItems / props.perPage)
        );
        const model = ref(props.perPage);
        const perPageOptions = [5, 10, 20, 50];
        const changePage = (page) => {
            emit("changepage", page);
        };
        const messagesPerPage = ref(props.perPage);
        const changePerPage = (changedValue) => {
            emit("changeperpage", parseInt(changedValue, 10));
        };

        return {
            numPages,
            perPageOptions,
            changePage,
            changePerPage,
            messagesPerPage,
            model,
        };
    },
};
</script>

<template>
    <div class="pagination__wrapper">
        <aside class="pagination__meta">
            {{ $tc(messageKey, numItems, { num: numItems }) }}
            <vue-select
                class="perpage"
                style="width: 80px"
                v-model="model"
                :options="perPageOptions"
                :close-on-select="true"
                @selected="changePerPage"
            />
            {{ $t(perPageKey) }}
        </aside>
        <nav class="pagination" v-if="numPages > 1">
            <button
                :disabled="currentPage === 1"
                class="
                    pagination__link
                    pagination__link--icon
                    pagination__link--prev
                "
                @click="changePage(currentPage - 1)"
            >
                <icon name="expand_more" />
            </button>
            <button
                v-for="page in numPages"
                :key="page"
                class="pagination__link"
                :class="{ active: page === currentPage }"
                @click="changePage(page)"
            >
                {{ page }}
            </button>
            <button
                :disabled="currentPage === numPages"
                class="
                    pagination__link
                    pagination__link--icon
                    pagination__link--next
                "
                @click="changePage(currentPage + 1)"
            >
                <icon name="expand_more" />
            </button>
        </nav>
    </div>
</template>

<style lang="scss" scoped>
.pagination {
    display: flex;
    flex-wrap: wrap;

    padding: 4px;
    margin: 0;

    @include respond-to("medium") {
        padding: 8px;
    }

    &__wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;

        margin: 8px 0;

        @include respond-to("medium") {
            margin: 16px 0;
        }

        @include themed() {
            background-color: t("g-sunken");
        }
    }

    &__meta {
        display: flex;
        align-items: center;

        padding: 4px;

        @include respond-to("medium") {
            padding: 8px;
        }
    }

    &__link {
        height: 41px;
        padding: 4px 8px;
        border: 0;
        margin: 0 2px 0 0;

        outline: 0;
        cursor: pointer;

        @include respond-to("medium") {
            padding: 8px 16px;
            margin: 0 4px 0 0;
        }

        @include themed() {
            background-color: t("g-deep");
            color: t("t-light");
        }

        &:last-of-type {
            margin-right: 0;
        }

        &.active {
            @include themed() {
                background-color: t("g-abbey");
            }
        }

        &:disabled {
            opacity: 0.4;

            cursor: not-allowed;
        }

        &--icon {
            padding: 0 2px;

            @include respond-to("medium") {
                padding: 0 8px;
            }
        }

        &--prev .icon {
            transform: rotate(90deg);
        }

        &--next .icon {
            transform: rotate(-90deg);
        }
    }
}

.pagination__meta .vue-select {
    width: 65px;
    margin: 0 16px;
}
</style>
