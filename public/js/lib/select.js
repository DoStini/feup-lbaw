function createSelect({
    id,
    parent,
    options,
    name,
    ajax = false,
    url,
    delay,
    data,
    processResults,
    errorHandler,
    callback,
    label,
    selectClasses = "",
}) {
    const root = document.createElement('div');
    root.id = `root-${id}`;
    root.className = "autocomplete-select"

    root.innerHTML = `
        <input name="${name}" id="${id}" style="visibility:collapse;position:fixed">
        </input>
        <a class="form-select hidden-arrow" href="#" role="button" id="select-trigger-${id}" data-bs-toggle="dropdown" aria-expanded="false">
            <span>&#8203;</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <div class="px-4 pb-4 pt-2">
                ${label ? `<label class="form-label" for="autocomplete-${id}">${label}</label>` : ""}
                <input id="autocomplete-${id}" class="form-control" autocomplete="off"></input>
        </div>
            <div class="select-container">
            </div>
        </div>
    `;

    let interval;

    const input = root.querySelector(`#autocomplete-${id}`);
    const button = root.querySelector("a");
    const text = button.querySelector("span");
    const container = root.querySelector(".select-container");
    const select = root.querySelector(`#${id}`);

    select.addEventListener("update", () => {
        text.innerHTML = select.value || "\220"
    });

    select.addEventListener("reset", () => {
        select.value = "";
        text.innerText = "\220"
    });

    var dropdown = new bootstrap.Dropdown(button);
    button.addEventListener("shown.bs.dropdown", () => {
        if (ajax) {
            interval = setInterval(() => {
                if (!inputModified(input)) return;

                const req = data(input.value);
                getQuery(url, req)
                    .then((resp) => {
                        if (resp.status === 200) {
                            handleData(processResults(resp.data));
                        }
                    })
                    .catch((err) => errorHandler && errorHandler(err));
            }, delay);
        }
    });
    button.addEventListener("hide.bs.dropdown", () => {
        if (ajax) {
            clearInterval(interval);
        }
    });

    function handleData(data) {
        container.innerHTML = "";
        data.results.forEach(item => {
            const elem = document.createElement("div");
            elem.className = "dropdown-item";
            elem.id = `select-item-${item.id}`;
            elem.innerHTML = item.text;
            elem.addEventListener("click", () => {
                select.value = item.text;
                select.dispatchEvent(new Event("update"));
                callback && callback(item);
            });
            container.appendChild(elem);
        });
    }

    return root;
};



