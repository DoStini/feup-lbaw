function ensureLimits(value, min, max) {
    if (min === undefined) min = -Infinity;
    if (max === undefined) max = +Infinity;

    if (value !== NaN) {
        if (value < min)
            return min;
        if (value > max)
            return max;
    }

    return value
}

function ensureNumber(val) {
    if (!isNaN(val)) {
        return val;
    }
    return "";
}

function createNumberSelector(id, value, onChange, min, max) {
    const elem = 
    $(`
        <div id="selector-${id}" class="input-group input-group-sm number-selector">
            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-dash-lg"></i></button>
            <input id="${id}" type="text" class="form-control" value=${value || 0}>
            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-plus-lg"></i></button>
        </div>
    `);

    const update = (target, value) => {
        if (onChange) {
            onChange(target, value);
        } else {
            target.value = value;
        }
    }

    
    elem.find(">:first-child").on("click", (e) => {
        const input = document.getElementById(id);
        const newValue = ensureLimits(parseInt(input.value) - 1, min, max);
        console.log("asd", newValue, input.value, input.value)
        update(input, newValue);
    });
    elem.find(">:last-child").on("click", () => {
        const input = document.getElementById(id);
        const newValue = ensureLimits(parseInt(input.value) + 1, min, max);
        update(input, newValue);
    });
    elem.find(">input").on("keypress", (e) => {
        e.preventDefault();
        const value = ensureNumber(e.key);
        if (!value) return;
        const newValue = ensureLimits(parseInt(e.target.value + value), min, max);
        update(e.target, newValue);
    });

    return elem;
}
