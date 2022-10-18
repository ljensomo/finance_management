function getError(error_response){
    if(error_response.response){
        let response = error_response.response;
        let errors = response.data.errors;
        let error_list = [];

        for (var error_subject in errors){
            for(var error in errors[error_subject]){
                error_list.push(errors[error_subject][error]);
            }
        }    
        return error_list;
    }
        
    return error_response;
}

function showError(error_response){
    let error = getError(error_response);
    alert(error);
  }
  
function showErrorModal(modal, error){

}