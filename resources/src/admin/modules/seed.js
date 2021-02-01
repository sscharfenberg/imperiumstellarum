/******************************************************************************
 *
 * admin component: generate random locations for stars
 *
 *****************************************************************************/
import { getDistance } from "./slider";
import PoissonDiskSampling from "poisson-disk-sampling";

/**
 * @function filter stars again by removing direct neighbours of player systems
 * @param {Array} stars
 * @param {Number} dimensions
 * @returns {*}
 */
const removeDirectPlayerNeighbours = (stars, dimensions) => {
    const NEIGHBOURS = [-1, -1, 0, -1, 1, -1, -1, 0, 1, 0, -1, 1, 0, 1, 1, 1];
    const playerStars = stars.filter((star) => star.type === 2);
    let npcStars = stars.filter((star) => star.type === 1);
    console.log(
        `checking ${playerStars.length} player stars, ${npcStars.length} npc stars.`
    );
    const npcStarsToRemove = [];
    npcStars.forEach((star) => {
        let i = 0;
        while (i < 16) {
            let lx = star.x + NEIGHBOURS[i++]; // get the x offset
            let ly = star.y + NEIGHBOURS[i++]; // get the y offset
            // ensure you are inside the grid
            if (ly >= 0 && ly < dimensions && lx >= 0 && lx < dimensions) {
                //console.log(`checking neighbour x=${lx}, y=${ly}`);
                const adjactentHomeStar = playerStars.find(
                    (s) => s.x === lx && s.y === ly
                );
                if (adjactentHomeStar) {
                    npcStarsToRemove.push(star);
                }
            }
        }
    });
    npcStars = npcStars.filter((star) => {
        return !npcStarsToRemove.includes(star);
    });

    console.log(
        `eliminated ${
            stars.length - npcStars.length + playerStars.length
        } stars directly adjacent to player home systems.`
    );

    return [...playerStars, ...npcStars];
};

/**
 * @function filter stars - remove duplicates and stars that are outside of map dimensions
 * @param {Array} stars
 * @param {Number} dimensions
 * @returns {*}
 */
const filterStars = (stars, dimensions) => {
    const seen = [];
    return stars.filter((star) => {
        // convert the coords array to a string, since javascript arrays (objects) are compared by id, not value
        // therefore, we can not simply compare arrays by using indexOf
        let coordSeen = `${star[0]}/${star[1]}`;
        if (
            star[0] >= dimensions ||
            star[1] >= dimensions ||
            seen.indexOf(coordSeen) > -1
        )
            return false;
        seen.push(coordSeen);
        return true;
    });
};

/**
 * normalize stars
 * round to integer coordinates and remove duplicates.
 * @param {array} points
 * @param {number} dimensions
 * @returns {array} points converted to integer and duplicates removed
 */
const normalizeStars = (points, dimensions) => {
    let intPoints = points.map((point) => [
        // we need integer coordinates
        Math.round(point[0]),
        Math.round(point[1]),
    ]);
    const filteredPoints = filterStars(intPoints, dimensions);
    console.log(
        `normmalized all ${points.length} stars, filtered ${
            points.length - filteredPoints.length
        }.`
    );
    return filteredPoints;
};

/**
 * @function create normalized map
 * @param {number} distMin - minimum star distance
 * @param {number} distMax - maximum star distance
 * @param {number} dimensions
 * @returns {array} filteredPoints - array of arrays with x/y coordinates
 *
 */
const systems = (distMin, distMax, dimensions) => {
    // limit tries for bigger dimensions - very large datasets result in perf problems
    const tries = 250 - dimensions;
    const pds = new PoissonDiskSampling({
        shape: [dimensions, dimensions], // for now, we only support equal width/height maps
        minDistance: distMin,
        maxDistance: distMax,
        tries,
    });
    const points = pds.fill();
    console.log(`generated ${points.length} stars.`);
    return normalizeStars(points, dimensions);
};

/**
 * @function start seeding
 * @param {array} nDist - distance of NPC systems
 * @param {array} pDist - distance of Player systems
 * @param {number} dimensions
 */
const seed = (nDist, pDist, dimensions) => {
    const _input = document.getElementById("map");
    const _form = _input.closest("form");
    let npcStars = systems(nDist[0], nDist[1], dimensions);
    let playerStars = systems(pDist[0], pDist[1], dimensions);
    npcStars = npcStars.map((star) => [star[0], star[1], 1]); // set to npc system by default.
    playerStars = playerStars.map((star) => [star[0], star[1], 2]); // set to player system.
    let allStars = playerStars
        .concat(npcStars)
        .sort((a, b) => (a[0] === b[0] ? 0 : a[0] < b[0] ? -1 : 1));
    console.log(
        `generated ${allStars.length} stars total - ` +
            `${npcStars.length} npc systems, ${playerStars.length} player home systems.`
    );
    let filteredStars = filterStars(allStars, dimensions).map((star) => {
        return { x: star[0], y: star[1], type: star[2] };
    });
    console.log(
        `${allStars.length - filteredStars.length} duplicates removed, ${
            filteredStars.length
        } remaining.`
    );
    filteredStars = removeDirectPlayerNeighbours(filteredStars, dimensions);
    _input.value = JSON.stringify(filteredStars);
    _form.submit();
};

/**
 * @function event handler for click on submit
 * @param {MouseEvent} ev
 */
const onSubmit = (ev) => {
    ev.preventDefault();
    const _btn = document.querySelector("[data-seed-map]");
    const _loading = _btn.querySelector(".spinner");
    _loading.style.display = "block";
    _btn.disabled = true;
    const _sliderNpcs = document.getElementById("npcDistanceSlider");
    const _sliderPlayers = document.getElementById("playerDistanceSlider");
    const npcDistances = getDistance(_sliderNpcs);
    const playerDistances = getDistance(_sliderPlayers);
    const dimensions = parseInt(
        document.getElementById("dimensions").value,
        10
    );
    seed(npcDistances, playerDistances, dimensions);
};

/**
 * @function setup
 */
export const initSeed = () => {
    console.log("initializing map seeding");
    document
        .querySelector("[data-seed-map]")
        .addEventListener("click", onSubmit);
};
