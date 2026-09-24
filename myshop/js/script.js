const buttons = document.querySelectorAll(".buy-button");

const detail = document.querySelector("#product-detail");


buttons.forEach(function(button) {

    button.addEventListener("click", function() {

        const name = button.dataset.name;
        const price = button.dataset.price;
        const image = button.dataset.image;
        const description = button.dataset.description;


        detail.innerHTML = `
            <h2>${name}</h2>

            <img src="${image}" alt="${name}">

            <p>Giá: ${price}円</p>

            <p>${description}</p>

            <button> Mua sản phẩm </button>
        `;

    });

});