<script>
/******************************************************************************
 * PageComponent: DraggableMap
 *****************************************************************************/
import interact from "interactjs";
import { ref, onMounted } from "vue";
export default {
    name: "DraggableMap",
    props: {
        dimensions: Number,
        tileSize: Number,
        cameraX: Number,
        cameraY: Number,
    },
    setup() {
        const stageSize = () =>
            document.getElementById("mapWrapper").offsetWidth;
        const mapStyles = ref("");
        const dragMoveListener = (event) => {
            const target = event.target;
            // keep the dragged position in the data-x/data-y attributes
            const x =
                (parseFloat(target.getAttribute("data-x")) || 0) + event.dx;
            const y =
                (parseFloat(target.getAttribute("data-y")) || 0) + event.dy;

            // translate the element
            target.style.webkitTransform = target.style.transform =
                "translate3d(" + x + "px, " + y + "px, 0)";

            // update the posiion attributes
            target.setAttribute("data-x", x);
            target.setAttribute("data-y", y);
        };
        onMounted(() => {
            mapStyles.value = `width: ${stageSize() * 2}px; height: ${
                stageSize() * 2
            }px`;
            // https://interactjs.io/docs/
            interact("#draggableMap").draggable({
                inertia: true,
                listeners: {
                    move: dragMoveListener,
                },
            });
        });
        return {
            mapStyles,
        };
    },
};
</script>

<template>
    <div
        class="map__actual"
        :style="mapStyles"
        draggable="true"
        id="draggableMap"
    >
        Map map map
        <div>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga
            placeat quas quos. At atque autem, consequatur culpa cum, delectus
            dolor dolore dolores dolorum ducimus ea exercitationem expedita
            harum illo iste labore laboriosam magni maiores minus nihil
            obcaecati, perspiciatis quia repellat rerum sint velit veniam
            voluptatem? A adipisci alias atque commodi consequuntur corporis
            culpa deleniti deserunt dolor dolores error esse eum excepturi
            exercitationem explicabo impedit inventore iusto magni modi mollitia
            necessitatibus nihil numquam officiis possimus quam, quia quidem
            quod quos recusandae repellendus sed sunt tempore temporibus unde
            veniam veritatis voluptate. A amet fugit maxime natus odit
            perferendis possimus qui quo ratione similique. Amet architecto
            corporis cum delectus, eligendi eos eum, incidunt iste labore
            laudantium libero minus nobis, perferendis quisquam repellat sunt
            totam unde vitae voluptate voluptatibus. Aperiam, blanditiis dolor,
            eaque explicabo facilis incidunt laudantium maxime molestias non
            nostrum tempora, ullam! Accusantium alias at consectetur
            consequuntur cumque eligendi eos error eum fugiat illo laboriosam
            nemo nesciunt nihil nostrum numquam placeat praesentium provident,
            quo, quod ratione repellat sapiente vero. Accusamus ad adipisci
            aliquam aliquid assumenda at beatae deserunt dicta ducimus eligendi
            esse et expedita facilis id labore libero modi molestiae neque nisi
            nostrum, obcaecati odit praesentium, quasi quia quidem quis quos
            sunt ut!
        </div>
        <div>
            Commodi cupiditate eaque facere natus nobis possimus praesentium
            voluptatibus! Accusantium alias amet animi beatae blanditiis
            consequuntur corporis cupiditate deleniti ea eaque error esse
            exercitationem fugit harum, ipsam ipsum laborum maiores
            necessitatibus nihil, nisi nostrum numquam omnis optio perspiciatis
            praesentium quas quidem rem repellat repudiandae sapiente sit
            suscipit veritatis voluptas? Architecto blanditiis culpa doloremque
            eligendi esse eveniet, molestias mollitia natus, officia pariatur
            provident rem, rerum sit? Ab alias aut, consectetur distinctio iure
            magnam minima officiis optio quaerat quo sapiente totam ut vitae
            voluptatem voluptatibus. Accusamus aliquam culpa cum incidunt magni
            minima modi necessitatibus, nulla omnis quas quia ratione reiciendis
            reprehenderit sequi sint voluptas voluptatem. Accusamus alias
            aperiam assumenda at aut blanditiis, cum cupiditate delectus
            deserunt dolore dolores doloribus earum eligendi esse expedita
            facilis fugiat hic illo illum ipsa iste laboriosam laudantium maxime
            minus non numquam omnis placeat quibusdam quisquam similique sint
            soluta sunt suscipit tempore ullam ut vel! A deleniti ducimus quod
            recusandae vel! Aut dicta, enim error explicabo modi numquam
            officiis sit. Accusantium aliquid, blanditiis esse fugiat hic illo
            illum nisi optio provident temporibus! At corporis delectus deleniti
            dignissimos dolor dolorum explicabo ipsa minus, modi molestias nam
            neque nihil omnis praesentium quod reiciendis repellat repudiandae
            temporibus velit veniam vero vitae?
        </div>
    </div>
</template>

<style lang="scss" scoped>
.map__actual {
    z-index: -1;

    transform: translate3d(-50px, 0, 0);

    cursor: grab;
    touch-action: none;

    @include themed() {
        background: t("g-black");
    }
}
</style>
