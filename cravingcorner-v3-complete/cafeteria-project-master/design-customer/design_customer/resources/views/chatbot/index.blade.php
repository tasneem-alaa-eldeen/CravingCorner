<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">
            {{ auth()->user()->isAdmin() ? 'Admin Assistant' : 'CravingCorner Assistant' }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="card-ticket" style="padding:1.5rem;">

            <div id="conversation" style="display:flex; flex-direction:column; gap:0.75rem; margin-bottom:1.25rem; max-height:24rem; overflow-y:auto;">
                <div style="max-width:80%; border-radius:10px; background: var(--cc-paper); border:1px solid var(--cc-line); padding:0.6rem 0.9rem; font-size:0.875rem;">
                    @if (auth()->user()->isAdmin())
                        Hi {{ auth()->user()->name }}. Ask me about sales, stock, or orders.
                    @else
                        Hi {{ auth()->user()->name }}. Ask me what to eat or drink today.
                    @endif
                </div>
            </div>

            <form id="chat-form">
                @csrf
                <textarea id="message" name="message" required maxlength="2000" rows="3" class="input-cc" style="width:100%;"
                    placeholder="{{ auth()->user()->isAdmin() ? 'e.g. How many orders were placed today?' : 'e.g. Recommend something spicy under 150 EGP' }}"></textarea>
                <div style="margin-top:0.75rem; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn-mustard">Send</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('chat-form');
        const conversation = document.getElementById('conversation');
        const textarea = document.getElementById('message');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const message = textarea.value.trim();
            if (!message) return;

            const q = document.createElement('div');
            q.style.cssText = 'align-self:flex-end; max-width:80%; border-radius:10px; background:var(--cc-mustard); color:var(--cc-ink); padding:0.6rem 0.9rem; font-size:0.875rem; font-weight:600;';
            q.textContent = message;
            conversation.append(q);

            const pending = document.createElement('div');
            pending.style.cssText = 'max-width:80%; border-radius:10px; background:var(--cc-paper); border:1px solid var(--cc-line); padding:0.6rem 0.9rem; font-size:0.875rem; color:var(--cc-text-muted);';
            pending.textContent = 'Thinking...';
            conversation.append(pending);
            conversation.scrollTop = conversation.scrollHeight;

            const button = form.querySelector('button');
            button.disabled = true;
            textarea.value = '';

            try {
                const response = await fetch('{{ route('chatbot.respond') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message }),
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message ?? 'The request failed.');

                pending.textContent = result.content;
                pending.style.color = 'var(--cc-ink)';
            } catch (error) {
                pending.textContent = error.message;
                pending.style.color = 'var(--cc-clay)';
            } finally {
                button.disabled = false;
                conversation.scrollTop = conversation.scrollHeight;
            }
        });
    </script>
</x-app-layout>
