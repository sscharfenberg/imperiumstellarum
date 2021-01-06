<script>
/******************************************************************************
 * PageComponent: NewFleetName
 *****************************************************************************/
import { ref } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "NewFleetName",
    components: { Icon },
    emits: ["changed"],
    setup(props, { emit }) {
        const handleChange = () => {
            emit("changed", fleetName.value);
        };
        const fleetName = ref("");
        return { handleChange, fleetName };
    },
};
</script>

<template>
    <div class="form-row">
        <div class="label">
            <label for="fleetName">{{ $t("fleets.new.nameLabel") }}</label>
        </div>
        <div class="input">
            <input
                type="text"
                class="form-control"
                id="fleetName"
                @input="handleChange"
                :placeholder="$t('fleets.new.namePlaceholder')"
                v-model="fleetName"
            />
            <div class="addon"><icon name="fleets" /></div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.form-row {
    padding: 0 0 16px 0;
}
</style>
