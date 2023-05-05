import "../app.js";

const chatList = document.querySelector('.chat_list');
const messageSections = document.querySelectorAll('.message-item');

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
      body: JSON.stringify({ chatId }),
    })
      .then(response => response.json())
      .then(data => console.log(data))
      .catch(error => console.error(error));

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
