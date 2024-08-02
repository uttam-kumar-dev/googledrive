(function (BASE_URL) {
    const contextmenuelem = document.getElementById('contextmenu');
    const mainBody = document.getElementById('main-card-body');
    const defaultPadding = 16; // in px

    document.onclick = hideContextMenu;
    document.oncontextmenu = showContextMenu;

    var current_selector = '';

    const getOffsetParent = (event, update_current_selector = true) => {

        if(!update_current_selector){
            return event.srcElement.offsetParent;
        }
        current_selector = event.srcElement.offsetParent;
        return current_selector;
    }

    const handleMenuServices = (elem) => {

        let attr = elem.getAttribute('data-isstarred');
        let fd = elem.getAttribute('data-fid');


        if(fd){
            contextmenuelem.querySelector('.download_document').lastElementChild.innerHTML = `<a target="blank" class="text-decoration-none" href="${BASE_URL}services/download.php?d=1&fd=${fd}">Download</a>`;
        }

        if(attr === null) return;

        if(attr == 0){
            contextmenuelem.querySelector('.context_starred').lastElementChild.innerText = 'Starred';
            return;
        }


        contextmenuelem.querySelector('.context_starred').lastElementChild.innerText = 'Unstarred';

    }

    const setContextMenuPosition = (event) => {
        let windowWidth = window.innerWidth;
        let windowHeight = window.innerHeight;

        let contextMenuWidth = contextmenuelem.clientWidth;
        let contextMenuHeight = contextmenuelem.clientHeight;

        let cursorPositionX = event.pageX;
        let cursorPositionY = event.pageY;

        let widthDiff = windowWidth - cursorPositionX;
        let heightDiff = windowHeight - cursorPositionY;

        if (widthDiff < 0) {
            contextmenuelem.style.left = `${(cursorPositionX + defaultPadding) - contextMenuWidth}px`;
        }

        else if (widthDiff < contextMenuWidth) {
            contextmenuelem.style.left = `${(cursorPositionX - contextMenuWidth - defaultPadding) + widthDiff}px`;
        }

        if (heightDiff < contextMenuHeight) {
            contextmenuelem.style.top = `${(window.innerHeight - contextMenuHeight)}px`;
        }

    }

    function hideContextMenu(event) {

        let elem = getOffsetParent(event, false);

        if (!elem) {
            contextmenuelem.style.display = 'none';
            return;
        }

        if (elem.parentElement.id != 'contextmenu') {
            contextmenuelem.style.display = 'none';
        }

        // console.log(event);
    }

    function showContextMenu(event) {

        event.preventDefault();

        // console.log(event);
        let elem = getOffsetParent(event);
        if (elem) {

            if (elem.classList.contains('on-contextmenu')) {
                contextmenuelem.style.top = `${event.pageY}px`;
                contextmenuelem.style.left = `${event.pageX}px`;
                contextmenuelem.style.display = 'block';
                setContextMenuPosition(event);
                handleMenuServices(elem);
                return;
            }
        }

        contextmenuelem.style.display = 'none';


        // console.log(event.srcElement.offsetParent);
    }

    //menu functions

    //when click on Starred
    $('body').on('click','.context_starred', function(event){
        event.current = current_selector;
        window.updateStarred(event);

        console.log(event);
    });

    //when click on share
    $('body').on('click', '.share_document', function(event){
        event.current = current_selector;
        window.shareFiles(event);
    })

})(BASE_URL);

