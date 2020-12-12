<script>
/******************************************************************************
 * PageComponent: StarName
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import GameButton from "Components/Button/GameButton";
export default {
    name: "StarName",
    props: {
        starId: String,
    },
    components: { GameButton },
    setup(props) {
        const store = useStore();
        const star = computed(() =>
            store.getters["empire/starById"](props.starId)
        );
        const name = computed(() => star.value.name);
        const isExpanded = computed(() =>
            store.getters["empire/isStarExpanded"](props.starId)
        );
        const onExpandClick = () => {
            store.commit("empire/TOGGLE_STAR_EXPANDED", {
                id: props.starId,
                expand: !store.getters["empire/isStarExpanded"](props.starId),
            });
        };
        const onEditClick = () => {
            store.commit("empire/SET_EDITING_STAR", props.starId);
        };
        return {
            name,
            isExpanded,
            onExpandClick,
            onEditClick,
        };
    },
};
</script>

<template>
    <div class="star-name">
        <game-button
            v-if="!isExpanded"
            icon-name="expand_more"
            @click="onExpandClick"
            class="expand"
        />
        <game-button
            v-if="isExpanded"
            icon-name="expand_less"
            @click="onExpandClick"
            class="expand"
        />
        <span class="name">{{ name }}</span>
        <game-button icon-name="edit" class="edit" @click="onEditClick" />
    </div>
</template>

<style lang="scss" scoped>
.star-name {
    display: flex;
    align-items: center;

    overflow: hidden;
    flex: 1 1 auto;
}

.expand {
    margin-right: 4px;

    @include respond-to("medium") {
        margin-right: 16px;
    }
}

.edit {
    margin-left: auto;
}

.name {
    overflow: hidden;
    height: 48px;

    font-size: 24px;
    line-height: 48px;
    white-space: nowrap;
    text-overflow: ellipsis;

    @include themed() {
        color: t("t-text");
    }
}
</style>
