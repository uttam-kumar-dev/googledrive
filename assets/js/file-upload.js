
const form = document.getElementById('file_upload_form');
const uploadButton = document.getElementById('upload_file_btn');
var successIcon =  `<svg class="ms-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #198754;" class="ms-1"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path><path d="M9.999 13.587 7.7 11.292l-1.412 1.416 3.713 3.705 6.706-6.706-1.414-1.414z"></path></svg>`;
var errorIcon =  `<svg class="ms-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #dc3545;"><path d="M9.172 16.242 12 13.414l2.828 2.828 1.414-1.414L13.414 12l2.828-2.828-1.414-1.414L12 10.586 9.172 7.758 7.758 9.172 10.586 12l-2.828 2.828z"></path><path d="M12 22c5.514 0 10-4.486 10-10S17.514 2 12 2 2 6.486 2 12s4.486 10 10 10zm0-18c4.411 0 8 3.589 8 8s-3.589 8-8 8-8-3.589-8-8 3.589-8 8-8z"></path></svg>`;

var file_count = 0;
var uploaded_files = 0;
form.addEventListener('submit', (event) => {
    event.preventDefault();

    uploadButton.disabled = true;

    uploaded_files = 0;
    file_count = 0;


    var files = document.getElementById('file_input').files;

    file_count = files.length;

    $('#file_upload_sidebar').removeClass('d-none');
    openAccordion();

    for (var i = 0; i < files.length; i++) {

        uploadFile(files[i], i);



    }

});

function uploadFile(file, index) {

    var formData = new FormData();
    formData.append('file', file);
    formData.append('path',CURRENT_FOLDER);

    var progressBarId = `id_file_${index}`;

    formData.append('file_id', progressBarId);

    var progressBar = $(`<div class="progress_container">
                                <label class="mb-1" for="${file.name}">${file.name}</label>
                                <div class="progress h-5px">
                                    <div class="progress-bar" id="${progressBarId}" role="progressbar" title="0% Completed" aria-label="${file.name}" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div><hr class="mb-1" />`);

    $('#file_progress_bar_container').append(progressBar);



    var xhr = new XMLHttpRequest();

    xhr.upload.addEventListener('progress', function (event) {
        if (event.lengthComputable) {
            var percent = Math.round((event.loaded / event.total) * 100);
            let element = document.getElementById(progressBarId);
            element.style.width = percent.toFixed(2) + '%';
            element.setAttribute('title', percent.toFixed(2) + '% Completed');
            element.setAttribute('aria-valuenow', percent.toFixed(2));
        }
    });

    xhr.addEventListener('load', function (event) {
   
        let res = JSON.parse(event.target.responseText);

        let labelText = $('#'+res.progress_bar).parent().parent().find('label');

        if(res.status == 'success'){
            labelText.html(labelText.text()+successIcon);
        }else{
            labelText.html(labelText.text()+`<span title="${res.msg}">${errorIcon}</span>`);
        }
        

        uploaded_files++;
        
        if(uploaded_files == file_count){
            $('#file_upload_header').find('.header-status').addClass('d-none');
            $('#file_upload_header').addClass('bg-success text-white')
            $('#file_upload_header').find('.completed').removeClass('d-none').addClass('d-flex');
            uploadButton.disabled = false;


        }

    });


    xhr.open('POST', BASE_URL+'services/file.php');
    xhr.send(formData);
}

function closeFileProgressBar(){
    $('#file_upload_sidebar').addClass('d-none');
    $('#file_upload_header').find('.header-status').removeClass('d-none');
    $('#file_upload_header').removeClass('bg-success text-white')
    $('#file_upload_header').find('.completed').removeClass('d-flex').addClass('d-none');
    $('#file_progress_bar_container').html('');
}

function openAccordion(){

    $('#accordian_files_upload').find('.accordion-item').find('#file_accordian').addClass('show');

}