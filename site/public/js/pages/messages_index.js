import "../app.js";

const chatList = document.querySelector('.chat_list');
const messageSections = document.querySelectorAll('.message-item');

function decrementNotificationCount() {
  const notificationCount = document.getElementById("notification-count");
  let count = parseInt(notificationCount.innerText);
  count--;
  notificationCount.innerText = count;
  if (count === 0) {
    notificationCount.style.display = "none";
  }
}
chatList.addEventListener('click', e => {
  if (e.target && e.target.closest('.chat_item')) {
    const chatId = e.target.closest('.chat_item').dataset.chatId;

    fetch("/message-update/" + chatId, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document
          .querySelector('meta[name="csrf-token"]')
          .getAttribute("content"),
      },
      body: { chatId },
    }).catch(error => console.error(error));

    messageSections.forEach(section => {
      if (section.classList.contains(`chat-${chatId}`)) {
        section.style.display = 'block';
      } else {
        section.style.display = 'none';
      }
    });

    chatList.querySelectorAll('.chat_item').forEach(item => {
      if (item.dataset.chatId === chatId) {
        item.classList.add('selected');
        
        if (item.classList.contains("unread")) {
          decrementNotificationCount();
          item.classList.remove('unread');
        }
      } else {
        item.classList.remove('selected');
      }
    });
  }
});

if (chatList.children.length > 0) {
  const lastChat = chatList.querySelector(':last-child');
  lastChat.click();
}
