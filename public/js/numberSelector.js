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

function createNumberSelector({id, value,  min, max, onChange, onBlur}) {
    const elem = 
    $(`
        <div id="selector-${id}" class="input-group input-group-sm number-selector">
            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-dash-lg"></i></button>
            <input id="${id}" type="text" class="form-control" value=${value || 0}>
            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-plus-lg"></i></button>
        </div>
    `);

    let prevUpdate = value;
    let prevBlur = value;

    const update = (target, value) => {
        if (onChange) {
            onChange(target, value, prevUpdate);
        } else {
            target.value = value;
        }
    }


    const lessButton = elem.find(">:first-child");
    lessButton.on("click", (e) => {
        const input = document.getElementById(id);
        const newValue = ensureLimits(parseInt(input.value) - 1, min, max);
        update(input, newValue);
        const event = new Event('change');
        document.getElementById(id).dispatchEvent(event);
    });
    lessButton.on("mouseleave", () => lessButton.trigger("blur"))
    // lessButton.on("blur", () => {
    //     const input = document.getElementById(id);
    //     const newValue = ensureLimits(parseInt(input.value), min, max);
    //     onBlur && onBlur(input, newValue);
    // });

    const moreButton = elem.find(">:last-child");
    moreButton.on("click", () => {
        const input = document.getElementById(id);
        const newValue = ensureLimits(parseInt(input.value) + 1, min, max);
        update(input, newValue);
        const event = new Event('change');
        document.getElementById(id).dispatchEvent(event);
    });
    moreButton.on("mouseleave", () => moreButton.trigger("blur"))

    const input = elem.find(">input");

    input.on("keypress", (e) => {
        e.preventDefault();
        const value = ensureNumber(e.key);
        if (!value) return;
        const newValue = ensureLimits(parseInt(e.target.value + value), min, max);
        update(e.target, newValue);
        const event = new Event('change');
        document.getElementById(id).dispatchEvent(event);
    });
    elem.on("mouseleave", () => {
        elem.trigger("submit");
        input.trigger("blur");
    });
    elem.on("submit", () => {
        const input = document.getElementById(id);
        const newValue = ensureLimits(parseInt(input.value), min, max);
        onBlur && onBlur(input, newValue, prevBlur);
        prevBlur = newValue;
    });

    return elem;
}
