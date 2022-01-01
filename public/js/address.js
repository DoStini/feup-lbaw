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

document.querySelectorAll(".edit-address-btn")
    .forEach((el) => el.addEventListener("click", () => {
        console.log(el.id.split("-")[2]);
        collapse.dispatchEvent(new Event("click"));
}));

$("#zip").on("select2:select", (e) => {
    const data = e.params.data;
    document.getElementById('county').value = data.county;
    document.getElementById('district').value = data.district;
});