function loadOptions(option_id, options){
    let select_option = document.getElementById(option_id);
    for (var key in options) {

      let option = options[key];
      let new_option = document.createElement("option");
      new_option.value = option.id;
      new_option.innerHTML = option.type_name;
      select_option.appendChild(new_option);
  }
}

function refreshDataTable(table_name = "#datatable"){
  let table = $(table_name).DataTable();
  table.ajax.reload(null, false);
}

function clearForm(form_name){
  $(form_name)[0].reset();
}

function showResponse(response){
  alert(response.message);
}

function formatNumber(number){
  return "₱ "+number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}