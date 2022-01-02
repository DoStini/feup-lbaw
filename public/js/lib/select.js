function createSelect({
    id,
    parent,
    ajax: {
        url,
        delay,
        processResults
    },
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
                <input id="autocomplete-${id}" class="form-control"></input>
            </form>
            <div class="select-container">

            

            </div>
        </div>
    `;


    return element;
};



