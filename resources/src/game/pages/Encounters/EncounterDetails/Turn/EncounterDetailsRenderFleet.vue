<script>
/******************************************************************************
 * Page: EncounterDetailsRenderFleet
 *****************************************************************************/
import { ref, onMounted } from "vue";
export default {
    name: "EncounterDetailsRenderFleet",
    props: {
        fleetId: String,
        row: Number,
        col: Number,
        name: String,
        ships: Object,
    },
    setup(props) {
        const fleetRow = ref(null);
        const rowWidth = ref(0);
        const columnWidth = ref([]);
        const columnMargin = () => {
            const col = props.col;
            let margin = 0;
            for (let i = 0; i < col; i++) {
                margin += columnWidth.value[i];
            }
            return margin + "px";
        };

        /**
         * @function when component is mounted, calculate widths / margins
         */
        onMounted(() => {
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
        });
        return { fleetRow, columnWidth, columnMargin };
    },
};
</script>

<template>
    <li
        ref="fleetRow"
        :title="name"
        :style="{ '--fleet-margin': columnMargin() }"
    >
        <span :style="{ width: columnWidth[col] + 'px' }">
            {{ name }}
            {{ ships }}
        </span>
    </li>
</template>

<style lang="scss" scoped>
li {
    display: flex;
    align-items: center;
}
span {
    display: block;

    margin-left: var(--fleet-margin);

    transition: margin-left map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: t("g-deep");
    }
}
</style>
