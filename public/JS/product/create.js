function handleImagePreview(inputId, previewId, removeButtonId) {
    document
        .getElementById(inputId)
        .addEventListener("change", function (event) {
            const preview = document.getElementById(previewId);
            const removeButton = document.getElementById(removeButtonId);
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                    removeButton.style.display = "inline";
                };
                reader.readAsDataURL(file);
            }
        });

    document
        .getElementById(removeButtonId)
        .addEventListener("click", function () {
            const fileInput = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            fileInput.value = ""; // Clear the file input
            preview.style.display = "none"; // Hide the preview
            this.style.display = "none"; // Hide the remove button
        });
}

// Initialize for the main image
handleImagePreview("mainImage", "imagePreview", "removeImage");

// Multiple Images Preview
document
    .getElementById("additionalImages")
    .addEventListener("change", function (event) {
        const previewContainer = document.getElementById(
            "additionalImagePreviews"
        );
        previewContainer.innerHTML = ""; // Clear existing previews
        const files = Array.from(event.target.files);

        if (files.length > 5) {
            alert("You can only upload up to 5 images.");
            this.value = ""; // Clear the input
            return;
        }

        files.forEach((file, index) => {
            const reader = new FileReader();
            const previewWrapper = document.createElement("div");
            previewWrapper.style.position = "relative";

            const previewImage = document.createElement("img");
            previewImage.classList.add("image-preview");
            previewWrapper.appendChild(previewImage);

            const removeButton = document.createElement("span");
            removeButton.textContent = "Remove Image";
            removeButton.classList.add("remove-image-btn");
            removeButton.style.position = "absolute";
            removeButton.style.top = "5px";
            removeButton.style.right = "5px";
            previewWrapper.appendChild(removeButton);

            previewContainer.appendChild(previewWrapper);

            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewImage.style.display = "block";
                removeButton.style.display = "inline";
            };
            reader.readAsDataURL(file);

            // Event listener to remove image
            removeButton.addEventListener("click", function () {
                previewWrapper.remove();
                const fileList = Array.from(
                    document.getElementById("additionalImages").files
                );
                fileList.splice(index, 1);
                const dataTransfer = new DataTransfer();
                fileList.forEach((file) => dataTransfer.items.add(file));
                document.getElementById("additionalImages").files =
                    dataTransfer.files;
            });
        });
    });

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
