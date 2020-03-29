function animateVotebars() {
    var items = document.querySelectorAll(".for");
    items.forEach(item => {
        //  for each item:
        //      - we get the parents width
        //      - get the ratio of each child div
        //      - and multiply that ratio by the width of the parent
        var parent    = item.parentElement;
        var forEl     = item;
        var againstEl = item.nextElementSibling;

        var numForVotes = parseInt(forEl.getAttribute("data-votes"));
        var numAgainstVotes = parseInt(againstEl.getAttribute("data-votes"));
        var sum = numForVotes + numAgainstVotes;
        var offset = 2;

        if (numForVotes == 0 && numAgainstVotes == 0) {
            forEl.style.width = (0.5 * parent.offsetWidth - offset).toString() + "px";
            againstEl.style.width = (0.5 * parent.offsetWidth - offset).toString() + "px";
        }
        else {
            var forElCalculatedWidth = (numForVotes / sum) * parent.offsetWidth;
            var againstElCalculatedWidth = (numAgainstVotes / sum) * parent.offsetWidth;

            if (forElCalculatedWidth > 0)
                forEl.style.width = (forElCalculatedWidth.toString() - offset) + "px";
            else
                forEl.style.display = 'none';

            if (againstElCalculatedWidth > 0)
                againstEl.style.width = (againstElCalculatedWidth.toString() - offset) + "px";
            else
            {
                againstEl.style.display = 'none';
                console.log(againstElCalculatedWidth);
            }
        }
    })
}