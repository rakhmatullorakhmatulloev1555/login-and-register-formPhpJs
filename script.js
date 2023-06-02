// Отображение сообщения об успешной авторизации
function showSuccessMessage() {
    var successMessage = document.getElementById('success_message');
    successMessage.style.display = 'block';
}

// Скрытие сообщения об успешной авторизации

// Скрываем блок сообщения через 10 секунд

setTimeout(function() {
      success_message.style.display = 'none';
    }, 10000);



