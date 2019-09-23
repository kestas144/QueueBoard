<script>

    function refreshPageWithDelayParameter(boardId) {
        if (window.location.href.indexOf("&delayId") > -1) {
             window.location.href = location.search.replace(/&delayId = [^&$]*/i, '&delayId=' + boardId);
        } else {
            window.location.href = location.href + "&delayId=" + boardId;
        }
    }

    function refreshPageWithCancelParameter(boardId) {
        if (window.location.href.indexOf("&cancelId") > -1) {
            window.location.href = location.search.replace(/&cancelId = [^&$]*/i, '&cancelId=' + boardId);
        } else
            window.location.href = location.href + "&cancelId=" + boardId;
    }

    function refreshPageWithCompletedParameter(boardId) {
        if (window.location.href.indexOf("&completedId") > -1) {
            window.location.href = location.search.replace(/&completedId = [^&$]*/i, '&completedId=' + boardId);
        } else {
            window.location.href = location.href + "&completedId=" + boardId;
        }
    }
</script>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
        crossorigin="anonymous"></script>


