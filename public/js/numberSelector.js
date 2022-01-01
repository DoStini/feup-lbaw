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
    const elem = document.createElement("div");
    elem.id = `selector-${id}`;
    elem.className = "input-group input-group-sm number-selector";
    elem.innerHTML = `
            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-dash-lg"></i></button>
            <input id="${id}" type="text" class="form-control" value=${value || 0}>
            <button class="btn btn-outline-secondary" type="button"><i class="bi bi-plus-lg"></i></button>
    `;

    let prevUpdate = value;
    let prevBlur = value;

    const update = (target, value) => {
        if (onChange) {
            onChange(target, value, prevUpdate);
        } else {
            target.value = value;
        }
    }


    const lessButton = elem.firstElementChild;
    lessButton.addEventListener("click", (e) => {
        const input = document.getElementById(id);
        const newValue = ensureLimits(parseInt(input.value) - 1, min, max);
        update(input, newValue);
        const event = new Event('change');
        document.getElementById(id).dispatchEvent(event);
    });
    lessButton.addEventListener("mouseleave", () => {
        lessButton.dispatchEvent(new Event("blur"))
    }) 

    const moreButton = elem.lastElementChild;
    moreButton.addEventListener("click", () => {
        const input = document.getElementById(id);
        const newValue = ensureLimits(parseInt(input.value) + 1, min, max);
        update(input, newValue);
        const event = new Event('change');
        document.getElementById(id).dispatchEvent(event);
    });
    moreButton.addEventListener("mouseleave", () => moreButton.dispatchEvent(new Event("blur")))

    const input = elem.querySelector("input");

    input.addEventListener("keypress", (e) => {
        e.preventDefault();
        const value = ensureNumber(e.key);
        if (!value) return;
        const newValue = ensureLimits(parseInt(e.target.value + value), min, max);
        update(e.target, newValue);
        const event = new Event('change');
        document.getElementById(id).dispatchEvent(event);
    });
    elem.addEventListener("mouseleave", () => {
        elem.dispatchEvent(new Event("submit"));
        input.dispatchEvent(new Event("blur"));
    });
    elem.addEventListener("submit", () => {
        const input = document.getElementById(id);
        const newValue = ensureLimits(parseInt(input.value), min, max);
        onBlur && onBlur(input, newValue, prevBlur);
        prevBlur = newValue;
    });

    return elem;
}
