// نفس الـ JavaScript السابق بدون أي تغيير
// Application State
let currentTab = "ask"; // تبويب افتراضي

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

    // Add active class to clicked button أو للزر المناسب لو event مش موجود
    const btn =
        event?.target ||
        document.querySelector(`.tab-btn[data-tab="${tabName}"]`);
    if (btn) btn.classList.add("active");

    // حفظ آخر تبويب تم اختياره
    localStorage.setItem("lastActiveTab", tabName);

    currentTab = tabName;
};

// عند تحميل الصفحة
document.addEventListener("DOMContentLoaded", () => {
    const lastTab = localStorage.getItem("lastActiveTab") || currentTab;
    showTab(null, lastTab); // تمرير null كـ event
});

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
        formData.append(
            "_token",
            document.querySelector('input[name="_token"]').value
        );

        fetch(form.action, {
            method: "POST",
            body: formData,
            headers: {
                Accept: "application/json",
            },
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
    // جميع الرسائل
    const allMessages = document.querySelectorAll(".message");

    // 1. إخفاء كل الرسائل فورًا وإلغاء أي timeout
    allMessages.forEach((msg) => {
        if (msg.timeoutId) {
            clearTimeout(msg.timeoutId);
            msg.timeoutId = null;
        }
        msg.classList.add("hidden");
    });

    // 2. اختيار الرسالة الجديدة
    const messageEl = document.getElementById(`${type}-message`);
    const textEl = messageEl.querySelector(".message-text");

    // 3. تحديث النص
    textEl.textContent = text;

    // 4. إظهار الرسالة الجديدة
    messageEl.classList.remove("hidden");
    messageEl.classList.remove("fading");

    // 5. ضبط timeout لإخفاءها بعد 4 ثواني
    messageEl.timeoutId = setTimeout(() => {
        messageEl.classList.add("fading");
        messageEl.timeoutId = null;
    }, 4000);
}

document.addEventListener("DOMContentLoaded", () => {
    const flashMsg = document.getElementById("flash-message");

    if (flashMsg) {
        if (flashMsg.timeoutId) {
            clearTimeout(flashMsg.timeoutId);
        }
        // 4. إظهار الرسالة الجديدة
        flashMsg.classList.remove("hidden");
        flashMsg.classList.remove("fading");

        // 5. ضبط timeout لإخفاءها بعد 4 ثواني
        flashMsg.timeoutId = setTimeout(() => {
            flashMsg.classList.add("fading");
            flashMsg.timeoutId = null;
        }, 4000);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("nav-toggle");
    const menu = document.getElementById("nav-menu");
    const header = document.querySelector(".header");

    toggle &&
        toggle.addEventListener("click", function () {
            const open = menu.classList.toggle("mobile-open");
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
            header.style.marginTop = open ? "100px" : "0";
        });
});

const token = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// العام
document
    .getElementById("searchFormPublic")
    .addEventListener("submit", function (e) {
        e.preventDefault();
        const query = document.getElementById("search-input-public").value;
        fetchAnswers(
            `/search?search=${encodeURIComponent(query)}`,
            "answers-container-public"
        );
    });

// الخاص
document
    .getElementById("searchFormMine")
    .addEventListener("submit", function (e) {
        e.preventDefault();
        const query = document.getElementById("search-input-mine").value;
        fetchAnswers(
            `/search/visitor?search=${encodeURIComponent(query)}`,
            "answers-container-mine"
        );
    });

// دالة عامة لعرض النتائج
function fetchAnswers(url, containerId) {
    fetch(url)
        .then((res) => res.json())
        .then((data) => {
            const container = document.getElementById(containerId);
            container.innerHTML = "";

            const answeredQuestions = data.questions.filter(
                (q) => q.is_answered == 1
            );
            if (answeredQuestions.length === 0) {
                container.innerHTML = "<p>لا توجد نتائج مطابقة.</p>";
                return;
            }

            const isAdmin = data.user?.privilege_level === 2;

            answeredQuestions.forEach((q) => {
                const trashIcon = isAdmin
                    ? `<i class="fa-solid fa-trash icon-trash" data-bs-toggle="modal" data-bs-target="#exampleModalDeleteUnanswer${q.id}"></i>`
                    : "";
                const dateObj = new Date(q.created_at);

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
                        <div class="answer-meta d-flex justify-content-between align-items-center">
                            ${trashIcon}
                            <span class="answer-date">${dateObj.toLocaleDateString()}  \\\  ${dateObj.toLocaleTimeString()}</span>
                        </div>
                    </div>
                    ${
                        isAdmin
                            ? `
                    <div class="modal fade" id="exampleModalDeleteUnanswer${q.id}" tabindex="-1" aria-labelledby="exampleModalDeleteUnanswer${q.id}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    <h1 class="modal-title fs-5">حذف السؤال</h1>
                                </div>
                                <div class="modal-body">
                                    هل أنت متأكد أنك تريد حذف السؤال؟
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                    <form class="model-form" action="/questions/${q.id}" method="POST">
                                        <input type="hidden" name="_token" value="${token}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger">حذف السؤال</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>`
                            : ""
                    }
                `;
            });
        })
        .catch((err) => console.error("Error:", err));
}

document.addEventListener("DOMContentLoaded", function () {
    const allBtn = document.getElementById("all");
    const mineBtn = document.getElementById("mine");
    const publicSearch = document.querySelector(".public-search");
    const mineSearch = document.querySelector(".mine-search");

    allBtn.addEventListener("click", () => {
        toggleAnswers("all");
        localStorage.setItem("Qtab", "all");
    });
    mineBtn.addEventListener("click", () => {
        toggleAnswers("mine");
        localStorage.setItem("Qtab", "mine");
    });

    function toggleAnswers(type) {
        const publicContainer = document.getElementById(
            "answers-container-public"
        );
        const mineContainer = document.getElementById("answers-container-mine");

        // Default hide/show
        if (type === "all") {
            publicContainer.classList.remove("hidden");
            mineContainer.classList.add("hidden");
            publicSearch.classList.remove("hidden");
            mineSearch.classList.add("hidden");

            // تحديث الأزرار
            allBtn.classList.remove("btn-light");
            allBtn.classList.add("btn-primary");
            mineBtn.classList.remove("btn-primary");
            mineBtn.classList.add("btn-light");
        } else {
            publicContainer.classList.add("hidden");
            mineContainer.classList.remove("hidden");
            publicSearch.classList.add("hidden");
            mineSearch.classList.remove("hidden");

            // تحديث الأزرار
            mineBtn.classList.remove("btn-light");
            mineBtn.classList.add("btn-primary");
            allBtn.classList.remove("btn-primary");
            allBtn.classList.add("btn-light");
        }
    }

    // تفعيل التبويب الافتراضي عند تحميل الصفحة
    const savedTab = localStorage.getItem("Qtab");
    toggleAnswers(savedTab);
});
