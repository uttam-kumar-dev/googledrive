(function (BASE_URL) {

    const toast = (elem_id, msg='') => {
        const toast_dom = document.getElementById(elem_id)
        const toast = new bootstrap.Toast(toast_dom);

        $('#'+elem_id).find('.toast-body').html(msg);
        
        return toast;
    }

    const updateStarred = (event) => {

        event.target.classList.add('pe-none');

        let fid = event.target.getAttribute('data-fid');
        let type_of = event.target.getAttribute('data-type');
        let token = document.getElementById('csrf_input');

        var is_starred = event.target.classList.contains('bxs-star') ? 0 : 1;
        $.ajax({
            url: BASE_URL + 'services/starred.php',
            type: 'POST',
            data: { fid: fid, type_of: type_of, csrf_input: token.value, is_starred: is_starred },
            success: function (response) {

                let obj = JSON.parse(response);

                if (obj.status == 'success') {

                    if (is_starred == 1) {
                        event.target.classList.remove('bx-star');
                        event.target.classList.add('bxs-star');
                    } else {
                        event.target.classList.remove('bxs-star');
                        event.target.classList.add('bx-star');
                    }
                }

                event.target.classList.remove('pe-none');
                token.value = obj.token;

                toast('notification', obj.msg).show();

            }
        });

    }

    //service for mark folder to starred
    $('body').on('click', '.starred_document', updateStarred);

})(BASE_URL);