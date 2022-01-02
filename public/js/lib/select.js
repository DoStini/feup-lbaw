function handle(data) {
    console.log(data)
}

function createSelect({
    id,
    parent,
    options,
    ajax = false,
    url,
    delay,
    data,
    processResults,
    errorHandler,
    selectClasses = "",
}) {
    const element = document.createElement('div');
    element.id = `root-${id}`;

    element.innerHTML = `
        <a class="form-select hidden-arrow" href="#" role="button" id="select-trigger-${id}" data-bs-toggle="dropdown" aria-expanded="false">
        <select id="${id}" style="visibility:collapse">
        </select>
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <form class="p-4">
                <input id="autocomplete-${id}" class="form-control" autocomplete="off"></input>
            </form>
            <div class="select-container">
            </div>
            <div class="dropdown-item">Helo</div>
        </div>
    `;

    let interval;

    const input = element.querySelector('input');
    const button = element.querySelector("a");
    var dropdown = new bootstrap.Dropdown(button);
    button.addEventListener("shown.bs.dropdown", () => {
        if (ajax) {
            interval = setInterval(() => {
                if (!inputModified(input)) return;

                const req = data(input.value);
                console.log(req);
                getQuery(url, req)
                    .then((resp) => {
                        if (resp.status === 200) {
                            handle(processResults(resp.data));
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

    return element;
};



