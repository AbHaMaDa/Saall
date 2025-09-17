// نفس الـ JavaScript السابق بدون أي تغيير
// Application State
let currentTab = "ask";
let isAdminLoggedIn = false;
const ADMIN_PIN = "123"; // يمكن تغييره حسب الحاجة

// Initialize app
document.addEventListener("DOMContentLoaded", function () {
    initializeApp();
});

function initializeApp() {
    setupEventListeners();
    loadAnswers();
    updateCharCounter();
}

function setupEventListeners() {
    // Question form submission
    document
        .getElementById("question-form")
        .addEventListener("submit", handleQuestionSubmit);

    // Character counter
    document
        .getElementById("question")
        .addEventListener("input", updateCharCounter);

    // Search functionality
    document
        .getElementById("search-input")
        .addEventListener("input", searchAnswers);
    document
        .getElementById("search-input")
        .addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                searchAnswers();
            }
        });

    // Admin PIN enter key
    document
        .getElementById("admin-pin")
        .addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                adminLogin();
            }
        });
}

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
function handleQuestionSubmit(e) {
    e.preventDefault();

    const question = document.getElementById("question").value.trim();

    // Validation
    if (question.length < 8) {
        showMessage("يجب أن يكون السؤال أكثر من 8 أحرف", "error");
        return;
    }

    if (question.length > 800) {
        showMessage("يجب أن يكون السؤال أقل من 800 حرف", "error");
        return;
    }

    // Create question object
    const questionObj = {
        id: generateId(),
        question: question,
        answer: "",
        isAnswered: false,
        createdAt: new Date().toISOString(),
        answeredAt: null,
    };

    // Save to localStorage
    saveQuestion(questionObj);

    // Clear form
    document.getElementById("question").value = "";
    updateCharCounter();

    // Show success message
    showMessage(
        "تم إرسال سؤالك بنجاح. سيتم الرد عليه قريباً إن شاء الله",
        "success"
    );
}

function saveQuestion(question) {
    let questions = getQuestions();
    questions.push(question);
    localStorage.setItem("sal_questions", JSON.stringify(questions));
}

function getQuestions() {
    const questions = localStorage.getItem("sal_questions");
    return questions ? JSON.parse(questions) : [];
}

function updateCharCounter() {
    const textarea = document.getElementById("question");
    const counter = document.getElementById("char-count");
    const currentLength = textarea.value.length;

    counter.textContent = currentLength;

    // Update counter color based on length
    const counterElement = document.querySelector(".char-counter");
    counterElement.classList.remove("warning", "error");

    if (currentLength > 700) {
        counterElement.classList.add("error");
    } else if (currentLength > 600) {
        counterElement.classList.add("warning");
    }
}

// Answers Display
function loadAnswers() {
    const questions = getQuestions();
    const answeredQuestions = questions.filter((q) => q.isAnswered);
    const answersContainer = document.getElementById("answers-list");

    if (answeredQuestions.length === 0) {
        answersContainer.innerHTML = `
            <div class="empty-state">
                <p>لا توجد إجابات منشورة حالياً</p>
                <p style="font-size: 0.9rem; color: #bbb;">سيتم عرض الإجابات هنا بمجرد نشرها</p>
            </div>
        `;
        return;
    }

    // Sort by answered date (newest first)
    answeredQuestions.sort(
        (a, b) => new Date(b.answeredAt) - new Date(a.answeredAt)
    );

    answersContainer.innerHTML = answeredQuestions
        .map(
            (q) => `
        <div class="answer-item">
            <div class="question-section">
                <span class="question-label">السؤال:</span>
                <div class="question-text">${escapeHtml(q.question)}</div>
            </div>
            <div class="answer-section">
                <span class="answer-label">الإجابة:</span>
                <div class="answer-text">${escapeHtml(q.answer)}</div>
            </div>
            <div class="answer-meta">
                تاريخ النشر: ${formatDate(q.answeredAt)}
            </div>
        </div>
    `
        )
        .join("");
}

function searchAnswers() {
    const searchTerm = document
        .getElementById("search-input")
        .value.trim()
        .toLowerCase();

    if (!searchTerm) {
        loadAnswers();
        return;
    }

    const questions = getQuestions();
    const answeredQuestions = questions.filter(
        (q) =>
            q.isAnswered &&
            (q.question.toLowerCase().includes(searchTerm) ||
                q.answer.toLowerCase().includes(searchTerm))
    );

    const answersContainer = document.getElementById("answers-list");

    if (answeredQuestions.length === 0) {
        answersContainer.innerHTML = `
            <div class="empty-state">
                <p>لم يتم العثور على نتائج للبحث</p>
                <p style="font-size: 0.9rem; color: #bbb;">"${escapeHtml(
                    searchTerm
                )}"</p>
            </div>
        `;
        return;
    }

    // Sort by answered date (newest first)
    answeredQuestions.sort(
        (a, b) => new Date(b.answeredAt) - new Date(a.answeredAt)
    );

    answersContainer.innerHTML = answeredQuestions
        .map(
            (q) => `
        <div class="answer-item">
            <div class="question-section">
                <span class="question-label">السؤال:</span>
                <div class="question-text">${highlightSearchTerm(
                    escapeHtml(q.question),
                    searchTerm
                )}</div>
            </div>
            <div class="answer-section">
                <span class="answer-label">الإجابة:</span>
                <div class="answer-text">${highlightSearchTerm(
                    escapeHtml(q.answer),
                    searchTerm
                )}</div>
            </div>
            <div class="answer-meta">
                تاريخ النشر: ${formatDate(q.answeredAt)}
            </div>
        </div>
    `
        )
        .join("");
}

// Admin Functions
function adminLogin() {
    const pin = document.getElementById("admin-pin").value;

    if (pin === ADMIN_PIN) {
        isAdminLoggedIn = true;
        document.getElementById("admin-login").classList.add("hidden");
        document.getElementById("admin-panel").classList.remove("hidden");
        document.getElementById("admin-pin").value = "";
        loadAdminQuestions();
        showMessage("تم تسجيل الدخول بنجاح", "success");
    } else {
        showMessage("رمز PIN غير صحيح", "error");
        document.getElementById("admin-pin").value = "";
    }
}

function adminLogout() {
    isAdminLoggedIn = false;
    document.getElementById("admin-login").classList.remove("hidden");
    document.getElementById("admin-panel").classList.add("hidden");
    showMessage("تم تسجيل الخروج بنجاح", "success");
}

function loadAdminQuestions() {
    const questions = getQuestions();
    const adminContainer = document.getElementById("admin-questions");

    if (questions.length === 0) {
        adminContainer.innerHTML = `
            <div class="text-center" style="padding: 40px; color: #999;">
                <p>لا توجد أسئلة حالياً</p>
            </div>
        `;
        return;
    }

    // Sort: unanswered first, then by creation date (newest first)
    questions.sort((a, b) => {
        if (a.isAnswered !== b.isAnswered) {
            return a.isAnswered ? 1 : -1;
        }
        return new Date(b.createdAt) - new Date(a.createdAt);
    });

    adminContainer.innerHTML = questions
        .map(
            (q) => `
        <div class="admin-question-item ${!q.isAnswered ? "unanswered" : ""}">
            <div class="admin-question-header">
                <div class="admin-question-info">
                    <div class="admin-question-text">${escapeHtml(
                        q.question
                    )}</div>
                    <div class="admin-question-meta">
                        التاريخ: ${formatDate(q.createdAt)}
                        ${
                            q.isAnswered
                                ? ` | تم الرد: ${formatDate(q.answeredAt)}`
                                : " | <strong>لم يتم الرد</strong>"
                        }
                    </div>
                </div>
            </div>

            ${
                q.isAnswered
                    ? `<div class="admin-answer-section">
                    <strong>الإجابة:</strong>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0;">
                        ${escapeHtml(q.answer)}
                    </div>
                    <div class="admin-actions">
                        <button onclick="deleteQuestion('${
                            q.id
                        }')" class="btn btn-danger">حذف السؤال</button>
                    </div>
                </div>`
                    : `<div class="admin-answer-section">
                    <textarea id="answer-${q.id}" placeholder="اكتب الإجابة هنا..."></textarea>
                    <div class="admin-actions">
                        <button onclick="saveAnswer('${q.id}')" class="btn btn-success">حفظ الإجابة</button>
                        <button onclick="deleteQuestion('${q.id}')" class="btn btn-danger">حذف السؤال</button>
                    </div>
                </div>`
            }
        </div>
    `
        )
        .join("");
}

function saveAnswer(questionId) {
    const answerText = document
        .getElementById(`answer-${questionId}`)
        .value.trim();

    if (!answerText) {
        showMessage("يرجى كتابة الإجابة أولاً", "error");
        return;
    }

    let questions = getQuestions();
    const questionIndex = questions.findIndex((q) => q.id === questionId);

    if (questionIndex !== -1) {
        questions[questionIndex].answer = answerText;
        questions[questionIndex].isAnswered = true;
        questions[questionIndex].answeredAt = new Date().toISOString();

        localStorage.setItem("sal_questions", JSON.stringify(questions));
        loadAdminQuestions();
        showMessage("تم حفظ الإجابة بنجاح", "success");
    }
}

function deleteQuestion(questionId) {
    if (
        confirm(
            "هل أنت متأكد من حذف هذا السؤال؟ لا يمكن التراجع عن هذا الإجراء."
        )
    ) {
        let questions = getQuestions();
        questions = questions.filter((q) => q.id !== questionId);

        localStorage.setItem("sal_questions", JSON.stringify(questions));
        loadAdminQuestions();
        showMessage("تم حذف السؤال بنجاح", "success");
    }
}

// Utility Functions
function generateId() {
    return Date.now().toString(36) + Math.random().toString(36).substr(2);
}

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    };
    return date.toLocaleDateString("ar-SA", options);
}

function highlightSearchTerm(text, searchTerm) {
    if (!searchTerm) return text;

    const regex = new RegExp(`(${escapeRegex(searchTerm)})`, "gi");
    return text.replace(
        regex,
        '<mark style="background: #fff3cd; padding: 2px 4px; border-radius: 3px;">$1</mark>'
    );
}

function escapeRegex(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
}

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
