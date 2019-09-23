<script>

    function refreshPageWithDelayParameter(boardId) {
        if (window . location . href . indexOf("&delayId") > -1) {
            window . location . href = location . search . replace(/&delayId = [^&$]*/i, '&delayId=' + boardId);
        } else {
            window . location . href = location . href + "&delayId=" + boardId;
        }
    }

    function refreshPageWithCancelParameter(boardId) {
        if (window . location . href . indexOf("&cancelId") > -1) {
            window . location . href = location . search . replace(/&cancelId = [^&$]*/i, '&cancelId=' + boardId);
        } else {
            window . location . href = location . href + "&cancelId=" + boardId;
        }
    }


    function refreshPageWithCompletedParameter(boardId) {
        if (window . location . href . indexOf("&completeId") > -1) {
            window . location . href = location . search . replace(/&cancelId = [^&$]*/i, '&completeId=' + boardId);
        } else {
            window . location . href = location . href + "&cancelId=" + boardId;
        }
    }

</script>