$((function() {
    $('.address-select').select2({
        dropdownParent: $('#address-form-collapse'),
        theme: "bootstrap-5",
        "language": {
            "noResults": function(){
                return "Search for a zip code";
            }
        },
        ajax: {
            url: 'http://localhost:8000/api/address/zipcode',
            delay: 500,
            data: function (params) {
              const query = {
                code: params.term,
              }
        
              // Query parameters will be ?search=[term]&type=public
              return query;
            },
            processResults: function (data) {
                data.forEach((el) => el.text = el.zip_code)
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                  results: data
                };
              }
          }
    });
}));



console.log(addresses)
const collapse = document.getElementById('address-form-collapse-trigger');

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
    console.log("hello", userId, data)
    jsonBodyPost(`/api/users/${userId}/private/address/add`, data)
        .then(response => {
            console.log("asd")
            document.getElementById("address-root").appendChild(createAddress(response.data));
            addresses[response.data.id] = {
                ...response.data,
                ...addresses
            }
            console.log(addresses);
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
    console.log(cachedData)

    form.dispatchEvent(new Event("reset"));

    document.getElementById("street-name").value = cachedData.street;
    document.getElementById("door").value = cachedData.door;

    $('#zip').trigger({
        type: 'select2:select',
        params: {
            data: cachedData
        }
    });
    document.querySelector("span.select2-selection__arrow").innerText = cachedData.zip_code;

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


document.querySelectorAll(".edit-address-btn")
    .forEach((el) => el.addEventListener("click", handleEditClick));

document.querySelectorAll(".remove-address-btn")
    .forEach((el) => el.addEventListener("click", handleRemoveClick));

const form = document.getElementById("address-form");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    const data = Object.fromEntries((new FormData(e.target)).entries());

    if (!action) {
        return;
    }

    action.action(data);
});

form.addEventListener("reset", (e) => {
    const zip = $('#zip');
    zip.trigger({
        type: 'select2:select',
        params: {
            data: {}
        }
    });
    zip.html("");
});

document.getElementById("close-window").addEventListener("click", () => {
    closeCollapse();
    resetAction();
})

$("#zip").on("select2:select", (e) => {
    const data = e.params.data;
    document.querySelector("span.select2-selection__arrow").innerText = "";
    document.getElementById('county').value = data.county;
    document.getElementById('district').value = data.district;
    document.getElementById('zip_code_id').value = data.id || data.zip_code_id;
});

document.getElementById("new-address").addEventListener("click", (e) => {
    e.preventDefault();

    if (collapseOpen()) {
        return;
    }

    form.querySelector("h4").innerText = "New address";
    form.dispatchEvent(new Event("reset"));
    document.querySelector("span.select2-selection__arrow").innerText = "";

    openCollapse();
    newAction();    
});
