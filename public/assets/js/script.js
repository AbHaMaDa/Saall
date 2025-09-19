// نفس الـ JavaScript السابق بدون أي تغيير
// Application State
let currentTab = "ask";

// Tab Management
window.showTab = function (event, tabName) {
    // Hide all tabs
    document.querySelectorAll(".tab-content").forEach((tab) => {
        tab.classList.remove("active");
    });

    // Remove active class from all buttons
    document.querySelectorAll(".tab-btn").forEach((btn) => {
        btn.classList.remove("active");
    });

    // Show selected tab
    document.getElementById(`${tabName}-tab`).classList.add("active");

    // Add active class to clicked button
    event.target.classList.add("active");

    currentTab = tabName;

    // Load data based on tab
    if (tabName === "answers") {
        loadAnswers();
    } else if (tabName === "admin" && isAdminLoggedIn) {
        loadAdminQuestions();
    }
};

// Question Management
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("question-form");
    const questionInput = document.getElementById("question");
    const charCount = document.getElementById("char-count");

    // Live character count
    questionInput.addEventListener("input", () => {
        charCount.textContent = questionInput.value.length;
    });

    // Form submission via AJAX
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const question = questionInput.value.trim();

        // Validation
        if (question.length < 8) {
            showMessage("يجب أن يكون السؤال أكثر من 8 أحرف", "error");
            return;
        }
        if (question.length > 800) {
            showMessage("يجب أن يكون السؤال أقل من 800 حرف", "error");
            return;
        }

        // Prepare form data
        const formData = new FormData();
        formData.append("content", question);
        formData.append("_token", document.querySelector('input[name="_token"]').value);

        fetch(form.action, {
            method: "POST",
            body: formData,
            headers: {
                Accept: "application/json"
            }
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.errors && data.errors.content) {
                showMessage(data.errors.content[0], "error");
            } else {
                showMessage(
                    "تم إرسال سؤالك بنجاح. سيتم الرد عليه قريباً إن شاء الله",
                    "success"
                );
                form.reset();
                charCount.textContent = "0";
            }
        })
        .catch((error) => {
            console.error(error);
            showMessage("حدث خطأ أثناء إرسال السؤال", "error");
        });
    });
});




function showMessage(text, type) {
    const messageEl = document.getElementById(`${type}-message`);
    const textEl = messageEl.querySelector(".message-text");

    textEl.textContent = text;
    messageEl.classList.remove("hidden");

    setTimeout(() => {
        messageEl.classList.add("hidden");
    }, 4000);
}

document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("nav-toggle");
    const menu = document.getElementById("nav-menu");
    const header = document.querySelector(".header");

    toggle &&
        toggle.addEventListener("click", function () {
            const open = menu.classList.toggle("mobile-open");
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
            if (open) {
                header.style.marginTop = "100px";
            } else {
                header.style.marginTop = "0";
            }
        });
});


document.getElementById("searchForm").addEventListener("submit", function(e) {
    e.preventDefault(); // prevent refresh

    let query = document.getElementById("search-input").value;

    fetch(`/search?search=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => {
            let container = document.getElementById("answers-container");
            container.innerHTML = "";

            if (data.length === 0) {
                container.innerHTML = "<p>لا توجد نتائج مطابقة.</p>";
            } else {
                data.forEach(q => {
                    container.innerHTML += `
                        <div class="answer-item">
                            <div class="question-section">
                                <span class="question-label">السؤال:</span>
                                <div class="question-text">${q.content}</div>
                            </div>
                            <div class="answer-section">
                                <span class="answer-label">الإجابة:</span>
                                <div class="answer-text">${q.answer}</div>
                            </div>
                            <div class="answer-meta">
                                <span class="answer-date">${q.created_at}</span>
                            </div>
                        </div>
                    `;
                });
            }
        })
        .catch(err => console.error("Error:", err));
});
