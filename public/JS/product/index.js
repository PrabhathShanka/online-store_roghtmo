let currentScale = 1; // Initial scale for zoom

function loadMoreImages(productId) {
    fetch(`/product/images/${productId}`)
        .then((response) => response.json())
        .then((data) => {
            const carouselInner = document.getElementById("carouselInner");
            carouselInner.innerHTML = ""; // Clear any previous images

            data.images.forEach((image, index) => {
                const carouselItem = document.createElement("div");
                carouselItem.classList.add("carousel-item");
                if (index === 0) {
                    carouselItem.classList.add("active"); // Set the first image as active
                }

                const imgElement = document.createElement("img");
                imgElement.src = image.image_path;
                imgElement.classList.add("d-block", "w-100", "zoomable-image");
                imgElement.alt = "Product Image";

                // Append the image to the carousel item
                carouselItem.appendChild(imgElement);
                carouselInner.appendChild(carouselItem);
            });

            // Show the modal
            const imageModal = new bootstrap.Modal(
                document.getElementById("imageModal")
            );
            imageModal.show();

            // Reset scale when a new image is loaded
            currentScale = 1;
            document.querySelectorAll(".zoomable-image").forEach((image) => {
                image.style.transform = `scale(${currentScale})`;
            });
        })
        .catch((error) => console.error("Error loading images:", error));
}

// Zoom In function
document.getElementById("zoomIn").addEventListener("click", () => {
    currentScale += 0.1; // Increase the scale
    document.querySelectorAll(".zoomable-image").forEach((image) => {
        image.style.transform = `scale(${currentScale})`;
    });
});

// Zoom Out function
document.getElementById("zoomOut").addEventListener("click", () => {
    currentScale = Math.max(1, currentScale - 0.1); // Prevent scale from going below 1
    document.querySelectorAll(".zoomable-image").forEach((image) => {
        image.style.transform = `scale(${currentScale})`;
    });
});

setTimeout(function () {
    document.querySelectorAll(".alert").forEach((alert) => alert.remove());
}, 5000);

function showFullDescription(description) {
    document.getElementById("fullDescription").textContent = description;
    document.getElementById("descriptionModal").style.display = "flex";
}

document.querySelector(".close-btn").addEventListener("click", function () {
    document.getElementById("descriptionModal").style.display = "none";
});

//delete button

function confirmDelete(productId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
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
            document.getElementById(`deleteForm-${productId}`).submit();
            Swal.fire(
                "Deleted!",
                "Your imaginary file has been deleted.",
                "success"
            );
        } else if (result.isDismissed) {
            Swal.fire("Cancelled", "Your imaginary file is safe", "error");
        }
    });
}

setTimeout(function () {
    let alert = document.querySelector(".alert");
    if (alert) {
        alert.classList.remove("show");
        alert.classList.add("hide");
    }
}, 5000); // milliseconds
