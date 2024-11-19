document
    .getElementById("mainImage")
    .addEventListener("change", function (event) {
        const preview = document.getElementById("imagePreview");
        const removeButton = document.getElementById("removeImage");
        const currentImage = document.querySelector(".img-thumbnail");
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = "block";
                removeButton.style.display = "inline";

                // Hide the current image preview if a new image is selected
                if (currentImage) {
                    currentImage.style.display = "none";
                }
            };
            reader.readAsDataURL(file);
        }
    });

document.getElementById("removeImage").addEventListener("click", function () {
    const fileInput = document.getElementById("mainImage");
    const preview = document.getElementById("imagePreview");
    const currentImage = document.querySelector(".img-thumbnail");

    fileInput.value = ""; // Clear the file input
    preview.style.display = "none"; // Hide the preview
    this.style.display = "none"; // Hide the remove button

    // Show the original image again if it exists
    if (currentImage) {
        currentImage.style.display = "block";
    }
});

// Image Deletion
// Function to handle image deletion with SweetAlert confirmation
function confirmDelete(imageId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel please!",
        reverseButtons: true,
        preConfirm: () => {
            // Send AJAX request to delete the image
            return fetch(`/delete-image/${imageId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    imageId,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        Swal.fire(
                            "Deleted!",
                            "Your imaginary file has been deleted.",
                            "success"
                        ).then(() => {
                            // Reload the page after successful deletion
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            "Failed",
                            "Failed to delete the image.",
                            "error"
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    Swal.fire(
                        "Error",
                        "An error occurred while deleting the image.",
                        "error"
                    );
                });
        },
    });
}

//validation

document.querySelector("form").addEventListener("submit", function (event) {
    // Prevent form submission if validation fails
    let isValid = true;

    // Validate Product Name
    const name = document.getElementById("name").value;
    if (!name || name.length > 255) {
        alert("Product Name is required and must not exceed 255 characters.");
        isValid = false;
    }

    // Validate Description (Optional, but should be a string if provided)
    const description = document.getElementById("description").value;
    if (description && typeof description !== "string") {
        alert("Description must be a valid string.");
        isValid = false;
    }

    // Validate Price
    const price = document.getElementById("price").value;
    if (!price || isNaN(price)) {
        alert("Price is required and must be a number.");
        isValid = false;
    }

    // Validate Stock Quantity
    const stock = document.getElementById("stock").value;
    if (!stock || !Number.isInteger(Number(stock))) {
        alert("Stock Quantity is required and must be an integer.");
        isValid = false;
    }

    // Validate Category
    const category = document.getElementById("category_id").value;
    if (!category) {
        alert("Category is required.");
        isValid = false;
    }

    // Validate Main Image (Optional, but must be a valid image format if provided)
    const mainImage = document.getElementById("mainImage").files[0];
    if (
        mainImage &&
        !["image/jpeg", "image/png", "image/jpg"].includes(mainImage.type)
    ) {
        alert("Main image must be a file of type jpeg, png, or jpg.");
        isValid = false;
    }
    if (mainImage && mainImage.size > 10240 * 1024) {
        alert("Main image must be smaller than 10MB.");
        isValid = false;
    }

    // Validate Additional Images (Optional, but each must be a valid image format and limit to 5)
    const additionalImages = document.getElementById("additionalImages").files;
    if (additionalImages.length > 5) {
        alert("You can upload a maximum of 5 additional images.");
        isValid = false;
    }
    for (let i = 0; i < additionalImages.length; i++) {
        const file = additionalImages[i];
        if (!["image/jpeg", "image/png", "image/jpg"].includes(file.type)) {
            alert(
                "Each additional image must be a file of type jpeg, png, or jpg."
            );
            isValid = false;
            break;
        }
        if (file.size > 10240 * 1024) {
            alert("Each additional image must be smaller than 10MB.");
            isValid = false;
            break;
        }
    }

    // Prevent form submission if validation fails
    if (!isValid) event.preventDefault();
});
