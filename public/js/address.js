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
              var query = {
                code: params.term,
              }
        
              // Query parameters will be ?search=[term]&type=public
              return query;
            },
            processResults: function (data) {
                data.forEach((el) => el.text = el.zip_code)
                console.log(data)
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                  results: data
                };
              }
          }
    });
}));


const collapse = document.getElementById('address-form-collapse-trigger');

let action = {};

function handleEdit(data) {

}

function insertAddress(data) {
    const elem = document.createElement('div');
    elem.className = "accordion-item";

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
                <div class="col-6">
                    ${data.street} ${data.door}<br>
                    ${data.zip_code}<br>
                    ${data.county}<br>
                    ${data.district}<br>
                </div>
                <i id="edit-address-${data.id}" class="bi bi-pencil-square col-1 fs-4 px-0 btn edit-address-btn"></i>
            </div>
        </div>`
    
    elem.innerHTML = html;

    document.getElementById("address-root").appendChild(elem);

}

function handleNew(data) {
    console.log("hello", userId, data)
    jsonBodyPost(`/api/users/${userId}/private/address/add`, data)
        .then(response => {
            insertAddress(response.data)
        })
        .catch(handleError);
}

function handleError(error) {
    if(error.response) {
        if(error.response.data) {
            reportData("There was an managing an address", error.response.data["errors"], {
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

function handleEdit(data) {

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

document.querySelectorAll(".edit-address-btn")
    .forEach((el) => el.addEventListener("click", () => {
        const id = el.id.split("-")[2];

        if (collapseOpen()) {
            return;
        }

        form.querySelector("h4").innerText = "Edit address";
    
        openCollapse();
        editAction(id);
}));

const form = document.getElementById("address-form");

form.addEventListener("submit", (e) => {
    e.preventDefault();
    const data = Object.fromEntries((new FormData(e.target)).entries());

    if (!action) {
        return;
    }

    action.action(data);
});

document.getElementById("close-window").addEventListener("click", () => {
    closeCollapse();
    resetAction();
})

$("#zip").on("select2:select", (e) => {
    const data = e.params.data;
    document.getElementById('county').value = data.county;
    document.getElementById('district').value = data.district;
    document.getElementById('zip_code_id').value = data.id;
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
