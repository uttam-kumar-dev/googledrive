(function (BASE_URL, global) {

    const toast = (elem_id, msg='') => {
        const toast_dom = document.getElementById(elem_id)
        const toast = new bootstrap.Toast(toast_dom);

        $('#'+elem_id).find('.toast-body').html(msg);
        
        return toast;
    }

    const getNodeType = (event) => {
        return event.target.nodeName;
    }

    const handleStarredIcon = (event, response = null) => {
        let fid = event.target.getAttribute('data-fid');
        let type_of = event.target.getAttribute('data-type');
        var is_starred = event.target.classList.contains('bxs-star') ? 0 : 1;

        if(response){

            if(is_starred == 1){
                event.target.classList.remove('bx-star');
                event.target.classList.add('bxs-star');
            }else {
                event.target.classList.remove('bxs-star');
                event.target.classList.add('bx-star');
            }

        }

        return {

                fid:fid, 
                type_of:type_of,
                is_starred:is_starred
            };
    }

    const handleOtherNode = (event, response=null) => {

        let fid = event.current.getAttribute('data-fid');
        let type_of = event.current.getAttribute('data-type');
        var is_starred = event.current.getAttribute('data-isstarred') == 0 ? 1 : 0;

        if(response){

            if(is_starred == 1){
                event.current.setAttribute('data-isstarred', 1);
                event.currentTarget.lastElementChild.innerText = 'Unstarred';
            }else{
                event.current.setAttribute('data-isstarred', 0);
                event.currentTarget.lastElementChild.innerText = 'Starred';
            }

        }

        return {
            fid:fid, 
            type_of:type_of,
            is_starred:is_starred
        };
    }

    const updateStarred = (event) => {

        event.target.classList.add('pe-none');

        if(getNodeType(event) == 'I'){
            var {fid, type_of, is_starred} = handleStarredIcon(event);
        }else{
            var {fid, type_of, is_starred} = handleOtherNode(event);
        }


        let token = document.getElementById('csrf_input');

        $.ajax({
            url: BASE_URL + 'services/starred.php',
            type: 'POST',
            data: { fid: fid, type_of: type_of, csrf_input: token.value, is_starred: is_starred },
            success: function (response) {

                let obj = JSON.parse(response);

                if (obj.status == 'success') {

                    if (getNodeType(event) == 'I') handleStarredIcon(event, 1);
                    else handleOtherNode(event, 1);
                }

                event.target.classList.remove('pe-none');
                token.value = obj.token;

                toast('notification', obj.msg).show();

            }
        });

    }

    //service for mark folder to starred
    $('body').on('click', '.starred_document', updateStarred);

    $('body').on('click', '.card_folder',function() {

        if($(this).hasClass('selected')){
            $(this).removeClass('selected');
            return;
        }

        $(this).addClass('selected');

    });

    //register some function to global scope
    global.updateStarred = updateStarred;

})(BASE_URL, window);