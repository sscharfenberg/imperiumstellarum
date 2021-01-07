<script>
/******************************************************************************
 * PageComponent: NewFleetName
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "NewFleetName",
    components: { Icon },
    setup() {
        const store = useStore();
        const fleetName = computed({
            get: () => store.state.fleets.create.name,
            set: (value) => {
                store.commit("fleets/SET_CREATE_FLEET_NAME", value);
            },
        });
        return { fleetName };
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
