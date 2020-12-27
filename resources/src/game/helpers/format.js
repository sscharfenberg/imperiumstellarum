/******************************************************************************
 * formatting helpers
 *****************************************************************************/

/**
 * @function convert a latin number to roman number string
 * @param {Number} latin
 * @returns {string}
 */
export const convertLatinToRoman = (latin) => {
    let result = "";
    const decimal = [1000, 900, 500, 400, 100, 90, 50, 40, 10, 9, 5, 4, 1];
    const roman = [
        "M",
        "CM",
        "D",
        "CD",
        "C",
        "XC",
        "L",
        "XL",
        "X",
        "IX",
        "V",
        "IV",
        "I",
    ];
    latin = parseInt(latin, 10);
    if (latin === 0) return "N";
    for (let i = 0; i <= decimal.length; i++) {
        // looping over every element of our arrays
        while (latin % decimal[i] < latin) {
            // keep trying the same number until it won"t fit anymore
            result += roman[i];
            // add the matching roman number to our result string
            latin -= decimal[i];
            // remove the decimal value of the roman number from our number
        }
    }
    return result;
};
