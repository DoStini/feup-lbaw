let action = {};

function createAddress(data) {
    const elem = document.createElement('div');
    elem.className = "accordion-item";
    elem.id=`address-root-${data.id}`

    const html = `
        <h2 class="accordion-header" id="address-heading-${data.id}">
            <button
                class="accordion-button collapsed"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#address-panel-collapse-${data.id}"
                aria-expanded="false"
                aria-controls="address-panel-collapse-${data.id}"
                id="address-button-${data.id}">
                ${data.zip_code}, ${data.street} ${data.door}
            </button>
        </h2>
        <div id="address-panel-collapse-${data.id}"
            data-bs-parent="#addresses-accordion"
            class="accordion-collapse collapse"
            aria-labelledby="address-heading-${data.id}"
        >
            <div class="accordion-body row container justify-content-between align-items-center">
                <div class="col-6 address-info">
                    ${data.street} ${data.door}<br>
                    ${data.zip_code}<br>
                    ${data.county}<br>
                    ${data.district}<br>
                </div>
                <div class="col-6 container">
                    <div class="row justify-content-end">
                        <i id="edit-address-${data.id}" class="bi bi-pencil-square col-1 fs-4 px-0 btn edit-address-btn"></i>
                        <i id="remove-address-${data.id}" class="bi bi-x-lg col-1 fs-4 px-0 btn remove-address-btn"></i>
                    </div>
                </div>
            </div>
        </div>`

    elem.innerHTML = html;

    elem.querySelector(".edit-address-btn").addEventListener("click", handleEditClick)
    elem.querySelector(".remove-address-btn").addEventListener("click", handleRemoveClick);
    return elem;
}

function modifyAddress(data) {
    const root = document.getElementById(`address-root-${data.id}`);
    root.querySelector("button").innerHTML = `${data.zip_code}, ${data.street} ${data.door}`;
    root.querySelector(".address-info").innerHTML = `
        ${data.street} ${data.door}<br>
        ${data.zip_code}<br>
        ${data.county}<br>
        ${data.district}<br>
    `;
}

function handleEdit(data) {
    jsonBodyPost(`/api/users/${userId}/private/address/${action.id}/edit`, data)
        .then(response => {
            modifyAddress(response.data);
            addresses[response.data.id] = {
                ...response.data,
                ...addresses
            }
            closeCollapse();
        })
        .catch(handleError);
}

function handleNew(data) {
    jsonBodyPost(`/api/users/${userId}/private/address/add`, data)
        .then(response => {
            document.getElementById("address-root").appendChild(createAddress(response.data));
            addresses[response.data.id] = {
                ...response.data,
                ...addresses
            }
            closeCollapse();
        })
        .catch(handleError);
}

function handleError(error) {
    if(error.response) {
        if(error.response.data) {
            reportData("There was an error managing an address", error.response.data["errors"], {
                'street' : 'Street',
                'zip_code_id' : 'Zip Code',
                'door' : 'Door',
            });
        }
    };
}

function newAction() {
    action = {
        action: handleNew,
    }
}

function editAction(id) {
    action = {
        action: handleEdit,
        id,
    }
}

function resetAction() {
    action = {}
}

function collapseOpen() {
    return collapse.getAttribute("aria-expanded") === "true"
}

function openCollapse() {
    if (collapseOpen()) {
        return;
    }
    collapse.dispatchEvent(new Event("click"));
}

function closeCollapse() {
    if (!collapseOpen()) {
        return;
    }
    collapse.dispatchEvent(new Event("click"));
}

function handleEditClick(e) {
    const el = e.target;

    const id = el.id.split("-")[2];

    if (collapseOpen()) {
        return;
    }

    form.querySelector("h4").innerText = "Edit address";

    const cachedData = addresses[id];

    form.dispatchEvent(new Event("reset"));

    document.getElementById("street-name").value = cachedData.street;
    document.getElementById("door").value = cachedData.door;
    document.getElementById("zip_code_id").value = cachedData.zip_code_id;

    const zip = document.getElementById("zip");
    zip.value = cachedData.zip_code;
    zip.dispatchEvent(new Event("update"));

    openCollapse();
    editAction(id);
}

function handleRemoveClick(e) {
    const el = e.target;

    const id = el.id.split("-")[2];

    if (collapseOpen()) {
        return;
    }

    deleteRequest(`/api/users/${userId}/private/address/${id}/remove`)
        .then(() => {
            document.getElementById(`address-root-${id}`).remove();
            delete addresses[id];
        })
        .catch();

}

function setupListeners() {
    const selectTarget = document.getElementById("select-target-zip");
    selectTarget.replaceWith(selectTarget, createSelect({
        id: "zip",
        name: "zip",
        label: "Choose your Zip Code",
        ajax: true,
        delay: 1000,
        url: '/api/address/zipcode',
        data: (value) => {
            const query = {
                code: value,
            }
            return query;
        },
        processResults: (data) => {
            data.forEach((el) => el.text = el.zip_code)
            console.log(data);
            return {
                results: data
            };
        },
        callback: (item) => {
            document.getElementById('county').value = item.county;
            document.getElementById('district').value = item.district;
            document.getElementById('zip_code_id').value = item.id || item.zip_code_id;
        }
    }));

    document.querySelectorAll(".edit-address-btn")
        .forEach((el) => el.addEventListener("click", handleEditClick));

    document.querySelectorAll(".remove-address-btn")
        .forEach((el) => el.addEventListener("click", handleRemoveClick));

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const data = Object.fromEntries((new FormData(e.target)).entries());

        if (!action) {
            return;
        }

        action.action(data);
    });

    form.addEventListener("reset", () => {
        document.getElementById("zip").dispatchEvent(new Event("reset"));
    });

    document.getElementById("close-window").addEventListener("click", () => {
        closeCollapse();
        resetAction();
    });

    document.getElementById("new-address").addEventListener("click", (e) => {
        e.preventDefault();

        if (collapseOpen()) {
            return;
        }

        form.querySelector("h4").innerText = "New address";
        form.dispatchEvent(new Event("reset"));

        openCollapse();
        newAction();
    });
}

const collapse = document.getElementById('address-form-collapse-trigger');
const form = document.getElementById("address-form");

if (collapse) {
    setupListeners();
}
