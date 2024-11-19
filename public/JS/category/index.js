setTimeout(function () {
    let alert = document.querySelector(".alert");
    if (alert) {
        alert.classList.remove("show");
        alert.classList.add("hide");
    }
}, 2000); // milliseconds

//  delete button

function confirmDelete(categoryId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this category!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel please!",
        reverseButtons: true,
        closeOnConfirm: false,
        closeOnCancel: false,
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, submit the form
            document.getElementById(`deleteForm-${categoryId}`).submit();
            Swal.fire("Deleted!", "The category has been deleted.", "success");
        } else if (result.isDismissed) {
            Swal.fire("Cancelled", "Your category is safe :)", "error");
        }
    });
}
