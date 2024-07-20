(function (BASE_URL) {
    const contextmenuelem = document.getElementById('contextmenu');
    const mainBody = document.getElementById('main-card-body');
    const defaultPadding = 16; // in px

    document.onclick = hideContextMenu;
    document.oncontextmenu = showContextMenu;

    const getOffsetParent = (event) => {
        return event.srcElement.offsetParent;
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

        console.log('Cursor X : ' + cursorPositionX);
        console.log('Body : '+ windowWidth);

        console.log(widthDiff);

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

        let elem = getOffsetParent(event);

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
                return;
            }
        }

        contextmenuelem.style.display = 'none';


        // console.log(event.srcElement.offsetParent);
    }

})(BASE_URL);

