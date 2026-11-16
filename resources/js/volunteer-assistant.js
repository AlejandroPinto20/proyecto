document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('assistant-toggle');
    const panel = document.getElementById('assistant-panel');
    const form = document.getElementById('assistant-form');
    const input = document.getElementById('assistant-input');
    const messages = document.getElementById('assistant-messages');

    if (!toggle || !panel || !form || !input || !messages) return;

    const addMessage = (content, type) => {
        const message = document.createElement('div');
        message.className = type === 'user'
            ? 'ml-8 rounded-lg bg-blue-700 p-3 text-white'
            : 'mr-8 rounded-lg bg-white p-3 text-slate-700 shadow-sm';
        message.textContent = content;
        messages.appendChild(message);
        messages.scrollTop = messages.scrollHeight;
        return message;
    };

    toggle.addEventListener('click', () => panel.classList.toggle('hidden'));

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const question = input.value.trim();
        if (!question) return;

        addMessage(question, 'user');
        input.value = '';
        input.disabled = true;
        const pending = addMessage('Consultando al asistente…', 'assistant');

        try {
            const response = await fetch('/api/assistant/ask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ message: question }),
            });
            const payload = await response.json();
            pending.textContent = payload.answer || payload.message || 'No pude responder esa consulta.';
        } catch (error) {
            pending.textContent = 'No fue posible conectar con el asistente local.';
        } finally {
            input.disabled = false;
            input.focus();
            messages.scrollTop = messages.scrollHeight;
        }
    });
});
