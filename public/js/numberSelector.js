function createNumberSelector(id, parent) {

    const elem = document.createElement("div");
    elem.id = `number-selector-${id}`;
    elem.innerHtml = `
        <div class="input-group">
            <div class="input-group-prepend">
                <i id="number-selector-addon-${id}" class="input-group-text bi bi-dash-lg"></i>
            </div>
            </div>
            <input id="${id}" type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="number-selector-addon-${id}">
            <div class="input-group-prepend">
                <i id="number-selector-addon-${id}" class="input-group-text bi bi-dash-lg"></i>
            </div>
        </div>
    `;

    parent.append(elem);

}
