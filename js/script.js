window.onload = function() {
    var toast = document.getElementById('toast');
    if (toast) {
        toast.classList.add('show');
        setTimeout(function() {
            toast.classList.remove('show');
            toast.classList.add('fade');
            setTimeout(function() {
                toast.remove();
            }, 500); // Wait for fade out animation to complete
        }, 3000); // Display for 3 seconds
    }
};