document.addEventListener("DOMContentLoaded", () => {
    const stars = document.querySelectorAll(".star");
    const scoreInput = document.getElementById("rating-score");
    const submitButton = document.getElementById("rating-submit");
    const modal = document.getElementById("rating-modal");
    const completeBtn = document.getElementById("complete-transaction-btn");
    const completeContainer = document.getElementById("complete-container");
    const ratingForm = document.getElementById("rating-form");

   
    const openModal = () => {
        if (!modal) return;
        modal.classList.add("is-open");
        modal.setAttribute("aria-hidden", "false");
    };
    const closeModal = () => {
        if (!modal) return;
        modal.classList.remove("is-open");
        modal.setAttribute("aria-hidden", "true");
    };

  
    if (completeBtn) {
        completeBtn.addEventListener("click", () => {
            openModal();
        });
    }

    
    if (modal) {
        modal.addEventListener("click", (e) => {
            if (e.target && e.target.dataset && e.target.dataset.close === "true") {
                closeModal();
            }
        });
    }

    
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeModal();
        }
    });

    
    let selectedScore = 0;

    if (scoreInput && scoreInput.value) {
        selectedScore = parseInt(scoreInput.value);
        stars.forEach(s => {
            s.classList.toggle("selected", parseInt(s.dataset.value) <= selectedScore);
        });
        if (submitButton) submitButton.disabled = selectedScore <= 0;
    }

    stars.forEach(star => {
        star.addEventListener("mouseover", () => {
            const value = parseInt(star.dataset.value);
            stars.forEach(s => {
                s.classList.toggle("hovered", parseInt(s.dataset.value) <= value);
            });
        });

        star.addEventListener("mouseout", () => {
            stars.forEach(s => s.classList.remove("hovered"));
        });

        star.addEventListener("click", () => {
            const value = parseInt(star.dataset.value);

            if (selectedScore === value) {
                selectedScore = 0;
                scoreInput.value = "";
                if (submitButton) submitButton.disabled = true;
                stars.forEach(s => s.classList.remove("selected"));
            } else {
                selectedScore = value;
                scoreInput.value = selectedScore;
                if (submitButton) submitButton.disabled = false;
                stars.forEach(s => {
                    s.classList.toggle("selected", parseInt(s.dataset.value) <= selectedScore);
                });
            }
        });
    });

    
    if (ratingForm) {
        ratingForm.addEventListener("submit", () => {
            
            if (completeContainer) {
                completeContainer.remove();
            }
            closeModal();
        });
    }
});
