// Переменная для переключения изображения
var isMainImage = true;

// Функция для отображения текущего времени
function showTime() {
    var now = new Date();
    var timeString = now.toLocaleTimeString();
    var clockBlock = document.getElementById("clock");

    if (clockBlock) {
        clockBlock.innerHTML = timeString;
    }
}

// Функция для отображения текущей даты
function showDate() {
    var now = new Date();
    var dateBlock = document.getElementById("currentDate");

    if (dateBlock) {
        dateBlock.innerHTML = now.toLocaleDateString();
    }
}

// Функция приветствия
function showWelcomeMessage() {
    var block = document.getElementById("welcomeMessage");

    if (block) {
        block.innerHTML = "Добро пожаловать на сайт об искусственном интеллекте.";
    }
}

// запуск после загрузки
document.addEventListener("DOMContentLoaded", function () {
    showWelcomeMessage();
    showTime();
    showDate();
    setInterval(showTime, 1000);
});

// смена изображения
function changeImage() {
    var image = document.getElementById("mainImage");

    if (image) {
        if (isMainImage) {
            image.src = "images/ai2.jpg"; // استخدم صورة ثانية مناسبة
        } else {
            image.src = "images/ai.jpg";
        }

        isMainImage = !isMainImage;
    }
}

// изменение цвета текста
function changeTextColor(element) {
    element.style.color = "darkred";
}

// возврат цвета
function resetTextColor(element) {
    element.style.color = "#222";
}

// прозрачность картинки
function makeTransparent(element) {
    element.style.opacity = "0.7";
}

function resetTransparency(element) {
    element.style.opacity = "1";
}

// увеличение карточки
function enlargeCard(element) {
    element.style.transform = "scale(1.03)";
}

function resetCard(element) {
    element.style.transform = "scale(1)";
}

// выпадающее меню
function showDropdown() {
    var menu = document.getElementById("dropdownMenu");

    if (menu) {
        menu.style.display = "block";
    }
}

function hideDropdown() {
    var menu = document.getElementById("dropdownMenu");

    if (menu) {
        menu.style.display = "none";
    }
}

// длина имени
function countNameLength() {
    var nameInput = document.getElementById("name");
    var info = document.getElementById("nameInfo");

    if (nameInput && info) {
        var text = nameInput.value;
        info.innerHTML = "Длина имени: " + text.length;
    }
}

// подсказка для email
function showHint() {
    var hint = document.getElementById("emailHint");

    if (hint) {
        hint.innerHTML = "Пример: student@example.com";
    }
}

function hideHint() {
    var hint = document.getElementById("emailHint");

    if (hint) {
        hint.innerHTML = "";
    }
}