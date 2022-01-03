let errorModal = new bootstrap.Modal(document.getElementById('errorMessage'));

function reportData(message, data, attributes) {
    document.getElementById("errorMessageTitle").innerText = message;

    let result = document.createElement('dl');
    result.className = "row";

    for(let key in data) {
        let listTitle = document.createElement('dt');
        listTitle.className="col-sm-3";
        listTitle.innerText = attributes ? attributes[key] || key : key;

        let listData = document.createElement('dd');
        listData.className = "col-sm-9";

        let listDataText = "";
        let listDataObj = data[key];
        if(typeof listDataObj === 'object' && listDataObj !== null) {
            for(let dataKey in listDataObj) {
                listDataText = listDataText.concat(listDataObj[dataKey],'<br>');
            }
        } else {
            listDataText = listDataObj;
        }

        listData.innerHTML = listDataText;

        result.appendChild(listTitle);
        result.appendChild(listData);
    }

    document.getElementById("errorMessageBody").innerHTML = "";
    document.getElementById("errorMessageBody").appendChild(result);
    errorModal.show();
}
