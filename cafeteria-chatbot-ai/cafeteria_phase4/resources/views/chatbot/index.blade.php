<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ auth()->user()->isAdmin() ? 'Admin Assistant' : 'Cafeteria Assistant' }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">

            <div id="conversation" class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                <div class="max-w-md rounded-lg bg-gray-100 p-3 text-sm text-gray-700">
                    @if (auth()->user()->isAdmin())
                        Hi {{ auth()->user()->name }}. Ask me about sales, stock, or orders.
                    @else
                        Hi {{ auth()->user()->name }}. Ask me what to eat or drink today.
                    @endif
                </div>
            </div>

            <form id="chat-form">
                @csrf
                <textarea id="message" name="message" required maxlength="2000" rows="3"
                    class="w-full border-gray-300 rounded-md text-sm"
                    placeholder="{{ auth()->user()->isAdmin() ? 'e.g. How many orders were placed today?' : 'e.g. Recommend something spicy under 150 EGP' }}"></textarea>
                <div class="mt-3 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold">
                        Send
                    </button>
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
            q.className = 'ml-auto max-w-md rounded-lg bg-indigo-600 text-white p-3 text-sm';
            q.textContent = message;
            conversation.append(q);

            const pending = document.createElement('div');
            pending.className = 'max-w-md rounded-lg bg-gray-100 p-3 text-sm text-gray-500';
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
                pending.classList.remove('text-gray-500');
                pending.classList.add('text-gray-800');
            } catch (error) {
                pending.textContent = error.message;
                pending.classList.remove('text-gray-500');
                pending.classList.add('text-red-600');
            } finally {
                button.disabled = false;
                conversation.scrollTop = conversation.scrollHeight;
            }
        });
    </script>
</x-app-layout>
