import "../app.js";

const chatList = document.querySelector('.chat_list');
const dataMessage = document.querySelectorAll('.message-item_date');
const messageSections = document.querySelectorAll('.message-item');
const chatForm = document.querySelectorAll('.chat-form');
const chatFormButtons = document.querySelectorAll('.chat-form-buttons');

if (chatList) {
  chatList.addEventListener('click', (event) => {
    const chatItem = event.target.closest('.chat_item');
    if (chatItem) {
      const chatId = chatItem.dataset.chatId;
      chatForm.action = `/message-send/${chatId}`;
    }
  });
}

function decrementNotificationCount() {
  const notificationCount = document.getElementById("notification-count");
  let count = parseInt(notificationCount.innerText);
  count--;
  notificationCount.innerText = count;
  if (count === 0) {
    notificationCount.style.display = "none";
  }
}
if (chatList) {
  chatList.addEventListener('click', e => {
    if (e.target && e.target.closest('.chat_item')) {
      const chatId = e.target.closest('.chat_item').dataset.chatId;

      console.log(chatId);
      fetch("/message-read/" + chatId, {
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

      chatForm.forEach(chatForm => {
        const chatId = e.target.closest('.chat_item').dataset.chatId;
        if (chatForm.classList.contains(`chat-${chatId}`)) {
          chatForm.style.display = 'flex';
        } else {
          chatForm.style.display = 'none';
        }
      });

      chatFormButtons.forEach(chatFormButtons => {
        const chatId = e.target.closest('.chat_item').dataset.chatId;
        if (chatFormButtons.classList.contains(`chat-${chatId}`)) {
          chatFormButtons.style.display = 'flex';
        } else {
          chatFormButtons.style.display = 'none';
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

}